DELIMITER $$

CREATE PROCEDURE update_advance_balance(
    IN p_advance_id BIGINT(20)
)
BEGIN
    DECLARE w_collection_id BIGINT(20);
    DECLARE w_advance_id BIGINT(20);
    DECLARE w_amount, w_change DECIMAL(12,2) DEFAULT 0.00;
    DECLARE w_parallel_rate DECIMAL(10,2) DEFAULT 0.00;
    DECLARE w_currency_type_id BIGINT(20);
    DECLARE w_done INT(11) DEFAULT 0;
    DECLARE w_final_balance DECIMAL(12,2) DEFAULT 0.00;
    
    -- Variables para la última cobranza procesada
    DECLARE w_last_collection_id BIGINT(20);
    DECLARE w_last_payment_method_id BIGINT(20);
    DECLARE w_last_payment_date DATE;
    DECLARE w_last_digital_wallet_id BIGINT(20);
    DECLARE w_last_bank_id BIGINT(20);
    DECLARE w_last_operation_date DATE;
    DECLARE w_last_operation_number VARCHAR(20);
    DECLARE w_last_lote_number VARCHAR(30);
    DECLARE w_last_for_digits VARCHAR(4);

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET w_done = 1;

    -- Reinicia el saldo al monto original del anticipo
    UPDATE advances
    SET saldo = amount
    WHERE id = p_advance_id;

    -- Obtiene el tipo de moneda del anticipo
    SET @advance_currency = 0;
    SELECT currency_type_id INTO @advance_currency
    FROM advances
    WHERE id = p_advance_id;

    proceso: BEGIN
        DECLARE Rs CURSOR FOR
            -- Cobranzas que utilizan este anticipo
            SELECT id, advance_id, amount, parallel_rate, currency_type_id, `change`,
                   payment_method_id, payment_date, digital_wallet_id, bank_id,
                   operation_date, operation_number, lote_number, for_digits
            FROM collections
            WHERE advance_id = p_advance_id
              AND status = 1;

        OPEN Rs;

        FETCH Rs INTO w_last_collection_id, w_advance_id, w_amount, w_parallel_rate, 
                      w_currency_type_id, w_change,
                      w_last_payment_method_id, w_last_payment_date, w_last_digital_wallet_id,
                      w_last_bank_id, w_last_operation_date, w_last_operation_number,
                      w_last_lote_number, w_last_for_digits;

        WHILE NOT w_done DO
            -- Si la moneda de la cobranza es igual a la moneda del anticipo
            IF w_currency_type_id = @advance_currency THEN
                UPDATE advances
                SET saldo = saldo - (w_amount - COALESCE(w_change, 0))
                WHERE id = p_advance_id;

            -- Si las monedas son diferentes, aplicar conversión
            ELSEIF w_currency_type_id != @advance_currency THEN
                -- Anticipo en PEN (1) y cobranza en USD (2)
                IF @advance_currency = 1 AND w_currency_type_id = 2 THEN
                    UPDATE advances
                    SET saldo = saldo - ((w_amount - COALESCE(w_change, 0)) * w_parallel_rate)
                    WHERE id = p_advance_id;
                END IF;

                -- Anticipo en USD (2) y cobranza en PEN (1)
                IF @advance_currency = 2 AND w_currency_type_id = 1 THEN
                    UPDATE advances
                    SET saldo = saldo - ((w_amount - COALESCE(w_change, 0)) / w_parallel_rate)
                    WHERE id = p_advance_id;
                END IF;
            END IF;

            FETCH Rs INTO w_last_collection_id, w_advance_id, w_amount, w_parallel_rate, 
                          w_currency_type_id, w_change,
                          w_last_payment_method_id, w_last_payment_date, w_last_digital_wallet_id,
                          w_last_bank_id, w_last_operation_date, w_last_operation_number,
                          w_last_lote_number, w_last_for_digits;
        END WHILE;

        CLOSE Rs;
    END proceso;

    -- Obtener el saldo final después de procesar todas las cobranzas
    SELECT saldo INTO w_final_balance
    FROM advances
    WHERE id = p_advance_id;

    -- Si el saldo está en el rango -0.10 a 0.10, actualizar la última cobranza con rounding
    IF w_final_balance BETWEEN -0.10 AND 0.10 AND w_final_balance != 0 THEN
        -- Actualizar la última cobranza procesada agregando el saldo al campo rounding
        UPDATE collections
        SET rounding = ABS(w_final_balance),
            updated_at = NOW()
        WHERE id = w_last_collection_id;

        -- Actualizar el saldo a 0
        UPDATE advances
        SET saldo = 0
        WHERE id = p_advance_id;
    END IF;

END$$

DELIMITER ;
