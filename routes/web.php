<?php

   namespace App\Http\Controllers;

   use App\Http\Controllers\Account\UnitController;
   use Illuminate\Support\Facades\Route;

   /**
    * home
    */
   Route::get('/', function() {
      return view('auth.login');
   });

   //prefix route for account
   Route::prefix('account')->middleware(['auth'])->group(function() {

      //unit
      Route::resource('/units', UnitController::class, ['as' => 'account'])->except(['create', 'edit']);

      //dashboard
      Route::get('/dashboard', [Account\DashboardController::class, 'index'])->name('account.dashboard.index');

      //route resources for outlets
      Route::resource('/outlets', Account\OutletController::class, ['as' => 'account']);

      // =========================================================================
      // CUSTOMERS ROUTES
      // =========================================================================

      // 1. Export
      Route::get('customers/export', [Account\CustomerController::class, 'export'])
           ->name('account.customers.export');

      // 2. Import Page (GET) -> INI YANG ANDA KURANG
      Route::get('customers/manage-data', [Account\CustomerController::class, 'importExportView'])
           ->name('account.customers.import-view');

      // 3. Import Step 1: Upload & Preview (POST)
      Route::post('customers/import/preview', [Account\CustomerController::class, 'previewImport'])
           ->name('account.customers.import-preview');

      // 4. Import Step 2: Confirm & Process Queue (POST)
      Route::post('customers/import/process', [Account\CustomerController::class, 'processImport'])
           ->name('account.customers.process-import');

      // 5. Resource Standard
      Route::resource('/customers', Account\CustomerController::class, ['as' => 'account']);
      // =========================================================================

      // route resource untuk category expenses
      Route::resource('/category-expenses', Account\CategoryExpenseController::class, ['as' => 'account']);
      // route resource untuk expenses
      Route::resource('/expenses', Account\ExpenseController::class, ['as' => 'account']);
      // route resource untuk category packages
      Route::resource('/category-packages', Account\CategoryPackageController::class, ['as' => 'account']);
      // route resource untuk packages
      Route::resource('/packages', Account\PackageController::class, ['as' => 'account']);
      // route resource untuk permissions
      Route::resource('/permissions', Account\PermissionController::class, ['as' => 'account']);
      // route resource untuk roles
      Route::resource('/roles', Account\RoleController::class, ['as' => 'account']);
      // route resource untuk users
      Route::resource('/users', Account\UserController::class, ['as' => 'account']);

      //route get transaction print
      Route::get('/transactions/{transaction}/print', [Account\TransactionController::class, 'print'])
           ->name('account.transactions.print');
      // route resource untuk transactions
      Route::resource('/transactions', Account\TransactionController::class, ['as' => 'account']);

      // route resource untuk transactions- inline
      Route::get('/status-transactions', [Account\StatusTransactionsController::class, 'index'])
           ->name('account.status-transactions.index');
      Route::put('/status-transactions/{transaction}', [Account\StatusTransactionsController::class, 'update'])
           ->name('account.status-transactions.update');

      //route get reports
      Route::get('/reports', [Account\ReportController::class, 'index'])->name('account.reports.index');
      //route get reports export
      Route::get('/reports/export', [Account\ReportController::class, 'export'])->name('account.reports.export');
   });