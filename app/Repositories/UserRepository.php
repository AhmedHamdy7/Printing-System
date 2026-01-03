<?php

namespace App\Repositories;

use App\Models\User;
use Spatie\QueryBuilder\QueryBuilder;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getAllWithRoles()
    {
        return QueryBuilder::for(User::class)
            ->allowedFilters(['name', 'email'])
            ->allowedSorts(['name', 'email', 'created_at'])
            ->paginate(config('printing.pagination_limit'));
    }

    public function findWithRoles($id)
    {
        return $this->model->with('roles')->findOrFail($id);
    }

    public function getEmployeesWithInvoiceStats($startDate, $endDate)
    {
        return User::withCount([
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
    }
}
