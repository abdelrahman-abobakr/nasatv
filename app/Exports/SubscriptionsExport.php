<?php

namespace App\Exports;

use App\Models\Subscription;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class SubscriptionsExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = Subscription::query()->with('addedBy');

        // Apply filters (logic duplicated/shared from Controller)
        if ($this->request->has('search') && $this->request->search) {
            $search = $this->request->search;
            $query->where(function ($q) use ($search) {
                $q->where('client_name', 'like', "%{$search}%")
                  ->orWhere('client_phone', 'like', "%{$search}%");
            });
        }

        if ($this->request->has('employee') && $this->request->employee) {
            $query->where('added_by', $this->request->employee);
        }

        if ($this->request->has('status') && $this->request->status) {
            switch ($this->request->status) {
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

        if ($this->request->has('start_date') && $this->request->start_date) {
            $query->whereDate('start_date', '>=', $this->request->start_date);
        }

        if ($this->request->has('end_date') && $this->request->end_date) {
            $query->whereDate('end_date', '<=', $this->request->end_date);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Client Name',
            'Phone',
            'Plan',
            'Amount',
            'Duration (Months)',
            'Start Date',
            'End Date',
            'Added By',
            'Created At',
        ];
    }

    public function map($subscription): array
    {
        return [
            $subscription->id,
            $subscription->client_name,
            $subscription->client_phone,
            ucfirst($subscription->plan),
            $subscription->amount,
            $subscription->duration,
            $subscription->start_date->format('Y-m-d'),
            $subscription->end_date->format('Y-m-d'),
            $subscription->addedBy ? $subscription->addedBy->name : 'N/A',
            $subscription->created_at->format('Y-m-d H:i'),
        ];
    }
}
