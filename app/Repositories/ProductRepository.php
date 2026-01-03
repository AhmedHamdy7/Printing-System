<?php

namespace App\Repositories;

use App\Models\Product;
use Spatie\QueryBuilder\QueryBuilder;

class ProductRepository extends BaseRepository
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function getActive()
    {
        return $this->model->active()->get();
    }

    public function getAllWithFilters()
    {
        return QueryBuilder::for(Product::class)
            ->allowedFilters(['name', 'is_active'])
            ->allowedSorts(['name', 'price', 'created_at'])
            ->paginate(config('printing.pagination_limit'));
    }

    public function findWithItems($id)
    {
        return $this->model->with('invoiceItems')->findOrFail($id);
    }
}
