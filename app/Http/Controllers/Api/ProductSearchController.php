<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchProductsRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductSearchService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductSearchController extends Controller
{
    public function __construct(
        private readonly ProductSearchService $productSearchService
    ) {
    }

    public function __invoke(SearchProductsRequest $request): AnonymousResourceCollection {
        $products = $this->productSearchService->search(
            $request->validated()
        );

        return ProductResource::collection($products);
    }
}
