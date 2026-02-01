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

        $users = User::where('role', 'employee')
            ->select('name', 'id')
            ->withSum(['subscriptions' => function ($query) use ($month) {
                if ($month && $month !== 'all') {
                    $query->whereMonth('created_at', $month)
                          ->whereYear('created_at', now()->year);
                }
            }], 'amount')
            ->get();

        $labels = $users->pluck('name');
        // If subscriptions_sum_amount is null, map it to 0
        $data = $users->map(function ($user) {
            return $user->subscriptions_sum_amount ?? 0;
        });

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}
