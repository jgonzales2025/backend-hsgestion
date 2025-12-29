-- =====================================================================
-- Script para RESETEAR TODO y empezar limpio
-- =====================================================================
-- Ejecuta esto ANTES de crear nuevas compras para limpiar el desastre
-- =====================================================================

USE `backend_hsgestion`;

-- Paso 1: Resetear TODAS las cantidad_update a 0
UPDATE detail_purchase_guides 
SET cantidad_update = 0;

-- Paso 2: Restaurar TODOS los saldos de entry_guide_article a su cantidad original
UPDATE entry_guide_article 
SET saldo = quantity;

-- Paso 3: Verificar el resultado
SELECT 'VERIFICACIÓN DE RESET' as titulo;

SELECT 
    'detail_purchase_guides - Todos deben tener cantidad_update = 0' as verificacion,
    COUNT(*) as total_registros,
    SUM(CASE WHEN cantidad_update = 0 THEN 1 ELSE 0 END) as con_cantidad_update_cero,
    SUM(CASE WHEN cantidad_update > 0 THEN 1 ELSE 0 END) as con_cantidad_update_mayor_cero
FROM detail_purchase_guides;

SELECT 
    'entry_guide_article - Todos deben tener saldo = quantity' as verificacion,
    COUNT(*) as total_registros,
    SUM(CASE WHEN saldo = quantity THEN 1 ELSE 0 END) as saldo_igual_quantity,
    SUM(CASE WHEN saldo != quantity THEN 1 ELSE 0 END) as saldo_diferente_quantity
FROM entry_guide_article;

-- Paso 4: Mostrar algunos ejemplos para verificación manual
SELECT 
    'Ejemplos de detail_purchase_guides después del reset' as titulo,
    id,
    purchase_id,
    article_id,
    cantidad,
    cantidad_update,
    (cantidad - cantidad_update) as disponible
FROM detail_purchase_guides
LIMIT 10;

SELECT 
    'Ejemplos de entry_guide_article después del  reset' as titulo,
    ega.id,
    ega.entry_guide_id,
    eg.serie,
    eg.correlativo,
    ega.article_id,
    ega.quantity,
    ega.saldo
FROM entry_guide_article ega
JOIN entry_guides eg ON eg.id = ega.entry_guide_id
LIMIT 10;

SELECT '✅ RESET COMPLETADO - Ya puedes crear nuevas compras' as resultado;
