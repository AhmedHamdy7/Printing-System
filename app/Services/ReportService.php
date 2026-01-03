<?php

namespace App\Services;

use App\Repositories\InvoiceRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;

class ReportService
{
    protected $invoiceRepository;
    protected $userRepository;

    public function __construct(
        InvoiceRepository $invoiceRepository,
        UserRepository $userRepository
    ) {
        $this->invoiceRepository = $invoiceRepository;
        $this->userRepository = $userRepository;
    }

    public function getDailyReport($date)
    {
        $income = $this->invoiceRepository->getTotalForDate($date);
        $invoicesCount = $this->invoiceRepository->getCountForDate($date);
        $invoices = $this->invoiceRepository->getForDate($date);

        return compact('income', 'invoicesCount', 'invoices', 'date');
    }

    public function getMonthlyReport($month)
    {
        $monthStart = date('Y-m-01', strtotime($month));
        $monthEnd = date('Y-m-t', strtotime($month));

        $income = $this->invoiceRepository->getTotalBetweenDates($monthStart, $monthEnd);
        $invoicesCount = $this->invoiceRepository->getCountBetweenDates($monthStart, $monthEnd);

        $dailyIncome = DB::table('invoices')
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return compact('income', 'invoicesCount', 'dailyIncome', 'month');
    }

    public function getProductSalesReport($startDate, $endDate)
    {
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

        return compact('productSales', 'startDate', 'endDate');
    }

    public function getEmployeesReport($startDate, $endDate)
    {
        $employeeSales = $this->userRepository->getEmployeesWithInvoiceStats($startDate, $endDate);

        return compact('employeeSales', 'startDate', 'endDate');
    }
}
