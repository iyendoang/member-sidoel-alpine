<?php

   namespace App\Http\Controllers\Account;

   use Illuminate\Http\Request;
   use App\Models\Unit;
   use App\Http\Controllers\Controller;
   use Illuminate\Routing\Controllers\Middleware;
   use Illuminate\Routing\Controllers\HasMiddleware;

   class UnitController extends Controller implements HasMiddleware
   {
      /**
       * middleware
       */
      public static function middleware(): array {
         return [
            new Middleware(['permission:units.index'], only:['index']),
            new Middleware(['permission:units.create'], only:['create', 'store']),
            new Middleware(['permission:units.edit'], only:['edit', 'update']),
            new Middleware(['permission:units.delete'], only:['destroy']),
         ];
      }

      /**
       * index
       */
      public function index()
      {
         $units = Unit::when(request()->search, function ($query) {
            $query->where('name', 'like', '%' . request()->search . '%');
         })
                      ->orderBy('name', 'asc')
                      ->paginate(10);

         $units->appends(['search' => request()->search]);

         return view('account.units.index', compact('units'));
      }


      /**
       * create
       */
      public function create() {
         return view('account.units.create');
      }

      /**
       * store
       */
      public function store(Request $request) {
         // Validasi input
         $request->validate([
            'name' => 'required|unique:units,name',
         ]);
         // Simpan unit baru
         Unit::create([
            'name' => $request->name,
         ]);

         return redirect()
            ->route('account.units.index')
            ->with('success', 'Unit created successfully');
      }

      /**
       * edit
       */
      public function edit($id) {
         $unit = Unit::findOrFail($id);

         return view('account.units.edit', compact('unit'));
      }

      /**
       * update
       */
      public function update(Request $request, Unit $unit) {
         // Validasi input
         $request->validate([
            'name' => 'required|unique:units,name,' . $unit->id,
         ]);
         $unit->update([
            'name' => $request->name,
         ]);

         return redirect()
            ->route('account.units.index')
            ->with('success', 'Unit updated successfully');
      }

      /**
       * destroy
       */
      public function destroy($id) {
         $unit = Unit::findOrFail($id);
         $unit->delete();

         return redirect()
            ->route('account.units.index')
            ->with('success', 'Unit deleted successfully');
      }
   }
