<div x-data="{
        mobileMasterOpen: false,
        mobileReportsOpen: false,
        mobileUserManagementOpen: false
    }">
   <!-- Mobile Menu Overlay -->
   <div x-show="$store.mobileMenuOpen" x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-black/50 lg:hidden"
        @click="$store.mobileMenuOpen = false"></div>

   <!-- Mobile Drawer Menu -->
   <div x-show="$store.mobileMenuOpen" x-transition:enter="transition ease-in-out duration-300 transform"
        x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-xl lg:hidden overflow-y-auto">
      <!-- Mobile Menu Header -->
      <div class="flex items-center justify-between px-4 py-3 shadow">
         <div class="flex items-center">
            <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
               <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
               </svg>
            </div>
            <span class="ml-2 text-xl font-bold text-gray-900">MemberSidoel</span>
         </div>
         <button @click="$store.mobileMenuOpen = false"
                 class="p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
         </button>
      </div>

      <!-- Mobile Navigation Links -->
      <nav class="px-4 py-6 space-y-2">
         <a href="/account/dashboard" @click="$store.mobileMenuOpen = false"
            class="flex items-center px-3 py-2 {{ request()->is('account/dashboard*') ? 'text-primary bg-primary/10' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg font-medium">
            <x-icons.home width="20" height="20" class="mr-3"/>
            Dashboard
         </a>

         @if(auth()->user()->can('outlets.index') || auth()->user()->can('customers.index') || auth()->user()->can('category-expenses.index') || auth()->user()->can('expenses.index') || auth()->user()->can('category-packages.index') || auth()->user()->can('packages.index'))
            <!-- Mobile Master Dropdown -->
            <div>
               <button @click="mobileMasterOpen = !mobileMasterOpen"
                       class="w-full flex items-center justify-between px-3 py-2 {{ request()->is('account/outlets*') || request()->is('account/customers*') || request()->is('account/category-expenses*') || request()->is('account/expenses*') || request()->is('account/category-packages*') || request()->is('account/packages*') ? 'text-primary bg-primary/10' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg">
                  <div class="flex items-center">
                     <x-icons.category width="20" height="20" class="mr-3"/>
                     Master
                  </div>
                  <svg class="w-4 h-4 transition-transform" :class="mobileMasterOpen ? 'rotate-180' : ''" fill="none"
                       stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
               </button>
               <div x-show="mobileMasterOpen" x-transition class="ml-8 mt-2 space-y-1">
                  @can('units.index')
                     <a href="/account/units"
                        class="block px-3 py-2 text-sm {{ request()->is('account/units*') ? 'text-primary' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg">Units</a>
                  @endcan
                  @can('outlets.index')
                     <a href="/account/outlets"
                        class="block px-3 py-2 text-sm {{ request()->is('account/outlets*') ? 'text-primary' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg">Outlets</a>
                  @endcan

                  @can('customers.index')
                     <a href="/account/customers"
                        class="block px-3 py-2 text-sm {{ request()->is('account/customers*') ? 'text-primary' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg">Customers</a>
                  @endcan

                  @if(auth()->user()->can('category-expenses.index') || auth()->user()->can('expenses.index'))
                     <div class="border-t border-gray-100 my-1"></div>
                  @endif

                  @can('category-expenses.index')
                     <a href="/account/category-expenses"
                        class="block px-3 py-2 text-sm {{ request()->is('account/category-expenses*') ? 'text-primary' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg">Expense
                        Category</a>
                  @endcan

                  @can('expenses.index')
                     <a href="/account/expenses"
                        class="block px-3 py-2 text-sm {{ request()->is('account/expenses*') ? 'text-primary' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg">Expenses</a>
                  @endcan

                  @if(auth()->user()->can('category-packages.index') || auth()->user()->can('packages.index'))
                     <div class="border-t border-gray-100 my-1"></div>
                  @endif

                  @can('category-packages.index')
                     <a href="/account/category-packages"
                        class="block px-3 py-2 text-sm {{ request()->is('account/package-categories*') ? 'text-primary' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg">Package
                        Category</a>
                  @endcan

                  @can('packages.index')
                     <a href="/account/packages"
                        class="block px-3 py-2 text-sm {{ request()->is('account/packages*') ? 'text-primary' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg">Packages</a>
                  @endcan
               </div>
            </div>
         @endif

         @can('transactions.index')
            <a href="/account/transactions" @click="$store.mobileMenuOpen = false"
               class="flex items-center px-3 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg">
               <x-icons.cash-register width="20" height="20" class="mr-3"/>
               Transactions
            </a>
         @endcan

         @can('reports.cashflow')
            <!-- Mobile Reports Dropdown -->
            <div>
               <button @click="mobileReportsOpen = !mobileReportsOpen"
                       class="w-full flex items-center justify-between px-3 py-2 {{ request()->is('account/reports*') ? 'text-primary bg-primary/10' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg">
                  <div class="flex items-center">
                     <x-icons.chart-pie width="20" height="20" class="mr-3"/>
                     Reports
                  </div>
                  <svg class="w-4 h-4 transition-transform" :class="mobileReportsOpen ? 'rotate-180' : ''" fill="none"
                       stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
               </button>
               <div x-show="mobileReportsOpen" x-transition class="ml-8 mt-2 space-y-1">
                  <a href="/account/status-transactions"
                     class="block px-3 py-2 text-sm {{ request()->is('account/status-transactions.*') ? 'text-primary' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg">Transaction Status</a>
                  <a href="/account/reports"
                     class="block px-3 py-2 text-sm {{ request()->is('account/reports') ? 'text-primary' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg">Financial
                     Report</a>
               </div>
            </div>
         @endcan

         @if(auth()->user()->can('users.index') || auth()->user()->can('roles.index') || auth()->user()->can('permissions.index'))
            <!-- Mobile Users Management Dropdown -->
            <div>
               <button @click="mobileUserManagementOpen = !mobileUserManagementOpen"
                       class="w-full flex items-center justify-between px-3 py-2 {{ request()->is('account/users*') || request()->is('account/roles*') || request()->is('account/permissions*') ? 'text-primary bg-primary/10' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg">
                  <div class="flex items-center">
                     <x-icons.users width="20" height="20" class="mr-3"/>
                     Users Management
                  </div>
                  <svg class="w-4 h-4 transition-transform" :class="mobileUserManagementOpen ? 'rotate-180' : ''"
                       fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
               </button>
               <div x-show="mobileUserManagementOpen" x-transition class="ml-8 mt-2 space-y-1">
                  @can('roles.index')
                     <a href="/account/roles"
                        class="block px-3 py-2 text-sm {{ request()->is('account/roles*') ? 'text-primary' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg">Roles</a>
                  @endcan

                  @can('permissions.index')
                     <a href="/account/permissions"
                        class="block px-3 py-2 text-sm {{ request()->is('account/permissions*') ? 'text-primary' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg">Permissions</a>
                  @endcan

                  @can('users.index')
                     <a href="/account/users"
                        class="block px-3 py-2 text-sm {{ request()->is('account/users*') ? 'text-primary' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg">Users</a>
                  @endcan
               </div>
            </div>
         @endif
      </nav>

      <!-- Mobile Menu Footer -->
      <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200">
         <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-r from-primary to-secondary rounded-full flex items-center justify-center text-white">
               {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div>
               <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
               <p class="text-xs text-gray-500">{{ auth()->user()->getRoleNames()->join(', ') }}</p>
            </div>
         </div>
      </div>
   </div>
</div>
