<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        // ✅ If you already have a logs table/model, replace this section with real query.
        // For now: sample static data like screenshot
        $logs = collect([
            (object)[
                'date' => '2026-01-07 17:18:25',
                'action' => '[Warning] Please log in to the website.',
                'ip' => '***.***.57.126',
            ],
            (object)[
                'date' => '2026-01-06 01:40:47',
                'action' => 'Create an account',
                'ip' => '***.***.240.57',
            ],
        ]);

        // Filters (UI only for now)
        $filters = [
            'action' => $request->get('action'),
            'ip'     => $request->get('ip'),
            'date'   => $request->get('date'),
            'sort'   => $request->get('sort', 'today'),
            'show'   => (int) $request->get('show', 10),
        ];

        return view('customer.activity.index', compact('logs', 'filters'));
    }
}
