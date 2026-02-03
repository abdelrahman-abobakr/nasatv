<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function getChartData(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year') ?? now()->year;

        $users = User::where('role', 'employee')
            ->select('name', 'id')
            ->withSum(['subscriptions' => function ($query) use ($month, $year) {
                if ($month && $month !== 'all') {
                    $query->whereMonth('created_at', $month)
                          ->whereYear('created_at', $year);
                } elseif ($year && $year !== 'all') {
                    $query->whereYear('created_at', $year);
                }
            }], 'amount')
            ->withCount(['subscriptions' => function ($query) use ($month, $year) {
                if ($month && $month !== 'all') {
                    $query->whereMonth('start_date', $month)
                          ->whereYear('start_date', $year);
                } elseif ($year && $year !== 'all') {
                    $query->whereYear('start_date', $year);
                }
            }])
            ->get();

        $labels = $users->pluck('name');
        
        $amountData = $users->map(function ($user) {
            return $user->subscriptions_sum_amount ?? 0;
        });

        $countData = $users->pluck('subscriptions_count');

        return response()->json([
            'labels' => $labels,
            'amount_data' => $amountData,
            'count_data' => $countData,
        ]);
    }
}
