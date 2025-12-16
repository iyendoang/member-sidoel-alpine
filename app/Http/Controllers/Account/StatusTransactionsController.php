<?php

   namespace App\Http\Controllers\Account;

   use App\Http\Controllers\Controller;
   use App\Models\Transaction;
   use Illuminate\Http\Request;

   class StatusTransactionsController extends Controller
   {
      /**
       * Display a listing of transactions.
       */
      public function index()
      {
         $transactions = Transaction::with(['outlet', 'customer','transaction_details'])
                                    ->when(request('search'), fn($q) => $q->where('invoice', 'like', '%' . request('search') . '%'))
                                    ->latest()
                                    ->paginate(10)
                                    ->appends(['search' => request('search')]);

         return view('account.status-transactions.index', compact('transactions'));
      }

      /**
       * Update transaction inline fields.
       */
      public function update(Request $request, Transaction $transaction)
      {
         if (!$transaction->exists) {
            return response()->json([
               'success' => false,
               'message' => 'Transaction not found'
            ], 404);
         }

         try {
            // Validasi input
            $validated = $request->validate([
               'status' => 'sometimes|in:NEW,IN PROGRESS,COMPLETED,CANCELLED',
               'level' => 'sometimes|nullable|in:RA,MI,MTS,MA',
               'district' => 'sometimes|nullable|string|max:255',
               'domain' => 'sometimes|nullable|url',
            ]);

            // Update transaction
            $transaction->update($validated);

            return response()->json([
               'success' => true,
               'message' => 'Transaction updated successfully',
               'data' => $transaction
            ]);
         } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
               'success' => false,
               'message' => 'Validation error',
               'errors' => $e->errors()
            ], 422);
         } catch (\Exception $e) {
            return response()->json([
               'success' => false,
               'message' => 'Update failed',
               'error' => $e->getMessage()
            ], 500);
         }
      }
   }
