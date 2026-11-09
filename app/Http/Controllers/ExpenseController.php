<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::latest()->get();

        return view('expenses.index', compact('expenses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => ['required', 'string', 'max:150'],
            'amount' => ['required', 'numeric', 'min:1'],
            'expense_date' => ['required', 'date'],
        ]);

        Expense::create($validated);

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    public function apiIndex()
    {
        return response()->json([
            'data' => Expense::latest()->get(),
        ], 200);
    }

    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'description' => ['required', 'string', 'max:150'],
            'amount' => ['required', 'numeric', 'min:1'],
            'expense_date' => ['required', 'date'],
        ]);

        $expense = Expense::create($validated);

        return response()->json([
            'message' => 'Pengeluaran berhasil ditambahkan.',
            'data' => $expense,
        ], 201);
    }
}
