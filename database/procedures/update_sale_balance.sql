DELIMITER $$

CREATE PROCEDURE update_sale_balance(
    IN p_company_id BIGINT(20),
    IN p_document_type_id BIGINT(20),
    IN p_serie VARCHAR(10),
    IN p_document_number VARCHAR(20)
)
BEGIN
    DECLARE w_sale_id, w_company_id, w_currency_type_id BIGINT(20);
    DECLARE w_document_type_id BIGINT(20);
    DECLARE w_serie VARCHAR(10);
    DECLARE w_document_number VARCHAR(20);
    DECLARE w_amount, w_change DECIMAL(12,2) DEFAULT 0.00;
    DECLARE w_parallel_rate DECIMAL(10,2) DEFAULT 0.00;
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
    DECLARE w_last_credit_document_type_id INT;
    DECLARE w_last_credit_serie VARCHAR(6);
    DECLARE w_last_credit_correlative VARCHAR(10);

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET w_done = 1;

    -- Reinicia el saldo al total original
    UPDATE sales
    SET saldo = total
    WHERE company_id = p_company_id
      AND document_type_id = p_document_type_id
      AND serie = p_serie COLLATE utf8mb4_unicode_ci
      AND document_number = p_document_number COLLATE utf8mb4_unicode_ci;

    -- Obtiene el tipo de moneda de la venta
    SET @sale_currency = 0;
    SELECT currency_type_id INTO @sale_currency
    FROM sales
    WHERE company_id = p_company_id
      AND document_type_id = p_document_type_id
      AND serie = p_serie COLLATE utf8mb4_unicode_ci
      AND document_number = p_document_number COLLATE utf8mb4_unicode_ci;

    proceso: BEGIN
        DECLARE Rs CURSOR FOR
            -- Cobranzas donde este documento es la venta
            SELECT id, sale_id, company_id, sale_document_type_id, sale_serie, sale_correlative,
                   amount, parallel_rate, currency_type_id, `change`,
                   payment_method_id, payment_date, digital_wallet_id, bank_id,
                   operation_date, operation_number, lote_number, for_digits,
                   credit_document_type_id, credit_serie, credit_correlative
            FROM collections
            WHERE company_id = p_company_id
              AND sale_document_type_id = p_document_type_id
              AND sale_serie = p_serie COLLATE utf8mb4_unicode_ci
              AND sale_correlative = p_document_number COLLATE utf8mb4_unicode_ci
              AND status = 1
            
            UNION ALL
            
            -- Cobranzas donde este documento es la nota de crédito
            SELECT id, sale_id, company_id, sale_document_type_id, sale_serie, sale_correlative,
                   amount, parallel_rate, currency_type_id, `change`,
                   payment_method_id, payment_date, digital_wallet_id, bank_id,
                   operation_date, operation_number, lote_number, for_digits,
                   credit_document_type_id, credit_serie, credit_correlative
            FROM collections
            WHERE company_id = p_company_id
              AND credit_document_type_id = p_document_type_id
              AND credit_serie = p_serie COLLATE utf8mb4_unicode_ci
              AND credit_correlative = p_document_number COLLATE utf8mb4_unicode_ci
              AND status = 1;

        OPEN Rs;

        FETCH Rs INTO w_last_collection_id, w_sale_id, w_company_id, w_document_type_id, w_serie, w_document_number,
                      w_amount, w_parallel_rate, w_currency_type_id, w_change,
                      w_last_payment_method_id, w_last_payment_date, w_last_digital_wallet_id,
                      w_last_bank_id, w_last_operation_date, w_last_operation_number,
                      w_last_lote_number, w_last_for_digits, w_last_credit_document_type_id,
                      w_last_credit_serie, w_last_credit_correlative;

        WHILE NOT w_done DO
            IF w_currency_type_id = @sale_currency THEN
                UPDATE sales
                SET saldo = saldo - (w_amount - COALESCE(w_change, 0))
                WHERE company_id = p_company_id
                  AND document_type_id = p_document_type_id
                  AND serie = p_serie COLLATE utf8mb4_unicode_ci
                  AND document_number = p_document_number COLLATE utf8mb4_unicode_ci;

            ELSEIF w_currency_type_id != @sale_currency THEN
                IF @sale_currency = 1 AND w_currency_type_id = 2 THEN
                    UPDATE sales
                    SET saldo = saldo - ((w_amount - COALESCE(w_change, 0)) * w_parallel_rate)
                    WHERE company_id = p_company_id
                      AND document_type_id = p_document_type_id
                      AND serie = p_serie COLLATE utf8mb4_unicode_ci
                      AND document_number = p_document_number COLLATE utf8mb4_unicode_ci;
                END IF;

                IF @sale_currency = 2 AND w_currency_type_id = 1 THEN
                    UPDATE sales
                    SET saldo = saldo - ((w_amount - COALESCE(w_change, 0)) / w_parallel_rate)
                    WHERE company_id = p_company_id
                      AND document_type_id = p_document_type_id
                      AND serie = p_serie COLLATE utf8mb4_unicode_ci
                      AND document_number = p_document_number COLLATE utf8mb4_unicode_ci;
                END IF;
            END IF;

            FETCH Rs INTO w_last_collection_id, w_sale_id, w_company_id, w_document_type_id, w_serie, w_document_number,
                          w_amount, w_parallel_rate, w_currency_type_id, w_change,
                          w_last_payment_method_id, w_last_payment_date, w_last_digital_wallet_id,
                          w_last_bank_id, w_last_operation_date, w_last_operation_number,
                          w_last_lote_number, w_last_for_digits, w_last_credit_document_type_id,
                          w_last_credit_serie, w_last_credit_correlative;
        END WHILE;

        CLOSE Rs;
    END proceso;

    -- Obtener el saldo final después de procesar todas las cobranzas
    SELECT saldo INTO w_final_balance
    FROM sales
    WHERE company_id = p_company_id
      AND document_type_id = p_document_type_id
      AND serie = p_serie COLLATE utf8mb4_unicode_ci
      AND document_number = p_document_number COLLATE utf8mb4_unicode_ci;

    -- Si el saldo está en el rango -0.10 a 0.10, actualizar la última cobranza con rounding
    IF w_final_balance BETWEEN -0.10 AND 0.10 AND w_final_balance != 0 THEN
        -- Actualizar la última cobranza procesada agregando el saldo al campo rounding
        UPDATE collections
        SET rounding = ABS(w_final_balance),
            updated_at = NOW()
        WHERE id = w_last_collection_id;

        -- Actualizar el saldo a 0 y marcar como pagado
        UPDATE sales
        SET saldo = 0, payment_status = 1
        WHERE company_id = p_company_id
          AND document_type_id = p_document_type_id
          AND serie = p_serie COLLATE utf8mb4_unicode_ci
          AND document_number = p_document_number COLLATE utf8mb4_unicode_ci;
    END IF;

END$$

DELIMITER ;
