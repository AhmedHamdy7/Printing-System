<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class ReportController extends Controller
{
    public function index()
    {
        try {
            if (!auth()->user()->can('generate-reports')) {
                abort(403, 'Unauthorized action.');
            }

            return view('reports.index');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to load reports: ' . $e->getMessage());
        }
    }

    public function daily(Request $request)
    {
        try {
            if (!auth()->user()->can('generate-reports')) {
                abort(403, 'Unauthorized action.');
            }

            $validated = $request->validate([
                'date' => 'nullable|date',
            ]);

            $date = $validated['date'] ?? now()->toDateString();

            $income = Invoice::whereDate('created_at', $date)->sum('total');
            $invoicesCount = Invoice::whereDate('created_at', $date)->count();
            $invoices = Invoice::with(['user', 'items.product'])
                ->whereDate('created_at', $date)
                ->get();

            return view('reports.daily', compact('income', 'invoicesCount', 'invoices', 'date'));
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to generate daily report: ' . $e->getMessage());
        }
    }

    public function monthly(Request $request)
    {
        try {
            if (!auth()->user()->can('generate-reports')) {
                abort(403, 'Unauthorized action.');
            }

            $validated = $request->validate([
                'month' => 'nullable|date_format:Y-m',
            ]);

            $month = $validated['month'] ?? now()->format('Y-m');
            $monthStart = date('Y-m-01', strtotime($month));
            $monthEnd = date('Y-m-t', strtotime($month));

            $income = Invoice::whereBetween('created_at', [$monthStart, $monthEnd])->sum('total');
            $invoicesCount = Invoice::whereBetween('created_at', [$monthStart, $monthEnd])->count();

            $dailyIncome = Invoice::whereBetween('created_at', [$monthStart, $monthEnd])
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            return view('reports.monthly', compact('income', 'invoicesCount', 'dailyIncome', 'month'));
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to generate monthly report: ' . $e->getMessage());
        }
    }

    public function products(Request $request)
    {
        try {
            if (!auth()->user()->can('generate-reports')) {
                abort(403, 'Unauthorized action.');
            }

            $validated = $request->validate([
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
            ]);

            $startDate = $validated['start_date'] ?? now()->subMonth()->toDateString();
            $endDate = $validated['end_date'] ?? now()->toDateString();

            $productSales = DB::table('invoice_items')
                ->join('products', 'invoice_items.product_id', '=', 'products.id')
                ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
                ->whereBetween('invoices.created_at', [$startDate, $endDate])
                ->select(
                    'products.id',
                    'products.name',
                    DB::raw('SUM(invoice_items.quantity) as total_quantity'),
                    DB::raw('SUM(invoice_items.total_price) as total_revenue')
                )
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('total_revenue')
                ->get();

            return view('reports.products', compact('productSales', 'startDate', 'endDate'));
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to generate products report: ' . $e->getMessage());
        }
    }

    public function employees(Request $request)
    {
        try {
            if (!auth()->user()->can('generate-reports')) {
                abort(403, 'Unauthorized action.');
            }

            $validated = $request->validate([
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
            ]);

            $startDate = $validated['start_date'] ?? now()->subMonth()->toDateString();
            $endDate = $validated['end_date'] ?? now()->toDateString();

            $employeeSales = User::withCount([
                'invoices' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            ])
            ->withSum([
                'invoices' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            ], 'total')
            ->having('invoices_count', '>', 0)
            ->orderByDesc('invoices_sum_total')
            ->get();

            return view('reports.employees', compact('employeeSales', 'startDate', 'endDate'));
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to generate employees report: ' . $e->getMessage());
        }
    }
}
