<?php

   namespace Database\Seeders;

   use Illuminate\Database\Seeder;
   use Spatie\Permission\Models\Permission;
   use Illuminate\Database\Console\Seeds\WithoutModelEvents;

   class PermissionsTableSeeder extends Seeder
   {
      /**
       * Run the database seeds.
       *
       * @return void
       */
      public function run(): void {
         //permission for units
         Permission::create(['name' => 'units.index', 'guard_name' => 'web']);
         Permission::create(['name' => 'units.create', 'guard_name' => 'web']);
         Permission::create(['name' => 'units.edit', 'guard_name' => 'web']);
         Permission::create(['name' => 'units.delete', 'guard_name' => 'web']);
         //permission for is admin
         Permission::create(['name' => 'admin', 'guard_name' => 'web']);
         //permission for roles
         Permission::create(['name' => 'roles.index', 'guard_name' => 'web']);
         Permission::create(['name' => 'roles.create', 'guard_name' => 'web']);
         Permission::create(['name' => 'roles.edit', 'guard_name' => 'web']);
         Permission::create(['name' => 'roles.delete', 'guard_name' => 'web']);
         //permission for permissions
         Permission::create(['name' => 'permissions.index', 'guard_name' => 'web']);
         Permission::create(['name' => 'permissions.create', 'guard_name' => 'web']);
         Permission::create(['name' => 'permissions.edit', 'guard_name' => 'web']);
         Permission::create(['name' => 'permissions.delete', 'guard_name' => 'web']);
         //permission for users
         Permission::create(['name' => 'users.index', 'guard_name' => 'web']);
         Permission::create(['name' => 'users.create', 'guard_name' => 'web']);
         Permission::create(['name' => 'users.edit', 'guard_name' => 'web']);
         Permission::create(['name' => 'users.delete', 'guard_name' => 'web']);
         //permission for customers
         Permission::create(['name' => 'customers.index', 'guard_name' => 'web']);
         Permission::create(['name' => 'customers.create', 'guard_name' => 'web']);
         Permission::create(['name' => 'customers.edit', 'guard_name' => 'web']);
         Permission::create(['name' => 'customers.delete', 'guard_name' => 'web']);
         Permission::create(['name' => 'customers.import', 'guard_name' => 'web']);
         Permission::create(['name' => 'customers.export', 'guard_name' => 'web']);
         //permission for outlets
         Permission::create(['name' => 'outlets.index', 'guard_name' => 'web']);
         Permission::create(['name' => 'outlets.create', 'guard_name' => 'web']);
         Permission::create(['name' => 'outlets.edit', 'guard_name' => 'web']);
         Permission::create(['name' => 'outlets.delete', 'guard_name' => 'web']);
         //permissions for category expenses
         Permission::create(['name' => 'category-packages.index', 'guard_name' => 'web']);
         Permission::create(['name' => 'category-packages.create', 'guard_name' => 'web']);
         Permission::create(['name' => 'category-packages.edit', 'guard_name' => 'web']);
         Permission::create(['name' => 'category-packages.delete', 'guard_name' => 'web']);
         //permission for packages
         Permission::create(['name' => 'packages.index', 'guard_name' => 'web']);
         Permission::create(['name' => 'packages.create', 'guard_name' => 'web']);
         Permission::create(['name' => 'packages.edit', 'guard_name' => 'web']);
         Permission::create(['name' => 'packages.delete', 'guard_name' => 'web']);
         //permission for transactions
         Permission::create(['name' => 'transactions.index', 'guard_name' => 'web']);
         Permission::create(['name' => 'transactions.create', 'guard_name' => 'web']);
         Permission::create(['name' => 'transactions.edit', 'guard_name' => 'web']);
         Permission::create(['name' => 'transactions.delete', 'guard_name' => 'web']);
         Permission::create(['name' => 'transactions.print', 'guard_name' => 'web']);

         //permission for status-transactions
         Permission::create(['name' => 'status-transactions.index', 'guard_name' => 'web']);
         Permission::create(['name' => 'status-transactions.create', 'guard_name' => 'web']);
         Permission::create(['name' => 'status-transactions.edit', 'guard_name' => 'web']);
         //permission for reports
         Permission::create(['name' => 'reports.cashflow', 'guard_name' => 'web']);
         //permissions for category expenses
         Permission::create(['name' => 'category-expenses.index', 'guard_name' => 'web']);
         Permission::create(['name' => 'category-expenses.create', 'guard_name' => 'web']);
         Permission::create(['name' => 'category-expenses.edit', 'guard_name' => 'web']);
         Permission::create(['name' => 'category-expenses.delete', 'guard_name' => 'web']);
         //permission for expenses
         Permission::create(['name' => 'expenses.index', 'guard_name' => 'web']);
         Permission::create(['name' => 'expenses.create', 'guard_name' => 'web']);
         Permission::create(['name' => 'expenses.edit', 'guard_name' => 'web']);
         Permission::create(['name' => 'expenses.delete', 'guard_name' => 'web']);
      }
   }
