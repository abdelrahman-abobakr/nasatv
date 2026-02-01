<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SubscriptionsExport;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of subscriptions.
     */
    public function index(Request $request)
    {
        $query = Subscription::with('addedBy');

        // Admin sees all, employee sees only their own
        if (auth()->user()->isEmployee()) {
            $query->where('added_by', auth()->id());
        }

        // Filter by employee (admin only)
        if ($request->has('employee') && $request->employee && auth()->user()->isAdmin()) {
            $employeeId = $request->employee;
            // Support both direct ID and search string (though ID is preferred from dropdown)
            // If it's a numeric ID, filter directly. If it looks like a name search, you might need more complex logic, 
            // but for now, assuming the frontend sends an ID or we filter by relation if it's a string.
            // Let's stick to ID for the filter value, but the UI will handle the search.
            $query->where('added_by', $employeeId);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            switch ($request->status) {
                case 'expired':
                    $query->whereDate('end_date', '<', now());
                    break;
                case 'expire_soon':
                    $query->whereDate('end_date', '>=', now())
                          ->whereDate('end_date', '<=', now()->addMonths(3));
                    break;
                case 'active':
                    $query->whereDate('end_date', '>', now()->addMonths(3));
                    break;
            }
        }

        // Search by client name or phone
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('client_name', 'like', '%' . $request->search . '%')
                  ->orWhere('client_phone', 'like', '%' . $request->search . '%');
            });
        }
        
        // ... (Date filters remain)

        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }

        $subscriptions = $query->latest()->paginate(15);

        // Get employees for filter dropdown (admin only)
        $employees = auth()->user()->isAdmin() 
            ? User::where('role', 'employee')->get() 
            : collect();

        if (auth()->user()->isAdmin()) {
            return view('admin.subscriptions.index', compact('subscriptions', 'employees'));
        } else {
            return view('employee.subscriptions.index', compact('subscriptions'));
        }
    }

    /**
     * Show the form for creating a new subscription.
     */
    public function create()
    {
        if (auth()->user()->isEmployee()) {
            return view('employee.subscriptions.create');
        }
        $employees = User::where('role', 'employee')->get();
        return view('admin.subscriptions.create', compact('employees'));
    }

    /**
     * Store a newly created subscription in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_name' => ['required', 'string', 'max:255'],
            'client_phone' => ['nullable', 'string', 'max:255'],
            'plan' => ['required', 'string', 'in:basic,premium,vip'],
            'amount' => ['required', 'numeric', 'min:0'],
            'duration' => ['required', 'integer', 'min:1'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $startDate = $request->start_date ? \Carbon\Carbon::parse($request->start_date) : now();
        $endDate = $request->end_date ? \Carbon\Carbon::parse($request->end_date) : $startDate->copy()->addMonths((int)$request->duration);

        $addedBy = auth()->id();
        if (auth()->user()->isAdmin() && $request->has('added_by') && $request->added_by) {
            $addedBy = $request->added_by;
        }

        Subscription::create([
            'client_name' => $request->client_name,
            'client_phone' => $request->client_phone,
            'plan' => $request->plan,
            'amount' => $request->amount,
            'duration' => $request->duration,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'added_by' => $addedBy,
        ]);

        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.subscriptions.index')
                ->with('success', 'Subscription created successfully.');
        } else {
            return redirect()->route('employee.subscriptions.index')
                ->with('success', 'Subscription created successfully.');
        }
    }

    /**
     * Show the form for editing the specified subscription (admin only).
     */
    public function edit(Subscription $subscription)
    {
        if (auth()->user()->isEmployee()) {
             // Check if employee owns the subscription
             if ($subscription->added_by !== auth()->id()) {
                 abort(403);
             }
             return view('employee.subscriptions.edit', compact('subscription'));
        }
        $employees = User::where('role', 'employee')->get();
        return view('admin.subscriptions.edit', compact('subscription', 'employees'));
    }

    /**
     * Update the specified subscription in storage (admin only).
     */
    public function update(Request $request, Subscription $subscription)
    {
        $request->validate([
            'client_name' => ['required', 'string', 'max:255'],
            'client_phone' => ['nullable', 'string', 'max:255'],
            'plan' => ['required', 'string', 'in:basic,premium,vip'],
            'amount' => ['required', 'numeric', 'min:0'],
            'duration' => ['required', 'integer', 'min:1'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        if (auth()->user()->isEmployee() && $subscription->added_by !== auth()->id()) {
            abort(403);
        }

        $updateData = $request->all();

        // Recalculate end_date if duration changed or start_date changed, 
        // OR better yet, let's just respect the input if provided, or recalculate if logic dictates.
        // For simplicity, let's trust the input validation for now, but we might want to re-run the date logic if fields are missing?
        // Actually, the request validation requires start/end date in the update method currently (see lines 121-122).
        // Let's stick to the validated data.
        
        if (auth()->user()->isAdmin() && $request->has('added_by')) {
            $updateData['added_by'] = $request->added_by;
        }

        $subscription->update($updateData);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription updated successfully.');
    }

    /**
     * Remove the specified subscription from storage (admin only).
     */
    public function destroy(Subscription $subscription)
    {
        if (auth()->user()->isEmployee() && $subscription->added_by !== auth()->id()) {
             abort(403);
        }
        $subscription->delete();

        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.subscriptions.index')
                ->with('success', 'Subscription deleted successfully.');
            return redirect()->route('employee.subscriptions.index')
                ->with('success', 'Subscription deleted successfully.');
        }
    }

    public function export(Request $request)
    {
        return Excel::download(new SubscriptionsExport($request), 'subscriptions_' . now()->format('Y-m-d_H-i') . '.xlsx');
    }
}
