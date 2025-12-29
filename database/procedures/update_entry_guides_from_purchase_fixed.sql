DELIMITER $$

USE `backend_hsgestion`$$

DROP PROCEDURE IF EXISTS `update_entry_guides_from_purchase`$$

CREATE DEFINER=`root`@`%` PROCEDURE `update_entry_guides_from_purchase`(
    IN p_company_id BIGINT,
    IN p_supplier_id BIGINT,
    IN p_document_type_id BIGINT,
    IN p_reference_serie VARCHAR(10),
    IN p_reference_correlative VARCHAR(15)
)
BEGIN
    DECLARE v_entry_guide_id BIGINT;
    DECLARE v_purchase_id BIGINT;
    DECLARE v_article_id BIGINT;
    DECLARE v_cantidad_update DECIMAL(10,2); -- Cantidad consumida total
    DECLARE v_entry_guide_article_id BIGINT;
    DECLARE v_quantity_original DECIMAL(10,2);
    DECLARE v_saldo_actual DECIMAL(10,2);
    DECLARE v_cantidad_a_descontar DECIMAL(10,2);
    DECLARE v_cantidad_pendiente DECIMAL(10,2); -- Cantidad que aún falta descontar
    DECLARE v_done INT DEFAULT 0;
    DECLARE v_done_inner INT DEFAULT 0;

    -- Cursor principal para obtener las compras
    DECLARE cur_purchases CURSOR FOR
        SELECT DISTINCT
            pur.id AS purchase_id
        FROM purchase pur
        INNER JOIN shopping_income_guide sig 
            ON sig.purchase_id = pur.id
        WHERE pur.company_id = p_company_id
          AND pur.supplier_id = p_supplier_id
          AND pur.document_type_id = p_document_type_id
          AND pur.reference_serie = p_reference_serie COLLATE utf8mb4_unicode_ci
          AND pur.reference_correlative = p_reference_correlative COLLATE utf8mb4_unicode_ci;

    -- Cursor para los detalles de compra (artículos y cantidades CONSUMIDAS)
    DECLARE cur_purchase_details CURSOR FOR
        SELECT 
            dpg.article_id,
            dpg.cantidad_update  -- Usar la cantidad consumida, no la cantidad total
        FROM detail_purchase_guides dpg
        WHERE dpg.purchase_id = v_purchase_id
          AND dpg.cantidad_update > 0;  -- Solo procesar artículos con cantidad consumida

    -- Cursor para las guías de entrada ordenadas por fecha (FIFO)
    DECLARE cur_entry_guides CURSOR FOR
        SELECT 
            ega.id AS entry_guide_article_id,
            ega.entry_guide_id,
            ega.quantity,
            ega.saldo
        FROM entry_guide_article ega
        INNER JOIN entry_guides eg ON eg.id = ega.entry_guide_id
        INNER JOIN shopping_income_guide sig ON sig.entry_guide_id = eg.id
        WHERE sig.purchase_id = v_purchase_id
          AND ega.article_id = v_article_id
        ORDER BY eg.created_at ASC, eg.id ASC;  -- FIFO: primero las más antiguas

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_done = 1;

    OPEN cur_purchases;

    purchase_loop: LOOP
        FETCH cur_purchases INTO v_purchase_id;

        IF v_done THEN
            LEAVE purchase_loop;
        END IF;

        -- Abrir cursor de detalles de compra
        BEGIN
            DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_done_inner = 1;
            
            OPEN cur_purchase_details;

            detail_loop: LOOP
                SET v_done_inner = 0;
                FETCH cur_purchase_details INTO v_article_id, v_cantidad_update;

                IF v_done_inner THEN
                    LEAVE detail_loop;
                END IF;

                -- Para cada artículo, recalcular el saldo desde cero
                -- Primero, restaurar todos los saldos a su cantidad original
                UPDATE entry_guide_article ega
                INNER JOIN entry_guides eg ON eg.id = ega.entry_guide_id
                INNER JOIN shopping_income_guide sig ON sig.entry_guide_id = eg.id
                SET ega.saldo = ega.quantity
                WHERE sig.purchase_id = v_purchase_id
                  AND ega.article_id = v_article_id;

                -- Ahora, descontar la cantidad consumida siguiendo FIFO
                SET v_cantidad_pendiente = v_cantidad_update;

                BEGIN
                    DECLARE v_done_guides INT DEFAULT 0;
                    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_done_guides = 1;

                    OPEN cur_entry_guides;

                    guide_loop: LOOP
                        SET v_done_guides = 0;
                        FETCH cur_entry_guides INTO v_entry_guide_article_id, v_entry_guide_id, v_quantity_original, v_saldo_actual;

                        IF v_done_guides THEN
                            LEAVE guide_loop;
                        END IF;

                        -- Si ya no hay cantidad por descontar, salir
                        IF v_cantidad_pendiente <= 0 THEN
                            LEAVE guide_loop;
                        END IF;

                        -- Calcular cuánto descontar de esta guía
                        IF v_quantity_original >= v_cantidad_pendiente THEN
                            -- Esta guía tiene suficiente para cubrir lo que falta
                            SET v_cantidad_a_descontar = v_cantidad_pendiente;
                            SET v_cantidad_pendiente = 0;
                        ELSE
                            -- Descontar toda la cantidad de esta guía y continuar
                            SET v_cantidad_a_descontar = v_quantity_original;
                            SET v_cantidad_pendiente = v_cantidad_pendiente - v_quantity_original;
                        END IF;

                        -- Actualizar el saldo de la guía (saldo = quantity - cantidad_descontada)
                        UPDATE entry_guide_article
                        SET 
                            saldo = quantity - v_cantidad_a_descontar,
                            updated_at = NOW()
                        WHERE id = v_entry_guide_article_id;

                        -- Actualizar timestamp de la guía
                        UPDATE entry_guides
                        SET updated_at = NOW()
                        WHERE id = v_entry_guide_id;

                    END LOOP guide_loop;

                    CLOSE cur_entry_guides;
                END;

            END LOOP detail_loop;

            CLOSE cur_purchase_details;
        END;

    END LOOP purchase_loop;

    CLOSE cur_purchases;

END$$

DELIMITER ;
