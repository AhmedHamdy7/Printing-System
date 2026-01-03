<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;
use Exception;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

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
            $data = $this->reportService->getDailyReport($date);

            return view('reports.daily', $data);
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
            $data = $this->reportService->getMonthlyReport($month);

            return view('reports.monthly', $data);
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

            $data = $this->reportService->getProductSalesReport($startDate, $endDate);

            return view('reports.products', $data);
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

            $data = $this->reportService->getEmployeesReport($startDate, $endDate);

            return view('reports.employees', $data);
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to generate employees report: ' . $e->getMessage());
        }
    }
}
