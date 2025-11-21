<?php

namespace Database\Seeders;

use App\Modules\User\Infrastructure\Model\EloquentUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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

            // COMPRAS
            'compras.orden_compra_proveedores',
            'compras.listar_compras',
            'compras.registrar_compra',

            // CAJA
            'caja.caja_chica',
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
            'reportes.saldos_articulos',
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
            'tablas.unidad_medida',
            'tablas.usuarios',
            'tablas.roles',
            'tablas.bancos',
            'tablas.billetera_digital',
            'tablas.intentos_sesion',
            'tablas.notas_debito',
            'tablas.motivos_caja_chica',

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
            'mantenimiento.cotizacion',

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

        DB::table('menu_role')->insert([
            ['role_id' => $admin->id, 'menu_id' => 1],
            ['role_id' => $admin->id, 'menu_id' => 2],
            ['role_id' => $admin->id, 'menu_id' => 3],
            ['role_id' => $admin->id, 'menu_id' => 4],
            ['role_id' => $admin->id, 'menu_id' => 5],
            ['role_id' => $admin->id, 'menu_id' => 6],
            ['role_id' => $admin->id, 'menu_id' => 7],
            ['role_id' => $admin->id, 'menu_id' => 8],
            ['role_id' => $admin->id, 'menu_id' => 9],
            ['role_id' => $admin->id, 'menu_id' => 10],
            ['role_id' => $admin->id, 'menu_id' => 11],
            ['role_id' => $admin->id, 'menu_id' => 12],
            ['role_id' => $admin->id, 'menu_id' => 13],
            ['role_id' => $admin->id, 'menu_id' => 14],
            ['role_id' => $admin->id, 'menu_id' => 15],
            ['role_id' => $admin->id, 'menu_id' => 16],
            ['role_id' => $admin->id, 'menu_id' => 17],
            ['role_id' => $admin->id, 'menu_id' => 18],
            ['role_id' => $admin->id, 'menu_id' => 19],
            ['role_id' => $admin->id, 'menu_id' => 20],
            ['role_id' => $admin->id, 'menu_id' => 21],
            ['role_id' => $admin->id, 'menu_id' => 22],
            ['role_id' => $admin->id, 'menu_id' => 23],
            ['role_id' => $admin->id, 'menu_id' => 24],
            ['role_id' => $admin->id, 'menu_id' => 25],
            ['role_id' => $admin->id, 'menu_id' => 26],
            ['role_id' => $admin->id, 'menu_id' => 27],
            ['role_id' => $admin->id, 'menu_id' => 28],
            ['role_id' => $admin->id, 'menu_id' => 29],
            ['role_id' => $admin->id, 'menu_id' => 30],
            ['role_id' => $admin->id, 'menu_id' => 31],
            ['role_id' => $admin->id, 'menu_id' => 32],
            ['role_id' => $admin->id, 'menu_id' => 33],
            ['role_id' => $admin->id, 'menu_id' => 34],
            ['role_id' => $admin->id, 'menu_id' => 35],
            ['role_id' => $admin->id, 'menu_id' => 36],
            ['role_id' => $admin->id, 'menu_id' => 37],
            ['role_id' => $admin->id, 'menu_id' => 38],
            ['role_id' => $admin->id, 'menu_id' => 39],
            ['role_id' => $admin->id, 'menu_id' => 40],
            ['role_id' => $admin->id, 'menu_id' => 41],
            ['role_id' => $admin->id, 'menu_id' => 42],
            ['role_id' => $admin->id, 'menu_id' => 43],
            ['role_id' => $admin->id, 'menu_id' => 44],
            ['role_id' => $admin->id, 'menu_id' => 45],
            ['role_id' => $admin->id, 'menu_id' => 46],
            ['role_id' => $admin->id, 'menu_id' => 47],
            ['role_id' => $admin->id, 'menu_id' => 48],
            ['role_id' => $admin->id, 'menu_id' => 49],
            ['role_id' => $admin->id, 'menu_id' => 50],
            ['role_id' => $admin->id, 'menu_id' => 51],
            ['role_id' => $admin->id, 'menu_id' => 52],
            ['role_id' => $admin->id, 'menu_id' => 53],
            ['role_id' => $admin->id, 'menu_id' => 54],
            ['role_id' => $admin->id, 'menu_id' => 55],
            ['role_id' => $admin->id, 'menu_id' => 56],
            ['role_id' => $admin->id, 'menu_id' => 57],
            ['role_id' => $admin->id, 'menu_id' => 58],
            ['role_id' => $admin->id, 'menu_id' => 59],
            ['role_id' => $admin->id, 'menu_id' => 60],
            ['role_id' => $admin->id, 'menu_id' => 61],
            ['role_id' => $admin->id, 'menu_id' => 62],
            ['role_id' => $admin->id, 'menu_id' => 63],
            ['role_id' => $admin->id, 'menu_id' => 64],
            ['role_id' => $admin->id, 'menu_id' => 65],
            ['role_id' => $admin->id, 'menu_id' => 66],
            ['role_id' => $admin->id, 'menu_id' => 67],
            ['role_id' => $admin->id, 'menu_id' => 68],
            ['role_id' => $admin->id, 'menu_id' => 69],
            ['role_id' => $admin->id, 'menu_id' => 70],
            ['role_id' => $admin->id, 'menu_id' => 71],
            ['role_id' => $admin->id, 'menu_id' => 72],
            ['role_id' => $admin->id, 'menu_id' => 73],
            ['role_id' => $admin->id, 'menu_id' => 74],
            ['role_id' => $admin->id, 'menu_id' => 75],
            ['role_id' => $admin->id, 'menu_id' => 76],
            ['role_id' => $admin->id, 'menu_id' => 77],
            ['role_id' => $admin->id, 'menu_id' => 78],
            ['role_id' => $admin->id, 'menu_id' => 79],
            ['role_id' => $admin->id, 'menu_id' => 80],
            ['role_id' => $admin->id, 'menu_id' => 81],
            ['role_id' => $admin->id, 'menu_id' => 82],
            ['role_id' => $admin->id, 'menu_id' => 83],
            ['role_id' => $admin->id, 'menu_id' => 84],
            ['role_id' => $admin->id, 'menu_id' => 85],
            ['role_id' => $admin->id, 'menu_id' => 86],
            ['role_id' => $admin->id, 'menu_id' => 87],
            ['role_id' => $admin->id, 'menu_id' => 88],
            ['role_id' => $admin->id, 'menu_id' => 89],
            ['role_id' => $admin->id, 'menu_id' => 90],
            ['role_id' => $admin->id, 'menu_id' => 91],
            ['role_id' => $admin->id, 'menu_id' => 92],
            ['role_id' => $admin->id, 'menu_id' => 93],
            ['role_id' => $admin->id, 'menu_id' => 94],
            ['role_id' => $admin->id, 'menu_id' => 95],
            ['role_id' => $admin->id, 'menu_id' => 96],
            ['role_id' => $admin->id, 'menu_id' => 97],
            ['role_id' => $admin->id, 'menu_id' => 98],
            ['role_id' => $admin->id, 'menu_id' => 99],
            ['role_id' => $admin->id, 'menu_id' => 100],
            ['role_id' => $admin->id, 'menu_id' => 101],
            ['role_id' => $admin->id, 'menu_id' => 102],
            ['role_id' => $admin->id, 'menu_id' => 103],
            ['role_id' => $admin->id, 'menu_id' => 104],
            ['role_id' => $admin->id, 'menu_id' => 105],
            ['role_id' => $admin->id, 'menu_id' => 106],
            ['role_id' => $admin->id, 'menu_id' => 107],
            ['role_id' => $admin->id, 'menu_id' => 108],
            ['role_id' => $admin->id, 'menu_id' => 109],
        ]);

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

        DB::table('menu_role')->insert([
            ['role_id' => $gerente->id, 'menu_id' => 1],
            ['role_id' => $gerente->id, 'menu_id' => 2],
            ['role_id' => $gerente->id, 'menu_id' => 3],
            ['role_id' => $gerente->id, 'menu_id' => 7],
            ['role_id' => $gerente->id, 'menu_id' => 8],
            ['role_id' => $gerente->id, 'menu_id' => 9],
            ['role_id' => $gerente->id, 'menu_id' => 14],
            ['role_id' => $gerente->id, 'menu_id' => 15],
            ['role_id' => $gerente->id, 'menu_id' => 17],
            ['role_id' => $gerente->id, 'menu_id' => 27],
            ['role_id' => $gerente->id, 'menu_id' => 29],
            ['role_id' => $gerente->id, 'menu_id' => 45],
            ['role_id' => $gerente->id, 'menu_id' => 46],
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

        DB::table('menu_role')->insert([
            ['role_id' => $contador->id, 'menu_id' => 7],
            ['role_id' => $contador->id, 'menu_id' => 9],
            ['role_id' => $contador->id, 'menu_id' => 14],
            ['role_id' => $contador->id, 'menu_id' => 23],
            ['role_id' => $contador->id, 'menu_id' => 27],
            ['role_id' => $contador->id, 'menu_id' => 39],
            ['role_id' => $contador->id, 'menu_id' => 52],
            ['role_id' => $contador->id, 'menu_id' => 96],
            ['role_id' => $contador->id, 'menu_id' => 97],
            ['role_id' => $contador->id, 'menu_id' => 99],
        ]);

        // Cajero
        $cajero = Role::firstOrCreate(
            ['name' => 'Cajero', 'guard_name' => 'api']
        );
        $cajero->givePermissionTo([
            'caja.cancelacion_facturas',
            'caja.parte_diario',
            'caja.registro_cobranzas',
            'caja.deposito_cheques',
            'caja.deposito_tarjetas',
            'caja.consulta_voucher',
        ]);

        DB::table('menu_role')->insert([
            ['role_id' => $cajero->id, 'menu_id' => 7],
            ['role_id' => $cajero->id, 'menu_id' => 8],
            ['role_id' => $cajero->id, 'menu_id' => 9],
            ['role_id' => $cajero->id, 'menu_id' => 10],
            ['role_id' => $cajero->id, 'menu_id' => 11],
            ['role_id' => $cajero->id, 'menu_id' => 12],
            ['role_id' => $cajero->id, 'menu_id' => 13],
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
            'tablas.unidad_medida'
        ]);

        DB::table('menu_role')->insert([
            ['role_id' => $almacenero->id, 'menu_id' => 1],
            ['role_id' => $almacenero->id, 'menu_id' => 2],
            ['role_id' => $almacenero->id, 'menu_id' => 3],
            ['role_id' => $almacenero->id, 'menu_id' => 5],
            ['role_id' => $almacenero->id, 'menu_id' => 27],
            ['role_id' => $almacenero->id, 'menu_id' => 33],
            ['role_id' => $almacenero->id, 'menu_id' => 36],
        ]);

        // Vendedor
        $vendedor = Role::firstOrCreate(
            ['name' => 'Vendedor', 'guard_name' => 'api']
        );
        $vendedor->givePermissionTo([
            'tablas.articulos',
            'caja.cancelacion_facturas',
            'caja.parte_diario',
            'caja.registro_cobranzas',
            'caja.deposito_cheques',
            'caja.deposito_tarjetas',
            'caja.consulta_voucher',
            'almacen.guia_ingreso',
            'almacen.guia_remision',
            'almacen.registro_importaciones',
            'almacen.orden_ensamble',
            'almacen.anular_orden_ensamble'
        ]);

        DB::table('menu_role')->insert([
           ['role_id' => $vendedor->id, 'menu_id' => 62],
            ['role_id' => $vendedor->id, 'menu_id' => 63],
            ['role_id' => $vendedor->id, 'menu_id' => 7],
            ['role_id' => $vendedor->id, 'menu_id' => 8],
            ['role_id' => $vendedor->id, 'menu_id' => 9],
            ['role_id' => $vendedor->id, 'menu_id' => 10],
            ['role_id' => $vendedor->id, 'menu_id' => 11],
            ['role_id' => $vendedor->id, 'menu_id' => 12],
            ['role_id' => $vendedor->id, 'menu_id' => 13],
            ['role_id' => $vendedor->id, 'menu_id' => 1],
            ['role_id' => $vendedor->id, 'menu_id' => 2],
            ['role_id' => $vendedor->id, 'menu_id' => 3],
            ['role_id' => $vendedor->id, 'menu_id' => 4],
            ['role_id' => $vendedor->id, 'menu_id' => 5],
            ['role_id' => $vendedor->id, 'menu_id' => 6],
        ]);

        // ===== CREAR USUARIO ADMIN =====
        $user = EloquentUser::firstOrCreate(
            ['username' => 'admin'],
            [
                'firstname' => 'Admin',
                'lastname'  => 'Admin',
                'password'  => Hash::make('123456789'),
                'status'    => 1,
                'password_item' => Hash::make('123456789'),
            ]
        );

        if (!$user->hasRole('Administrador')) {
            $user->assignRole('Administrador');
        }

        $user->assignments()->createMany([
            ['company_id' => 1, 'branch_id' => 1, 'status' => 1],
            ['company_id' => 1, 'branch_id' => 2, 'status' => 1],
            ['company_id' => 2, 'branch_id' => 1, 'status' => 1],
            ['company_id' => 2, 'branch_id' => 2, 'status' => 1],
        ]);
    }
}
