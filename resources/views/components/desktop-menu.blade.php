<!-- Top Navigation -->
<nav
        x-data="{
        userDropdownOpen: false,
        masterDropdownOpen: false,
        reportsDropdownOpen: false,
        userManagementDropdownOpen: false,
    }"
        class="bg-white shadow sticky top-0 z-30">
   <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
         <div class="flex items-center">
            <!-- Mobile menu button -->
            <button @click="$store.mobileMenuOpen = true"
                    class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 mr-3">
               <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h7"></path>
               </svg>
            </button>

            <a href="{{ route('account.dashboard.index') }}">
               <div class="flex-shrink-0 flex items-center">
                  <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                     <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                     </svg>
                  </div>
                  <span class="ml-2 text-xl font-bold text-gray-900">MemberSidoel</span>
               </div>
            </a>

            <!-- Desktop Navigation Links with Icons -->
            <div class="hidden lg:ml-10 lg:flex lg:space-x-8 ">


               <a href="/account/dashboard"
                  class="{{ request()->is('account/dashboard*') ? 'text-primary' : '' }} text-gray-500 px-1 pt-2 pb-2 text-sm font-medium flex items-center space-x-2">
                  <x-icons.home width="20" height="20"/>
                  <span>Dashboard</span>
               </a>

               @if(auth()->user()->can('outlets.index') || auth()->user()->can('customers.index') || auth()->user()->can('category-expenses.index') || auth()->user()->can('expenses.index') || auth()->user()->can('category-packages.index') || auth()->user()->can('packages.index'))
                  <!-- Desktop Master Dropdown -->
                  <div class="relative" @click.away="masterDropdownOpen = false">
                     <button
                             @click="masterDropdownOpen = !masterDropdownOpen"
                             class="{{ request()->is('account/units*') ||request()->is('account/outlets*') || request()->is('account/customers*') || request()->is('account/category-expenses*') || request()->is('account/expenses*') || request()->is('account/category-packages*') || request()->is('account/packages*') ? 'text-primary' : '' }}
                                cursor-pointer text-gray-500 hover:text-gray-700 px-1 pt-2 pb-2 text-sm font-medium transition-colors flex items-center space-x-2"
                     >
                        <x-icons.category width="20" height="20"/>
                        <span>Master</span>
                        <svg class="w-4 h-4 transition-transform" :class="masterDropdownOpen ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                 d="M19 9l-7 7-7-7"></path>
                        </svg>
                     </button>
                     <div x-show="masterDropdownOpen" x-cloak x-transition
                          class="absolute top-full left-0 mt-1 w-48 bg-white rounded-xl border border-gray-200 py-1 z-50 p-2">

                        @can('units.index')
                           <a href="/account/units"
                              class="block px-4 py-2 text-sm {{ request()->is('account/units*') ? 'text-primary' : '' }} text-gray-700 hover:bg-gray-100 hover:rounded-xl">Units</a>
                        @endcan

                        @can('outlets.index')
                           <a href="/account/outlets"
                              class="block px-4 py-2 text-sm {{ request()->is('account/outlets*') ? 'text-primary' : '' }} text-gray-700 hover:bg-gray-100 hover:rounded-xl">Outlets</a>
                        @endcan

                        @can('customers.index')
                           <a href="/account/customers"
                              class="block px-4 py-2 text-sm {{ request()->is('account/customers*') ? 'text-primary' : '' }} text-gray-700 hover:bg-gray-100 hover:rounded-xl">Customers</a>
                        @endcan

                        <div class="border-t border-gray-100 my-1"></div>

                        @can('category-expenses.index')
                           <a href="/account/category-expenses"
                              class="block px-4 py-2 text-sm {{ request()->is('account/category-expenses*') ? 'text-primary' : '' }} text-gray-700 hover:bg-gray-100 hover:rounded-xl">Expense
                              Category</a>
                        @endcan

                        @can('expenses.index')
                           <a href="/account/expenses"
                              class="block px-4 py-2 text-sm {{ request()->is('account/expenses*') ? 'text-primary' : '' }} text-gray-700 hover:bg-gray-100 hover:rounded-xl">Expenses</a>
                        @endcan

                        <div class="border-t border-gray-100 my-1"></div>

                        @can('category-packages.index')
                           <a href="/account/category-packages"
                              class="block px-4 py-2 text-sm {{ request()->is('account/category-packages*') ? 'text-primary' : '' }} text-gray-700 hover:bg-gray-100 hover:rounded-xl">Package
                              Category</a>
                        @endcan

                        @can('packages.index')
                           <a href="/account/packages"
                              class="block px-4 py-2 text-sm {{ request()->is('account/packages*') ? 'text-primary' : '' }} text-gray-700 hover:bg-gray-100 hover:rounded-xl">Packages</a>
                        @endcan
                     </div>
                  </div>
               @endif

               @can('transactions.index')
                  <a href="/account/transactions"
                     class="text-gray-500 {{ request()->is('account/transactions*')  ? 'text-primary' : '' }} hover:text-gray-700 px-1 pt-2 pb-2 text-sm font-medium transition-colors flex items-center space-x-2">
                     <x-icons.cash-register width="20" height="20"/>
                     <span>Transactions</span>
                  </a>
               @endcan

               @can('reports.cashflow')
                  <!-- Desktop Reports Dropdown -->
                  <div class="relative" @click.away="reportsDropdownOpen = false">
                     <button @click="reportsDropdownOpen = !reportsDropdownOpen"
                             class="cursor-pointer {{ request()->is('account/reports*') ||request()->is('account/status-transactions*') ? 'text-primary' : '' }} text-gray-500 hover:text-gray-700 px-1 pt-2 pb-2 text-sm font-medium transition-colors flex items-center space-x-2">
                        <x-icons.chart-pie width="20" height="20"/>
                        <span>Reports</span>
                        <svg class="w-4 h-4 transition-transform" :class="reportsDropdownOpen ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                 d="M19 9l-7 7-7-7"></path>
                        </svg>
                     </button>
                     <div x-show="reportsDropdownOpen" x-cloak x-transition
                          class="absolute top-full left-0 mt-1 w-48 bg-white rounded-xl border border-gray-200 py-1 z-50 p-2">
                        <a href="/account/status-transactions"
                                         class="block px-4 py-2 text-sm {{ request()->is('account/status-transactions*') ? 'text-primary' : '' }} text-gray-700 hover:bg-gray-100 hover:rounded-xl">
                           Transaction Status</a>
                        <a href="/account/reports"
                           class="block px-4 py-2 text-sm {{ request()->is('account/reports') ? 'text-primary' : '' }} text-gray-700 hover:bg-gray-100 hover:rounded-xl">Financial
                           Report</a>
                     </div>
                  </div>
               @endcan

               @if(auth()->user()->can('users.index') || auth()->user()->can('roles.index') || auth()->user()->can('permissions.index'))
                  <!-- Desktop Users Management Dropdown -->
                  <div class="relative" @click.away="userManagementDropdownOpen = false">
                     <button @click="userManagementDropdownOpen = !userManagementDropdownOpen"
                             class="cursor-pointer {{ request()->is('account/users*') || request()->is('account/roles*') || request()->is('account/permissions*') ? 'text-primary' : '' }} text-gray-500 hover:text-gray-700 px-1 pt-2 pb-2 text-sm font-medium transition-colors flex items-center space-x-2">
                        <x-icons.users width="20" height="20"/>
                        <span>Users Management</span>
                        <svg class="w-4 h-4 transition-transform"
                             :class="userManagementDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                 d="M19 9l-7 7-7-7"></path>
                        </svg>
                     </button>
                     <div x-show="userManagementDropdownOpen" x-cloak x-transition
                          class="absolute top-full left-0 mt-1 w-48 bg-white rounded-xl border border-gray-200 py-1 z-50 p-2">
                        @can('roles.index')
                           <a href="/account/roles"
                              class="block px-4 py-2 text-sm {{ request()->is('account/roles*') ? 'text-primary' : '' }} text-gray-700 hover:bg-gray-100 hover:rounded-xl">Roles</a>
                        @endcan

                        @can('permissions.index')
                           <a href="/account/permissions"
                              class="block px-4 py-2 text-sm {{ request()->is('account/permissions*') ? 'text-primary' : '' }} text-gray-700 hover:bg-gray-100 hover:rounded-xl">Permissions</a>
                        @endcan

                        @can('users.index')
                           <a href="/account/users"
                              class="block px-4 py-2 text-sm {{ request()->is('account/users*') ? 'text-primary' : '' }} text-gray-700 hover:bg-gray-100 hover:rounded-xl">Users</a>
                        @endcan
                     </div>
                  </div>
               @endif

            </div>
         </div>

         <div class="flex items-center space-x-4">

            <!-- User Dropdown -->
            <div class="relative" @click.away="userDropdownOpen = false">
               <button @click="userDropdownOpen = !userDropdownOpen"
                       class="flex cursor-pointer items-center space-x-3 p-1 rounded-lg ">
                  <div class="hidden sm:block text-right">
                     <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                     <p class="text-xs text-gray-500">{{ auth()->user()->getRoleNames()->join(', ') }}</p>
                  </div>
                  <div class="w-8 h-8 bg-gradient-to-r from-primary to-secondary rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold text-sm">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </span>
                  </div>
                  <svg class="w-4 h-4 text-gray-400 transition-transform" :class="userDropdownOpen ? 'rotate-180' : ''"
                       fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
               </button>

               <!-- User Dropdown Menu -->
               <div x-show="userDropdownOpen" x-cloak x-transition
                    class="absolute right-0 top-full mt-2 w-56 bg-white rounded-xl border border-gray-200 py-1 z-50 p-2">
                  <div class="px-4 py-3 border-b border-gray-100">
                     <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                     <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                  </div>
                  <a href="/account/users/{{ auth()->user()->id }}/edit"
                     class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 hover:rounded-xl">
                     <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                     </svg>
                     My Profile
                  </a>
                  <div class="border-t border-gray-100"></div>
                  <a href="{{ route('logout') }}" style="cursor: pointer"
                     onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                     class="flex items-center px-4 py-3 text-sm text-red-600 hover:bg-red-50 hover:rounded-xl">
                     <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                     </svg>
                     Sign Out
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                     @csrf
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</nav>
