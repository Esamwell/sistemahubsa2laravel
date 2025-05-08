<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalClients' => Client::count(),
            'totalRequests' => Request::count(),
            'pendingRequests' => Request::where('status', 'pendente')->count(),
            'completedRequests' => Request::where('status', 'concluido')->count(),
            'requestsByStatus' => Request::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get(),
            'requestsByMonth' => Request::select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                    DB::raw('count(*) as count')
                )
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->map(function ($item) {
                    $date = \DateTime::createFromFormat('Y-m', $item->month);
                    return [
                        'month' => $date->format('M/Y'),
                        'count' => $item->count
                    ];
                })
        ];

        return view('dashboard', $data);
    }
} 