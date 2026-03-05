<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Note;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        
        $date = Carbon::createFromDate($year, $month, 1);
        
        $expenses = Expense::where('user_id', $userId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get()
            ->groupBy(fn($item) => Carbon::parse($item->date)->format('Y-m-d'));

        $incomes = Income::where('user_id', $userId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get()
            ->groupBy(fn($item) => Carbon::parse($item->date)->format('Y-m-d'));

        $notes = Note::where('user_id', $userId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get()
            ->groupBy('date');

        return view('calendar.index', compact('expenses', 'incomes', 'notes', 'date'));
    }
}
