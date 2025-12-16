<?php

   namespace Database\Seeders;

   use App\Models\User;
   use Illuminate\Database\Seeder;
   use Spatie\Permission\Models\Role;
   use Spatie\Permission\Models\Permission;
   use Illuminate\Database\Console\Seeds\WithoutModelEvents;

   class UserTableSeeder extends Seeder
   {
      /**
       * Run the database seeds.
       */
      public function run(): void {
         // =======================================
         // 1. CREATE ADMIN USER
         // =======================================
         $admin = User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@dev.com',
            'password' => bcrypt('password'),
         ]);
         // Get or create 'admin' role
         $adminRole = Role::where('name', 'admin')->first();
         // Assign all permissions to admin role
         $allPermissions = Permission::all();
         // Assign all permissions to admin role
         $adminRole->syncPermissions($allPermissions);
         // Assign admin role to admin user
         $admin->assignRole($adminRole);
         // =======================================
         // 2. CREATE CASHIER USER
         // =======================================
         $cashier = User::create([
            'name'     => 'Cashier',
            'email'    => 'cashier@gmail.com',
            'password' => bcrypt('password'),
         ]);
         // Get 'cashier' role
         $cashierRole = Role::where('name', 'cashier')->first();
         // Get permissions for cashier role
         $cashierPermissions = Permission::whereIn('name', [
            'units.index',
            'units.create',
            'units.edit',
            'expenses.index',
            'expenses.create',
            'expenses.edit',
            'packages.index',
            'packages.create',
            'packages.edit',
            'customers.index',
            'customers.create',
            'customers.edit',
            'transactions.index',
            'transactions.create',
            'transactions.edit',
         ])->get();
         // Assign only specific permissions to cashier role
         $cashierRole->syncPermissions($cashierPermissions);
         // Assign cashier role to cashier user
         $cashier->assignRole($cashierRole);
      }
   }
