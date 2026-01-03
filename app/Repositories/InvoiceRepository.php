<?php

namespace App\Repositories;

use App\Models\Invoice;
use Spatie\QueryBuilder\QueryBuilder;

class InvoiceRepository extends BaseRepository
{
    public function __construct(Invoice $model)
    {
        parent::__construct($model);
    }

    public function getAllWithFilters($userId = null)
    {
        $query = Invoice::withRelations();

        if ($userId) {
            $query->forUser($userId);
        }

        return QueryBuilder::for($query)
            ->allowedFilters(['invoice_number', 'customer_name'])
            ->allowedSorts(['created_at', 'total'])
            ->latest()
            ->paginate(config('printing.pagination_limit'));
    }

    public function findWithRelations($id)
    {
        return $this->model->withRelations()->findOrFail($id);
    }

    public function getForDate($date)
    {
        return $this->model->withRelations()->forDate($date)->get();
    }

    public function getTotalForDate($date)
    {
        return $this->model->forDate($date)->sum('total');
    }

    public function getCountForDate($date)
    {
        return $this->model->forDate($date)->count();
    }

    public function getBetweenDates($startDate, $endDate)
    {
        return $this->model->betweenDates($startDate, $endDate)->get();
    }

    public function getTotalBetweenDates($startDate, $endDate)
    {
        return $this->model->betweenDates($startDate, $endDate)->sum('total');
    }

    public function getCountBetweenDates($startDate, $endDate)
    {
        return $this->model->betweenDates($startDate, $endDate)->count();
    }
}
