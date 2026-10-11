<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ProductSearchService
{
public function search(array $filters): LengthAwarePaginator
{
    $query = Product::query()
        ->select([
            'id',
            'category_id',
            'name',
            'description',
            'price',
            'stock_quantity',
            'created_at',
        ]);

    $this->applySearch($query, $filters);
    $this->applyFilters($query, $filters);
    $this->applySorting($query, $filters);

    $paginator = $query->paginate(
        perPage: (int) ($filters['per_page'] ?? 20),
        columns: ['*'],
        pageName: 'page',
        page: (int) ($filters['page'] ?? 1),
    );

    // Preserve search filters in pagination links.
    $paginator->appends(
        collect($filters)
            ->except('page')
            ->all()
    );

    return $paginator;
}

    private function applySearch(Builder $query,array $filters): void {
        $term = $filters['q'] ?? null;

        if ($term !== null && $term !== '') {
            // Use MySQL FULLTEXT instead of loading products into PHP.
            $query->whereFullText(
                ['name', 'description'],
                $term
            );
        }
    }

    private function applyFilters(Builder $query,array $filters): void {
        $query->when(
            isset($filters['min_price']),
            fn (Builder $builder) => $builder->where(
                'price',
                '>=',
                $filters['min_price']
            )
        );

        $query->when(
            isset($filters['max_price']),
            fn (Builder $builder) => $builder->where(
                'price',
                '<=',
                $filters['max_price']
            )
        );

        $query->when(
            isset($filters['category_id']),
            fn (Builder $builder) => $builder->where(
                'category_id',
                $filters['category_id']
            )
        );

        $query->when(
            filter_var(
                $filters['in_stock'] ?? false,
                FILTER_VALIDATE_BOOLEAN
            ),
            fn (Builder $builder) => $builder->where(
                'stock_quantity',
                '>',
                0
            )
        );
    }

    private function applySorting(Builder $query, array $filters): void
    {
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
    
        // Apply the requested sorting direction.
        $query->orderBy($sortBy, $sortOrder);
    
        // Match the secondary sort direction to improve index usage.
        $query->orderBy('id', $sortOrder);
    }
}
