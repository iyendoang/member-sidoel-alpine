<?php

   namespace App\Http\Controllers\Account;

   use App\Models\Outlet;
   use Illuminate\Http\Request;
   use App\Http\Controllers\Controller;
   use Illuminate\Routing\Controllers\Middleware;
   use Illuminate\Routing\Controllers\HasMiddleware;
   use HTMLPurifier;
   use HTMLPurifier_Config;

   class OutletController extends Controller implements HasMiddleware
   {
      public static function middleware(): array
      {
         return [
            new Middleware(['permission:outlets.index'], only: ['index']),
            new Middleware(['permission:outlets.create'], only: ['create', 'store']),
            new Middleware(['permission:outlets.edit'], only: ['edit', 'update']),
            new Middleware(['permission:outlets.delete'], only: ['destroy']),
         ];
      }

      public function index()
      {
         $outlets = Outlet::when(request('search'), function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
         })->latest()->paginate(10)->withQueryString();

         return view('account.outlets.index', compact('outlets'));
      }

      public function create()
      {
         return view('account.outlets.create');
      }

      public function store(Request $request)
      {
         $validated = $request->validate([
            'code_outlet' => 'required|unique:outlets,code_outlet',
            'name'        => 'required',
            'address'     => 'required',
            'phone'       => 'required',
            'notes'       => 'nullable|string',
         ]);

         $validated['notes'] = $this->purifyNotes($validated['notes'] ?? null);

         Outlet::create($validated);

         return redirect()
            ->route('account.outlets.index')
            ->with('success', 'Outlet created successfully');
      }

      public function edit(Outlet $outlet)
      {
         return view('account.outlets.edit', compact('outlet'));
      }

      public function update(Request $request, Outlet $outlet)
      {
         $validated = $request->validate([
            'code_outlet' => 'required|unique:outlets,code_outlet,' . $outlet->id,
            'name'        => 'required',
            'address'     => 'required',
            'phone'       => 'required',
            'notes'       => 'nullable|string',
         ]);

         $validated['notes'] = $this->purifyNotes($validated['notes'] ?? null);

         $outlet->update($validated);

         return redirect()
            ->route('account.outlets.index')
            ->with('success', 'Outlet updated successfully');
      }

      public function destroy(Outlet $outlet)
      {
         $outlet->delete();

         return redirect()
            ->route('account.outlets.index')
            ->with('success', 'Outlet deleted successfully');
      }

      /**
       * Sanitasi HTML notes (production safe)
       */
      protected function purifyNotes(?string $notes): ?string
      {
         if (!$notes) {
            return null;
         }

         $config = HTMLPurifier_Config::createDefault();

         // ⬅️ INI PENTING
         $config->set('Cache.SerializerPath', storage_path('app/htmlpurifier'));

         return (new HTMLPurifier($config))->purify($notes);
      }
   }
