<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ===== ALMACÉN =====
        $almacen = Menu::create([
            'name' => 'almacen',
            'label' => 'Almacén',
            'icon' => 'Warehouse',
            'route' => '/almacen',
            'permission' => null, // Menú padre sin permiso
            'parent_id' => null,
            'order' => 1,
            'status' => 1,
            'type' => 'group'
        ]);

        Menu::create([
            'name' => 'almacen_guia_ingreso',
            'label' => 'Guía de Ingreso (compras)',
            'route' => '/almacen/guia-ingreso',
            'permission' => 'almacen.guia_ingreso',
            'parent_id' => $almacen->id,
            'order' => 1,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'almacen_guia_remision',
            'label' => 'Guía de Remisión',
            'route' => '/almacen/guia-remision',
            'permission' => 'almacen.guia_remision',
            'parent_id' => $almacen->id,
            'order' => 2,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'almacen_registro_importaciones',
            'label' => 'Registro de Importaciones',
            'route' => '/almacen/registro-importaciones',
            'permission' => 'almacen.registro_importaciones',
            'parent_id' => $almacen->id,
            'order' => 3,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'almacen_orden_ensamble',
            'label' => 'Orden de Ensamble',
            'route' => '/almacen/orden-ensamble',
            'permission' => 'almacen.orden_ensamble',
            'parent_id' => $almacen->id,
            'order' => 4,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'almacen_anular_orden_ensamble',
            'label' => 'Anular Orden de Ensamble',
            'route' => '/almacen/anular-orden-ensamble',
            'permission' => 'almacen.anular_orden_ensamble',
            'parent_id' => $almacen->id,
            'order' => 5,
            'status' => 1,
            'type' => 'item',
        ]);

        // ===== COMPRAS ====
        $purchase = Menu::create([
            'name' => 'compras',
            'label' => 'Compras',
            'icon' => 'ShoppingCart',
            'route' => '/compras',
            'permission' => null,
            'parent_id' => null,
            'order' => 2,
            'status' => 1,
            'type' => 'group'
        ]);

        Menu::create([
            'name' => 'orden_compras_proveedores',
            'label' => 'Listar Ordenes de Compra',
            'route' => '/compras/orden-compras-proveedores',
            'permission' => 'compras.orden_compras_proveedores',
            'parent_id' => $purchase->id,
            'order' => 1,
            'status' => 1,
            'type' => 'item',
        ]);

        // ===== CAJA =====
        $caja = Menu::create([
            'name' => 'caja',
            'label' => 'Caja',
            'icon' => 'Banknote',
            'route' => '/caja',
            'permission' => null,
            'parent_id' => null,
            'order' => 3,
            'status' => 1,
            'type' => 'group'
        ]);

        Menu::create([
            'name' => 'caja_caja_chica',
            'label' => 'Caja Chica',
            'route' => '/caja/caja-chica',
            'permission' => 'caja.caja_chica',
            'parent_id' => $caja->id,
            'order' => 1,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'caja_cancelacion_facturas',
            'label' => 'Cancelación de Facturas',
            'route' => '/caja/cancelacion-facturas',
            'permission' => 'caja.cancelacion_facturas',
            'parent_id' => $caja->id,
            'order' => 2,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'caja_parte_diario',
            'label' => 'Parte Diario de Caja',
            'route' => '/caja/parte-diario',
            'permission' => 'caja.parte_diario',
            'parent_id' => $caja->id,
            'order' => 3,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'caja_registro_cobranzas',
            'label' => 'Registro de Cobranzas Varias',
            'route' => '/caja/registro-cobranzas',
            'permission' => 'caja.registro_cobranzas',
            'parent_id' => $caja->id,
            'order' => 4,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'caja_deposito_cheques',
            'label' => 'Depósito de Cheques',
            'route' => '/caja/deposito-cheques',
            'permission' => 'caja.deposito_cheques',
            'parent_id' => $caja->id,
            'order' => 5,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'caja_deposito_tarjetas',
            'label' => 'Depósito de Tarjetas',
            'route' => '/caja/deposito-tarjetas',
            'permission' => 'caja.deposito_tarjetas',
            'parent_id' => $caja->id,
            'order' => 6,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'caja_consulta_voucher',
            'label' => 'Consulta Voucher',
            'route' => '/caja/consulta-voucher',
            'permission' => 'caja.consulta_voucher',
            'parent_id' => $caja->id,
            'order' => 7,
            'status' => 1,
            'type' => 'item',
        ]);

        // ===== ESTADÍSTICAS =====
        $estadistica = Menu::create([
            'name' => 'estadisticas',
            'label' => 'Estadísticas',
            'icon' => 'BarChart3',
            'route' => '/estadisticas',
            'permission' => null,
            'parent_id' => null,
            'order' => 4,
            'status' => 1,
            'type' => 'group'
        ]);

        Menu::create([
            'name' => 'estadisticas_ventas_vs_costo',
            'label' => 'Cuadro de Ventas vs. Costo',
            'route' => '/estadisticas/ventas-vs-costo',
            'permission' => 'estadisticas.ventas_vs_costo',
            'parent_id' => $estadistica->id,
            'order' => 1,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'estadisticas_articulos_consumidos',
            'label' => 'Detalle de Artículos Consumidos x Clientes',
            'route' => '/estadisticas/articulos-consumidos-clientes',
            'permission' => 'estadisticas.articulos_consumidos_clientes',
            'parent_id' => $estadistica->id,
            'order' => 2,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'estadisticas_ventas_clientes_anual',
            'label' => 'Ventas a Clientes x Año',
            'route' => '/estadisticas/ventas-clientes-anual',
            'permission' => 'estadisticas.ventas_clientes_anual',
            'parent_id' => $estadistica->id,
            'order' => 3,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'estadisticas_ventas_volumen_rebate',
            'label' => 'Ventas de Artículos x Volumen (Rebate)',
            'route' => '/estadisticas/ventas-volumen-rebate',
            'permission' => 'estadisticas.ventas_volumen_rebate',
            'parent_id' => $estadistica->id,
            'order' => 4,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'estadisticas_ventas_descuento_costo_cero',
            'label' => 'Ventas de Artículos con Descuento - Costo Cero',
            'route' => '/estadisticas/ventas-descuento-costo-cero',
            'permission' => 'estadisticas.ventas_descuento_costo_cero',
            'parent_id' => $estadistica->id,
            'order' => 5,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'estadisticas_compras_linea_anual',
            'label' => 'Compras x Línea de Artículos Anual Cant/US$',
            'route' => '/estadisticas/compras-linea-anual',
            'permission' => 'estadisticas.compras_linea_anual',
            'parent_id' => $estadistica->id,
            'order' => 6,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'estadisticas_compras_proveedor_anual',
            'label' => 'Compras x Proveedor Anual Cant/US$',
            'route' => '/estadisticas/compras-proveedor-anual',
            'permission' => 'estadisticas.compras_proveedor_anual',
            'parent_id' => $estadistica->id,
            'order' => 7,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'estadisticas_precios_compra_articulo',
            'label' => 'Consulta de Precios de Compra x Artículo',
            'route' => '/estadisticas/precios-compra-articulo',
            'permission' => 'estadisticas.consulta_precios_compra',
            'parent_id' => $estadistica->id,
            'order' => 8,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'estadisticas_diferencia_cambio',
            'label' => 'Diferencia de Cambio',
            'route' => '/estadisticas/diferencia-cambio',
            'permission' => 'estadisticas.diferencia_cambio',
            'parent_id' => $estadistica->id,
            'order' => 9,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'estadisticas_ventas_productos',
            'label' => 'Cuadro de Ventas x Productos',
            'route' => '/estadisticas/ventas-productos',
            'permission' => 'estadisticas.ventas_por_producto',
            'parent_id' => $estadistica->id,
            'order' => 10,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'estadisticas_ventas_por_vendedor',
            'label' => 'Ventas de Artículos x Vendedor',
            'route' => '/estadisticas/ventas-vendedor',
            'permission' => 'estadisticas.ventas_por_vendedor',
            'parent_id' => $estadistica->id,
            'order' => 11,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'estadisticas_linea_credito_clientes',
            'label' => 'Línea de Crédito de Clientes',
            'route' => '/estadisticas/linea-credito-clientes',
            'permission' => 'estadisticas.linea_credito_clientes',
            'parent_id' => $estadistica->id,
            'order' => 12,
            'status' => 1,
            'type' => 'item',
        ]);

        // ===== REPORTES =====
        $reporte = Menu::create([
            'name' => 'reportes',
            'label' => 'Reportes',
            'icon' => 'FileBarChart',
            'route' => '/reportes',
            'permission' => null,
            'parent_id' => null,
            'order' => 5,
            'status' => 1,
            'type' => 'group'
        ]);

        Menu::create([
            'name' => 'reportes_kardex_fisico_sin_precio',
            'label' => 'Kardex Físico x Artículo sin Precio',
            'route' => '/reportes/kardex-fisico-sin-precio',
            'permission' => 'reportes.kardex_fisico_sin_precio',
            'parent_id' => $reporte->id,
            'order' => 1,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'reportes_kardex_valorizado',
            'label' => 'Kardex Valorizado (Costo Promedio)',
            'route' => '/reportes/kardex-valorizado',
            'permission' => 'reportes.kardex_valorizado_promedio',
            'parent_id' => $reporte->id,
            'order' => 2,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'reportes_kardex_fisico_negativo',
            'label' => 'Kardex Físico x Artículo (Negativo)',
            'route' => '/reportes/kardex-fisico-negativo',
            'permission' => 'reportes.kardex_fisico_negativo',
            'parent_id' => $reporte->id,
            'order' => 3,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'reportes_kardex_precio_real',
            'label' => 'Kardex Artículo con Precio Real (Compra/Venta)',
            'route' => '/reportes/kardex-precio-real',
            'permission' => 'reportes.kardex_valorizado_precio_real',
            'parent_id' => $reporte->id,
            'order' => 4,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'reportes_inventario_vs_saldo_fecha',
            'label' => 'Reporte de Inventario Vs. Saldo & Fecha',
            'route' => '/reportes/inventario-vs-saldo-fecha',
            'permission' => 'reportes.inventario_vs_saldo_fecha',
            'parent_id' => $reporte->id,
            'order' => 5,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'reportes_toma_inventario',
            'label' => 'Reporte para Toma de Inventario',
            'route' => '/reportes/toma-inventario',
            'permission' => 'reportes.toma_inventario',
            'parent_id' => $reporte->id,
            'order' => 6,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'reportes_compras_costo_cero',
            'label' => 'Reporte de Compras con Costo 0',
            'route' => '/reportes/compras-costo-cero',
            'permission' => 'reportes.compras_costo_cero',
            'parent_id' => $reporte->id,
            'order' => 7,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'reportes_saldos_articulos',
            'label' => 'Reporte de Saldos por Artículos',
            'route' => '/reportes/saldos-articulos',
            'permission' => 'reportes.saldos_articulos',
            'parent_id' => $reporte->id,
            'order' => 8,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'reportes_lista_articulos_valorizado',
            'label' => 'Lista de artículos con saldo valorizado',
            'route' => '/reportes/lista-articulos-valorizado',
            'permission' => 'reportes.lista_articulos_valorizado',
            'parent_id' => $reporte->id,
            'order' => 9,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'reportes_entrada_salida_articulo',
            'label' => 'Consulta Entrada / Salidas x Artículo',
            'route' => '/reportes/entrada-salida-articulo',
            'permission' => 'reportes.consulta_entradas_salidas',
            'parent_id' => $reporte->id,
            'order' => 10,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'reportes_registro_documentos',
            'label' => 'Consulta de Registro de Documentos',
            'route' => '/reportes/registro-documentos',
            'permission' => 'reportes.consulta_registro_documentos',
            'parent_id' => $reporte->id,
            'order' => 11,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'reportes_registro_ventas',
            'label' => 'Registro de Ventas',
            'route' => '/reportes/registro-ventas',
            'permission' => 'reportes.registro_ventas',
            'parent_id' => $reporte->id,
            'order' => 12,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'reportes_lista_precios',
            'label' => 'Lista de Precios',
            'route' => '/reportes/lista-precios',
            'permission' => 'reportes.lista_precios',
            'parent_id' => $reporte->id,
            'order' => 13,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'reportes_guias',
            'label' => 'Reporte de Guías',
            'route' => '/reportes/guias',
            'permission' => 'reportes.reporte_guias',
            'parent_id' => $reporte->id,
            'order' => 14,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'reportes_ventas_compras_item',
            'label' => 'Ventas/Compras x Item',
            'route' => '/reportes/ventas-compras-item',
            'permission' => 'reportes.ventas_compras_item',
            'parent_id' => $reporte->id,
            'order' => 15,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'reportes_caja_diario_mensual',
            'label' => 'Reporte de Caja Diario Mensual',
            'route' => '/reportes/caja-diario-mensual',
            'permission' => 'reportes.caja_diario_mensual',
            'parent_id' => $reporte->id,
            'order' => 16,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'reportes_documentos_venta',
            'label' => 'Consulta de Documentos de Venta',
            'route' => '/reportes/documentos-venta',
            'permission' => 'reportes.consulta_documentos_venta',
            'parent_id' => $reporte->id,
            'order' => 17,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'reportes_consulta_cobranzas',
            'label' => 'Consulta de Cobranzas',
            'route' => '/reportes/consulta-cobranzas',
            'permission' => 'reportes.consulta_cobranzas',
            'parent_id' => $reporte->id,
            'order' => 18,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'reportes_consulta_proformas',
            'label' => 'Consulta de Proformas',
            'route' => '/reportes/consulta-proformas',
            'permission' => 'reportes.consulta_proformas',
            'parent_id' => $reporte->id,
            'order' => 19,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'reportes_informe_facturacion',
            'label' => 'Informe de Facturación',
            'route' => '/reportes/informe-facturacion',
            'permission' => 'reportes.informe_facturacion',
            'parent_id' => $reporte->id,
            'order' => 20,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'reportes_indice_clientes_vendedor',
            'label' => 'Índice Clientes x Vendedor',
            'route' => '/reportes/indice-clientes-vendedor',
            'permission' => 'reportes.informe_ventas_vendedor',
            'parent_id' => $reporte->id,
            'order' => 21,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'reportes_pendientes_clientes',
            'label' => 'Consulta Pendientes x Clientes',
            'route' => '/reportes/pendientes-clientes',
            'permission' => 'reportes.documentos_pendientes_clientes',
            'parent_id' => $reporte->id,
            'order' => 22,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'reportes_consulta_sunat',
            'label' => 'Consulta Sunat',
            'route' => '/reportes/consulta-en-linea',
            'permission' => 'reportes.consulta_sunat',
            'parent_id' => $reporte->id,
            'order' => 23,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'reportes_consulta_dni_reniec',
            'label' => 'Consulta DNI Reniec',
            'route' => '/reportes/consulta-dni-reniec',
            'permission' => 'reportes.consulta_dni_reniec',
            'parent_id' => $reporte->id,
            'order' => 24,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'reportes_registro_compras',
            'label' => 'Registro de Compras',
            'route' => '/reportes/registro-compras',
            'permission' => 'reportes.registro_compras',
            'parent_id' => $reporte->id,
            'order' => 25,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'reportes_series_clientes',
            'label' => 'Reporte de Series',
            'route' => '/reportes/series-clientes',
            'permission' => 'reportes.reporte_series',
            'parent_id' => $reporte->id,
            'order' => 26,
            'status' => 1,
            'type' => 'item',
        ]);

        // ===== GARANTÍAS =====
        $garantia = Menu::create([
            'name' => 'garantias',
            'label' => 'Garantías',
            'icon' => 'ShieldBan',
            'route' => '/garantias',
            'permission' => null,
            'parent_id' => null,
            'order' => 6,
            'status' => 1,
            'type' => 'group'
        ]);

        Menu::create([
            'name' => 'garantias_recepcion_entrega',
            'label' => 'Recepción/Entrega de productos',
            'route' => '/garantias/recepcion-entrega',
            'permission' => 'garantias.recepcion_entrega_productos',
            'parent_id' => $garantia->id,
            'order' => 1,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'garantias_envios_proveedor',
            'label' => 'Envios de Garantías a Proveedor',
            'route' => '/garantias/envios-proveedor',
            'permission' => 'garantias.envios_a_proveedor',
            'parent_id' => $garantia->id,
            'order' => 2,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'garantias_nota_credito_devolucion',
            'label' => 'Nota de Credito x Devolución',
            'route' => '/garantias/nota-credito-devolucion',
            'permission' => 'garantias.nota_credito_devolucion',
            'parent_id' => $garantia->id,
            'order' => 3,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'garantias_nota_credito_descuento',
            'label' => 'Nota de Credito x Descuento',
            'route' => '/garantias/nota-credito-descuento',
            'permission' => 'garantias.nota_credito_descuento',
            'parent_id' => $garantia->id,
            'order' => 4,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'garantias_nota_debito',
            'label' => 'Nota de Debito',
            'route' => '/garantias/nota-debito',
            'permission' => 'garantias.nota_debito',
            'parent_id' => $garantia->id,
            'order' => 5,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'garantias_guias_remision',
            'label' => 'Guías de Remisión x Garantía',
            'route' => '/garantias/guias-remision',
            'permission' => 'garantias.guias_remision',
            'parent_id' => $garantia->id,
            'order' => 6,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'garantias_consultar_ventas',
            'label' => 'Consultar Ventas',
            'route' => '/garantias/consultar-ventas',
            'permission' => 'garantias.consultar_ventas',
            'parent_id' => $garantia->id,
            'order' => 7,
            'status' => 1,
            'type' => 'item',
        ]);

        // ===== TABLAS =====
        $tabla = Menu::create([
            'name' => 'tablas',
            'label' => 'Tablas',
            'icon' => 'Grid2x2',
            'route' => '/tablas',
            'permission' => null,
            'parent_id' => null,
            'order' => 7,
            'status' => 1,
            'type' => 'group'
        ]);

        Menu::create([
            'name' => 'tablas_articulos',
            'label' => 'Artículos',
            'route' => '/tablas/articulos',
            'permission' => 'tablas.articulos',
            'parent_id' => $tabla->id,
            'order' => 1,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'tablas_empresa_transporte',
            'label' => 'Empresa Transporte',
            'route' => '/tablas/empresa-transporte',
            'permission' => 'tablas.empresa_transporte',
            'parent_id' => $tabla->id,
            'order' => 4,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'tablas_conductor',
            'label' => 'Conductor',
            'route' => '/tablas/conductor',
            'permission' => 'tablas.conductor',
            'parent_id' => $tabla->id,
            'order' => 5,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'tablas_marcas',
            'label' => 'Marcas',
            'route' => '/tablas/marcas',
            'permission' => 'tablas.marcas',
            'parent_id' => $tabla->id,
            'order' => 6,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'tablas_categorias_articulos',
            'label' => 'Categorías de Artículos',
            'route' => '/tablas/categorias-articulos',
            'permission' => 'tablas.categorias_articulos',
            'parent_id' => $tabla->id,
            'order' => 7,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'tablas_subcategorias_articulos',
            'label' => 'Sub Categoría de Artículos',
            'route' => '/tablas/subcategorias-articulos',
            'permission' => 'tablas.subcategorias_articulos',
            'parent_id' => $tabla->id,
            'order' => 8,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'tablas_cartera_clientes',
            'label' => 'Cartera de Clientes',
            'route' => '/tablas/cartera-clientes',
            'permission' => 'tablas.cartera_clientes',
            'parent_id' => $tabla->id,
            'order' => 9,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'tablas_companias',
            'label' => 'Mantenimiento de Compañías',
            'route' => '/tablas/companias',
            'permission' => 'tablas.companias',
            'parent_id' => $tabla->id,
            'order' => 10,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'tablas_tipos_cambio',
            'label' => 'Tipos de Cambio',
            'route' => '/tablas/tipos-cambio',
            'permission' => 'tablas.tipos_cambio',
            'parent_id' => $tabla->id,
            'order' => 11,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'tablas_bloquear_meses',
            'label' => 'Bloquear Meses',
            'route' => '/tablas/bloquear-meses',
            'permission' => 'tablas.bloquear_meses',
            'parent_id' => $tabla->id,
            'order' => 12,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'tablas_porcentajes_igv',
            'label' => 'Porcentajes IGV',
            'route' => '/tablas/porcentajes-igv',
            'permission' => 'tablas.porcentajes_igv',
            'parent_id' => $tabla->id,
            'order' => 13,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'tablas_unidad_medida',
            'label' => 'Unidades de medida',
            'route' => '/tablas/unidad-medida',
            'permission' => 'tablas.unidad_medida',
            'parent_id' => $tabla->id,
            'order' => 14,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'tablas_usuarios',
            'label' => 'Mantenimiento de Usuarios',
            'route' => '/tablas/usuarios',
            'permission' => 'tablas.usuarios',
            'parent_id' => $tabla->id,
            'order' => 15,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'tablas_roles',
            'label' => 'Mantenimiento de Roles',
            'route' => '/tablas/roles',
            'permission' => 'tablas.roles',
            'parent_id' => $tabla->id,
            'order' => 16,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'tablas_bancos',
            'label' => 'Mantenimiento de Bancos',
            'route' => '/tablas/bancos',
            'permission' => 'tablas.bancos',
            'parent_id' => $tabla->id,
            'order' => 17,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'tablas_billetera_digital',
            'label' => 'Mantenimiento de Billetera Digital',
            'route' => '/tablas/billetera-digital',
            'permission' => 'tablas.billetera_digital',
            'parent_id' => $tabla->id,
            'order' => 18,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'tablas_intentos_sesion',
            'label' => 'Intentos de sesión de usuarios',
            'route' => '/tablas/intentos-sesion',
            'permission' => 'tablas.intentos_sesion',
            'parent_id' => $tabla->id,
            'order' => 19,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'tablas_notas_debito',
            'label' => 'Concepto de Notas de Débito',
            'route' => '/tablas/notas-debito',
            'permission' => 'tablas.notas_debito',
            'parent_id' => $tabla->id,
            'order' => 20,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'tablas_motivos_caja_chica',
            'label' => 'Motivos de Caja Chica',
            'route' => '/tablas/motivos-caja-chica',
            'permission' => 'tablas.motivos_caja_chica',
            'parent_id' => $tabla->id,
            'order' => 21,
            'status' => 1,
            'type' => 'item',
        ]);

        // ===== MANTENIMIENTO =====
        $mantenimiento = Menu::create([
            'name' => 'mantenimiento',
            'label' => 'Mantenimiento',
            'icon' => 'Wrench',
            'route' => '/mantenimiento',
            'permission' => null,
            'parent_id' => null,
            'order' => 8,
            'status' => 1,
            'type' => 'group'
        ]);

        Menu::create([
            'name' => 'mantenimiento_guias_ingreso',
            'label' => 'Guías Internas de Ingreso',
            'route' => '/mantenimiento/guias-ingreso-internas',
            'permission' => 'mantenimiento.guias_ingreso_internas',
            'parent_id' => $mantenimiento->id,
            'order' => 1,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'mantenimiento_documentos_venta',
            'label' => 'Facturas/Boletas de Venta/Nota de Venta',
            'route' => '/mantenimiento/documentos-venta',
            'permission' => 'mantenimiento.facturas_boletas_notas',
            'parent_id' => $mantenimiento->id,
            'order' => 2,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'mantenimiento_guias_remision',
            'label' => 'Guías de Remisión',
            'route' => '/mantenimiento/guias-remision',
            'permission' => 'mantenimiento.guias_remision',
            'parent_id' => $mantenimiento->id,
            'order' => 3,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'mantenimiento_ordenes_salida',
            'label' => 'Emitir Ordenes de Salida x Traslado',
            'route' => '/mantenimiento/ordenes-salida-traslado',
            'permission' => 'mantenimiento.emitir_orden_salida_traslado',
            'parent_id' => $mantenimiento->id,
            'order' => 4,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'mantenimiento_guias_ordenes_salida',
            'label' => 'Emitir Guía de Ordenes de Salida x Traslado',
            'route' => '/mantenimiento/guias-ordenes-salida-traslado',
            'permission' => 'mantenimiento.emitir_guia_orden_salida',
            'parent_id' => $mantenimiento->id,
            'order' => 5,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'mantenimiento_ordenes_cons_tras',
            'label' => 'Mantenimiento de Ordenes (Consignación/Traslado)',
            'route' => '/mantenimiento/ordenes-consignacion-traslado',
            'permission' => 'mantenimiento.mantenimiento_ordenes',
            'parent_id' => $mantenimiento->id,
            'order' => 6,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'mantenimiento_anular_documentos_venta',
            'label' => 'Anular Documentos Fac / Bol / NV / NC / ND (Ventas)',
            'route' => '/mantenimiento/anular-documentos-venta',
            'permission' => 'mantenimiento.anular_documentos_ventas',
            'parent_id' => $mantenimiento->id,
            'order' => 7,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'mantenimiento_anular_guias_remision',
            'label' => 'Anular Guías de Remisión (Ventas)',
            'route' => '/mantenimiento/anular-guias-remision',
            'permission' => 'mantenimiento.anular_guias_remision',
            'parent_id' => $mantenimiento->id,
            'order' => 8,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'mantenimiento_anular_guias_ingreso',
            'label' => 'Anular Guías de Ingreso a Almacén (Compras)',
            'route' => '/mantenimiento/anular-guias-ingreso',
            'permission' => 'mantenimiento.anular_guias_ingreso',
            'parent_id' => $mantenimiento->id,
            'order' => 9,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'mantenimiento_vista_principal_ventas',
            'label' => 'Vista Principal Ventas',
            'route' => '/mantenimiento/ventas-principal',
            'permission' => 'mantenimiento.vista_principal_ventas',
            'parent_id' => $mantenimiento->id,
            'order' => 10,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'mantenimiento_precios_articulos',
            'label' => 'Mantenimiento de Precios de Artículos',
            'route' => '/mantenimiento/precios-articulos',
            'permission' => 'mantenimiento.precios_articulos',
            'parent_id' => $mantenimiento->id,
            'order' => 11,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'mantenimiento_guia_ingreso_contabilidad',
            'label' => 'Guía Interna Ingreso / Contabilidad',
            'route' => '/mantenimiento/guia-ingreso-contabilidad',
            'permission' => 'mantenimiento.guia_ingreso_contabilidad',
            'parent_id' => $mantenimiento->id,
            'order' => 12,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'mantenimiento_cotizacion',
            'label' => 'Cotización',
            'route' => '/mantenimiento/cotizacion',
            'permission' => 'mantenimiento.cotizacion',
            'parent_id' => $mantenimiento->id,
            'order' => 13,
            'status' => 1,
            'type' => 'item',
        ]);

        // ===== ACTUALIZACIONES =====
        $actualizacion = Menu::create([
            'name' => 'actualizaciones',
            'label' => 'Actualizaciones',
            'icon' => 'RefreshCcw',
            'route' => '/actualizaciones',
            'permission' => null,
            'parent_id' => null,
            'order' => 9,
            'status' => 1,
            'type' => 'group'
        ]);

        Menu::create([
            'name' => 'actualizaciones_valorizacion',
            'label' => 'Valorización General (Kardex Valorizado)',
            'route' => '/actualizaciones/valorizacion-general',
            'permission' => 'actualizaciones.valorizacion_general',
            'parent_id' => $actualizacion->id,
            'order' => 1,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'actualizaciones_ultimo_costo',
            'label' => 'Actualización Último Costo',
            'route' => '/actualizaciones/ultimo-costo',
            'permission' => 'actualizaciones.actualizacion_ultimo_costo',
            'parent_id' => $actualizacion->id,
            'order' => 2,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'actualizaciones_saldos_clientes',
            'label' => 'Actualización Saldos de Clientes',
            'route' => '/actualizaciones/saldos-clientes',
            'permission' => 'actualizaciones.actualizacion_saldos_clientes',
            'parent_id' => $actualizacion->id,
            'order' => 3,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'actualizaciones_exportar_precios_web',
            'label' => 'Exportar Precios Web',
            'route' => '/actualizaciones/exportar-precios-web',
            'permission' => 'actualizaciones.exportar_precios_web',
            'parent_id' => $actualizacion->id,
            'order' => 4,
            'status' => 1,
            'type' => 'item',
        ]);

        // ===== SFS-SUNAT =====
        $sfssunat = Menu::create([
            'name' => 'sfs_sunat',
            'label' => 'SFS-SUNAT',
            'icon' => 'Landmark',
            'route' => '/sfs-sunat',
            'permission' => null,
            'parent_id' => null,
            'order' => 10,
            'status' => 1,
            'type' => 'group'
        ]);

        Menu::create([
            'name' => 'sfs_sunat_documentos_pendientes',
            'label' => 'Documentos Pendientes de Envío a Sunat',
            'route' => '/sfs-sunat/documentos-pendientes',
            'permission' => 'sfs_sunat.documentos_pendientes_envio',
            'parent_id' => $sfssunat->id,
            'order' => 1,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'sfs_sunat_comunicacion_baja',
            'label' => 'Comunicación de Baja de Documentos',
            'route' => '/sfs-sunat/comunicacion-baja',
            'permission' => 'sfs_sunat.comunicacion_baja_documentos',
            'parent_id' => $sfssunat->id,
            'order' => 2,
            'status' => 1,
            'type' => 'item',
        ]);

        Menu::create([
            'name' => 'sfs_sunat_seguimiento',
            'label' => 'Seguimiento de Documentos SFS',
            'route' => '/sfs-sunat/seguimiento',
            'permission' => 'sfs_sunat.seguimiento_documentos',
            'parent_id' => $sfssunat->id,
            'order' => 3,
            'status' => 1,
            'type' => 'item',
        ]);
    }
}
