<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'role.view',
            'role.update',
            'category.view',
            'category.create',
            'category.store',
            'category.edit',
            'category.update',
            'category.delete',
            'supplier.view',
            'supplier.create',
            'supplier.store',
            'supplier.edit',
            'supplier.update',
            'supplier.delete',
            'customer.view',
            'customer.create',
            'customer.store',
            'customer.edit',
            'customer.update',
            'customer.delete',
            'unit.view',
            'unit.create',
            'unit.store',
            'unit.edit',
            'unit.update',
            'unit.delete',
            'product.view',
            'product.create',
            'product.store',
            'product.edit',
            'product.update',
            'product.delete',
            // Stock Management permissions
            'stock.view',
            'stock.in',
            'stock.out',
            'stock.adjustment',
            'stock.opname.view',
            'stock.opname.create',
            'stock.opname.update',
            'stock.opname.delete',
            'stock.opname.start',
            'stock.opname.complete',
            'user.view',
            'user.create',
            'user.store',
            'user.edit',
            'user.update',
            'user.delete',
            // Sales permissions
            'sales.view',
            'sales.create',
            'sales.process',
            'sales.history',
            'sales.cancel',
            // Purchase permissions
            'purchase.view',
            'purchase.create',
            'purchase.store',
            'purchase.show',
            'purchase.update',
            'purchase.delete',
            // Settings permissions
            'setting.view',
            'setting.update',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $adminRole = Role::where('name', 'admin')->first();

        if ($adminRole) {
            $adminRole->syncPermissions($permissions);
        }
    }
}
