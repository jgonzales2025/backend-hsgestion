<?php

namespace Database\Seeders;

use App\Modules\User\Infrastructure\Model\EloquentUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Definir SOLO los permisos (sin duplicar la lógica de menús)
        $permissions = [
            // ALMACÉN
            'almacen.guia_ingreso',
            'almacen.guia_remision',
            'almacen.registro_importaciones',
            'almacen.orden_ensamble',
            'almacen.anular_orden_ensamble',

            // CAJA
            'caja.cancelacion_facturas',
            'caja.parte_diario',
            'caja.registro_cobranzas',
            'caja.deposito_cheques',
            'caja.deposito_tarjetas',
            'caja.consulta_voucher',

            // ESTADÍSTICAS
            'estadisticas.ventas_vs_costo',
            'estadisticas.articulos_consumidos_clientes',
            'estadisticas.ventas_clientes_anual',
            'estadisticas.ventas_volumen_rebate',
            'estadisticas.ventas_descuento_costo_cero',
            'estadisticas.compras_linea_anual',
            'estadisticas.compras_proveedor_anual',
            'estadisticas.consulta_precios_compra',
            'estadisticas.diferencia_cambio',
            'estadisticas.ventas_por_producto',
            'estadisticas.ventas_por_vendedor',
            'estadisticas.linea_credito_clientes',

            // REPORTES
            'reportes.kardex_fisico_sin_precio',
            'reportes.kardex_valorizado_promedio',
            'reportes.kardex_fisico_negativo',
            'reportes.kardex_valorizado_precio_real',
            'reportes.inventario_vs_saldo_fecha',
            'reportes.toma_inventario',
            'reportes.compras_costo_cero',
            'reportes.lista_articulos_valorizado',
            'reportes.consulta_entradas_salidas',
            'reportes.consulta_registro_documentos',
            'reportes.registro_ventas',
            'reportes.lista_precios',
            'reportes.reporte_guias',
            'reportes.ventas_compras_item',
            'reportes.caja_diario_mensual',
            'reportes.consulta_documentos_venta',
            'reportes.consulta_cobranzas',
            'reportes.consulta_proformas',
            'reportes.informe_facturacion',
            'reportes.informe_ventas_vendedor',
            'reportes.documentos_pendientes_clientes',
            'reportes.consulta_sunat',
            'reportes.consulta_dni_reniec',
            'reportes.registro_compras',
            'reportes.reporte_series',

            // GARANTÍAS
            'garantias.recepcion_entrega_productos',
            'garantias.envios_a_proveedor',
            'garantias.nota_credito_devolucion',
            'garantias.nota_credito_descuento',
            'garantias.nota_debito',
            'garantias.guias_remision',
            'garantias.consultar_ventas',

            // TABLAS
            'tablas.articulos',
            'tablas.vendedores',
            'tablas.personal_almacen',
            'tablas.empresa_transporte',
            'tablas.conductor',
            'tablas.marcas',
            'tablas.categorias_articulos',
            'tablas.subcategorias_articulos',
            'tablas.cartera_clientes',
            'tablas.companias',
            'tablas.tipos_cambio',
            'tablas.bloquear_meses',
            'tablas.porcentajes_igv',
            'tablas.usuarios',
            'tablas.roles',

            // MANTENIMIENTO
            'mantenimiento.guias_ingreso_internas',
            'mantenimiento.facturas_boletas_notas',
            'mantenimiento.guias_remision',
            'mantenimiento.emitir_orden_salida_traslado',
            'mantenimiento.emitir_guia_orden_salida',
            'mantenimiento.mantenimiento_ordenes',
            'mantenimiento.anular_documentos_ventas',
            'mantenimiento.anular_guias_remision',
            'mantenimiento.anular_guias_ingreso',
            'mantenimiento.vista_principal_ventas',
            'mantenimiento.precios_articulos',
            'mantenimiento.guia_ingreso_contabilidad',

            // ACTUALIZACIONES
            'actualizaciones.valorizacion_general',
            'actualizaciones.actualizacion_ultimo_costo',
            'actualizaciones.actualizacion_saldos_clientes',
            'actualizaciones.exportar_precios_web',

            // SFS-SUNAT
            'sfs_sunat.documentos_pendientes_envio',
            'sfs_sunat.comunicacion_baja_documentos',
            'sfs_sunat.seguimiento_documentos',
        ];

        // Crear todos los permisos
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'api',
            ]);
        }

        // ===== CREAR ROLES =====

        // Administrador (acceso total)
        $admin = Role::firstOrCreate(
            ['name' => 'Administrador', 'guard_name' => 'api']
        );
        $admin->givePermissionTo(Permission::all());

        // Gerente
        $gerente = Role::firstOrCreate(
            ['name' => 'Gerente', 'guard_name' => 'api']
        );
        $gerente->givePermissionTo([
            'almacen.guia_ingreso',
            'almacen.guia_remision',
            'caja.cancelacion_facturas',
            'caja.parte_diario',
            'estadisticas.ventas_vs_costo',
            'estadisticas.ventas_clientes_anual',
            'reportes.kardex_valorizado_promedio',
            'reportes.registro_ventas',
            'reportes.informe_facturacion',
        ]);

        // Contador
        $contador = Role::firstOrCreate(
            ['name' => 'Contador', 'guard_name' => 'api']
        );
        $contador->givePermissionTo([
            'caja.parte_diario',
            'estadisticas.diferencia_cambio',
            'reportes.registro_ventas',
            'reportes.registro_compras',
            'sfs_sunat.documentos_pendientes_envio',
            'sfs_sunat.seguimiento_documentos',
        ]);

        // Cajero
        $cajero = Role::firstOrCreate(
            ['name' => 'Cajero', 'guard_name' => 'api']
        );
        $cajero->givePermissionTo([
            'caja.registro_cobranzas',
            'caja.deposito_cheques',
            'caja.deposito_tarjetas',
            'caja.consulta_voucher',
        ]);

        // Almacenero
        $almacenero = Role::firstOrCreate(
            ['name' => 'Almacenero', 'guard_name' => 'api']
        );
        $almacenero->givePermissionTo([
            'almacen.guia_ingreso',
            'almacen.guia_remision',
            'almacen.orden_ensamble',
            'reportes.toma_inventario',
            'reportes.lista_articulos_valorizado',
        ]);

        // ===== CREAR USUARIO ADMIN =====
        $user = EloquentUser::firstOrCreate(
            ['username' => 'admin'],
            [
                'firstname' => 'Admin',
                'lastname'  => 'Admin',
                'password'  => Hash::make('123456789'),
                'status'    => 1,
            ]
        );

        if (!$user->hasRole('Administrador')) {
            $user->assignRole('Administrador');
        }
    }
}
