<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Expense;
use App\Models\Income;
use Carbon\Carbon;

class AiAdvisorController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $thisMonth = Carbon::now();
        
        // Data for analysis
        $income = Income::where('user_id', $userId)->whereMonth('date', $thisMonth->month)->whereYear('date', $thisMonth->year)->sum('amount');
        $expenses = Expense::where('user_id', $userId)->whereMonth('date', $thisMonth->month)->whereYear('date', $thisMonth->year)->sum('amount');
        
        $savingsRate = $income > 0 ? (($income - $expenses) / $income) * 100 : 0;
        
        $insights = [];
        
        // Prediction Logic
        $last3MonthsExpenses = Expense::where('user_id', $userId)
            ->where('date', '>=', Carbon::now()->subMonths(3)->startOfMonth())
            ->where('date', '<=', Carbon::now()->subMonth()->endOfMonth())
            ->select(\DB::raw('SUM(amount) as total'), \DB::raw('MONTH(date) as month'))
            ->groupBy('month')
            ->pluck('total')
            ->toArray();

        $avgExpenses = count($last3MonthsExpenses) > 0 ? array_sum($last3MonthsExpenses) / count($last3MonthsExpenses) : $expenses;
        $predictedExpenses = $avgExpenses * 1.05; 
        
        $last3MonthsIncome = Income::where('user_id', $userId)
            ->where('date', '>=', Carbon::now()->subMonths(3)->startOfMonth())
            ->where('date', '<=', Carbon::now()->subMonth()->endOfMonth())
            ->select(\DB::raw('SUM(amount) as total'), \DB::raw('MONTH(date) as month'))
            ->groupBy('month')
            ->pluck('total')
            ->toArray();

        $avgIncome = count($last3MonthsIncome) > 0 ? array_sum($last3MonthsIncome) / count($last3MonthsIncome) : $income;
        $predictedIncome = $avgIncome; 

        $topExpense = Expense::where('user_id', $userId)
            ->whereMonth('date', $thisMonth->month)
            ->select('description', \DB::raw('SUM(amount) as total'))
            ->groupBy('description')
            ->orderByDesc('total')
            ->first();

        $topExpenseName = $topExpense->description ?? 'N/A';
        $topExpenseAmount = $topExpense->total ?? 0;

        try {
            if (config('openai.api_key') && !str_contains(config('openai.api_key'), 'replace-this')) {
                $response = \OpenAI\Laravel\Facades\OpenAI::chat()->create([
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a professional financial advisor AI. Provide advice in JSON format.'],
                        ['role' => 'user', 'content' => "Analyze these financials for this month:
                            Current Income: {$income}
                            Current Expenses: {$expenses}
                            Savings Rate: {$savingsRate}%
                            Top Expense: {$topExpenseName} ({$topExpenseAmount})
                            
                            Predictions for Next Month:
                            Expected Income: {$predictedIncome}
                            Expected Expenses: {$predictedExpenses}
                            
                            Provide 4 specific, actionable, and encouraging financial insights. 
                            Return ONLY a JSON array of objects with keys: type (success, warning, info, advice, strategy), title, icon (sparkles, trending-up, lightning-bolt, chart-pie, briefcase), and message."],
                    ],
                ]);

                $aiContent = $response->choices[0]->message->content;
                $insights = json_decode($aiContent, true);
            }
        } catch (\Exception $e) {
            \Log::error("OpenAI Error: " . $e->getMessage());
        }

        // Fallback if OpenAI fails or not configured
        if (empty($insights)) {
            // ... (keep current fallback logic)
            if ($savingsRate > 20) {
                $insights[] = [
                    'type' => 'success',
                    'title' => 'Excellent Savings!',
                    'icon' => 'sparkles',
                    'message' => "You're saving " . round($savingsRate) . "% of your income. Consider investing this surplus in low-risk mutual funds."
                ];
            } else {
                $insights[] = [
                    'type' => 'warning',
                    'title' => 'Savings Alert',
                    'icon' => 'lightning-bolt',
                    'message' => "Your savings rate is low. Try tracking hidden subscriptions."
                ];
            }
        }

        $prediction = [
            'income' => $predictedIncome,
            'expenses' => $predictedExpenses,
            'savings' => $predictedIncome - $predictedExpenses,
            'trend' => ($predictedExpenses > $expenses) ? 'up' : 'stable'
        ];

        return view('ai-advisor.index', compact('insights', 'income', 'expenses', 'savingsRate', 'prediction'));
    }

    public function chat(Request $request)
    {
        $message = $request->input('message');
        $userId = auth()->id();

        // Fetch financial context
        $income = Income::where('user_id', $userId)->whereMonth('date', Carbon::now()->month)->sum('amount');
        $expenses = Expense::where('user_id', $userId)->whereMonth('date', Carbon::now()->month)->sum('amount');
        $recentExpenses = Expense::where('user_id', $userId)->orderByDesc('date')->limit(5)->get();

        $context = "User's current month income: {$income}. Current month expenses: {$expenses}. ";
        $context .= "Recent transactions: " . $recentExpenses->map(fn($e) => "{$e->description} ({$e->amount})")->implode(', ');

        $apiKey = env('GROQ_API_KEY');

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type'  => 'application/json',
            ])->timeout(30)->post('https://api.groq.com/openai/v1/chat/completions', [
                'model'    => 'llama-3.3-70b-versatile',
                'messages' => [
                    [
                        'role'    => 'system',
                        'content' => 'You are a friendly and professional financial AI assistant for an Expense Tracker app named Ex-Tracker. Use the provided financial context to give specific, actionable advice. Respond in the same language as the user (English or Sinhala). Keep answers concise and helpful. Do not use markdown formatting symbols like ** or ##, just plain text.',
                    ],
                    [
                        'role'    => 'user',
                        'content' => "Financial Context: {$context}\n\nUser Message: {$message}",
                    ],
                ],
                'max_tokens'  => 512,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $data  = $response->json();
                $reply = $data['choices'][0]['message']['content'] ?? 'I could not generate a response. Please try again.';
                return response()->json(['reply' => trim($reply)]);
            } else {
                $errorBody = $response->json();
                $errorMsg  = $errorBody['error']['message'] ?? 'Unknown error';
                \Log::error("Groq API Error: " . $errorMsg);

                if (str_contains($errorMsg, 'rate_limit') || $response->status() === 429) {
                    $reply = '⚠️ AI is a bit busy right now. Please wait a few seconds and try again.';
                } elseif (str_contains($errorMsg, 'invalid_api_key') || $response->status() === 401) {
                    $reply = '🔑 Groq API key is invalid. Please check your configuration.';
                } else {
                    $reply = '❌ Could not get a response. Please try again.';
                }

                return response()->json(['reply' => $reply], 503);
            }
        } catch (\Exception $e) {
            \Log::error("Groq Chat Exception: " . $e->getMessage());
            return response()->json(['reply' => '❌ Connection failed. Please try again.'], 503);
        }
    }
}
