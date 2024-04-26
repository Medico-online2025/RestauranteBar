<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role1      = Role::create(['name'  => 'SUPERADMIN']);
        $role2      = Role::create(['name'  => 'ADMIN']);
        $role3      = Role::create(['name'  => 'VENDEDOR']);
        $role4      = Role::create(['name'  => 'MESERO']);

        Permission::create([
            'name'          => 'admin.business', 
            'descripcion'   => 'Ver información de empresa'
        ])->syncRoles([$role1]);

        Permission::create([
            'name'          => 'admin.buys', 
            'descripcion'   => 'Ver compras'
        ])->syncRoles([$role1, $role2]);

        Permission::create([
            'name'          => 'admin.create_buy', 
            'descripcion'   => 'Registrar nueva compra'
        ])->syncRoles([$role1, $role2]);

        Permission::create([
            'name'          => 'admin.bills', 
            'descripcion'   => 'Ver gastos'
        ])->syncRoles([$role1, $role2]);

        Permission::create([
            'name'          => 'admin.products', 
            'descripcion'   => 'Ver productos'
        ])->syncRoles([$role1, $role2]);

        Permission::create([
            'name'          => 'admin.users', 
            'descripcion'   => 'Ver usuarios'
        ])->syncRoles([$role1, $role2]);

        Permission::create([
            'name'          => 'admin.roles', 
            'descripcion'   => 'Ver roles'
        ])->syncRoles([$role1, $role2]);

        Permission::create([
            'name'          => 'admin.clients', 
            'descripcion'   => 'Ver clientes'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'admin.providers', 
            'descripcion'   => 'Ver proveedores'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'admin.prices', 
            'descripcion'   => 'Ver precios'
        ])->syncRoles([$role1, $role2]);

        Permission::create([
            'name'          => 'admin.faq', 
            'descripcion'   => 'Ver preguntas frecuentes'
        ])->syncRoles([$role1, $role2]);

        Permission::create([
            'name'          => 'admin.cashes', 
            'descripcion'   => 'Ver arqueo de cajas'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'admin.pos', 
            'descripcion'   => 'Ver terminal POS'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'admin.series', 
            'descripcion'   => 'Ver series'
        ])->syncRoles([$role1, $role2]);

        Permission::create([
            'name'          => 'admin.rooms', 
            'descripcion'   => 'Ver salas'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'admin.tables', 
            'descripcion'   => 'Ver mesas'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'admin.list_cashes', 
            'descripcion'   => 'Ver cajas'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'admin.alerts_stock', 
            'descripcion'   => 'Ver productos por agotar'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'admin.alerts_expiration', 
            'descripcion'   => 'Ver productos por vencer'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'admin.alerts_sale', 
            'descripcion'   => 'Ver pendientes SUNAT'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'admin.billings', 
            'descripcion'   => 'Ver ventas'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'admin.credit_notes', 
            'descripcion'   => 'Ver notas de credito'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'admin.sale_notes', 
            'descripcion'   => 'Ver notas de venta'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'admin.quotes', 
            'descripcion'   => 'Ver cotizaciones'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'admin.sales_general', 
            'descripcion'   => 'Ver reporte ventas general'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'admin.sales_seller', 
            'descripcion'   => 'Ver reporte de ventas por vendedor'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'admin.purchases_general', 
            'descripcion'   => 'Ver reporte de compras general'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'admin.purchases_provider', 
            'descripcion'   => 'Ver reporte de compras por proveedor'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'admin.purchases_expenses', 
            'descripcion'   => 'Ver reporte de gastos'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'admin.contact_customers', 
            'descripcion'   => 'Ver reporte de clientes'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'admin.contact_providers', 
            'descripcion'   => 'Ver reporte de proveedores'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'admin.inventory_products', 
            'descripcion'   => 'Ver reporte de productos'
        ])->syncRoles([$role1, $role2, $role3]);

        Permission::create([
            'name'          => 'admin.orders', 
            'descripcion'   => 'Ver ordenes'
        ])->syncRoles([$role1, $role2, $role3, $role4]);

        Permission::create([
            'name'          => 'admin.create_order', 
            'descripcion'   => 'Crear pedido'
        ])->syncRoles([$role1, $role2, $role3, $role4]);

        Permission::create([
            'name'          => 'admin.register_order', 
            'descripcion'   => 'Registrar pedido'
        ])->syncRoles([$role1, $role2, $role3, $role4]);
    }
}
