<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Income;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function dashboard(Request $request)
    {
        $userId = auth()->id();

        // Income logic (current month)
        $monthlyIncome = Income::where('user_id', $userId)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        // Expense logic
        $dailyTotal = Expense::where('user_id', $userId)->whereDate('date', today())->sum('amount');

        $startOfWeek = now()->startOfWeek(); 
        $endOfWeek = now()->endOfWeek();     
        $weeklyTotal = Expense::where('user_id', $userId)->whereBetween('date', [$startOfWeek, $endOfWeek])->sum('amount');

        $monthlyTotal = Expense::where('user_id', $userId)
                            ->whereMonth('date', now()->month)
                            ->whereYear('date', now()->year)
                            ->sum('amount');

        // Remaining balance
        $remainingSalary = $monthlyIncome - $monthlyTotal;

        // Chart Data: Last 7 days expenses
        $last7Days = collect();
        $last7DaysData = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $last7Days->push($date->format('M d'));
            $sum = Expense::where('user_id', $userId)
                ->whereDate('date', $date->format('Y-m-d'))
                ->sum('amount');
            $last7DaysData->push($sum);
        }

        // Recent Notes
        $recentNotes = \App\Models\Note::where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->take(3)
            ->get();

        return view('dashboard', compact(
            'dailyTotal', 'weeklyTotal', 'monthlyTotal', 'monthlyIncome', 'remainingSalary',
            'last7Days', 'last7DaysData', 'recentNotes'
        ));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenses = Expense::where('user_id', auth()->id())->orderBy('date', 'desc')->get();
        return view('expenses.index', compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('expenses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        Expense::create($data);

        return redirect()->route('expenses.index')->with('success', 'Expense added.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $expense = Expense::findOrFail($id);
        if ($expense->user_id !== auth()->id()) {
            abort(403);
        }
        return view('expenses.edit', compact('expense'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $expense = Expense::findOrFail($id);
        if ($expense->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'description' => 'required',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        $expense->update($request->all());

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $expense = Expense::findOrFail($id);
        if ($expense->user_id !== auth()->id()) {
            abort(403);
        }
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}
