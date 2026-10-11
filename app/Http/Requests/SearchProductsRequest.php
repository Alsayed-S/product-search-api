<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class SearchProductsRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Public search endpoint. Add authorization if required by the product.
        return true;
    }

    protected function prepareForValidation(): void
    {
        $data = [];
    
        if (is_string($this->input('q'))) {
            $query = trim($this->input('q'));
    
            $data['q'] = $query === '' ? null : $query;
        }
    
        if ($this->has('in_stock')) {
            $value = $this->input('in_stock');
    
            if (in_array($value, ['true', '1', 1, true], true)) {
                $data['in_stock'] = true;
            } elseif (in_array($value, ['false', '0', 0, false], true)) {
                $data['in_stock'] = false;
            }
        }
    
        if ($data !== []) {
            $this->merge($data);
        }
    }

    public function rules(): array
    {
        return [
            'q' => ['sometimes', 'nullable', 'string', 'max:100'],

            'min_price' => ['sometimes', 'numeric', 'min:0'],
            'max_price' => ['sometimes', 'numeric', 'min:0'],

            'category_id' => [
                'sometimes',
                'integer',
                'min:1',
                'exists:categories,id',
            ],

            'in_stock' => [
                'sometimes',
                'in:1,0,true,false',
            ],
            'sort_by' => [
                'sometimes',
                'string',
                'in:price,created_at',
            ],

            'sort_order' => [
                'sometimes',
                'string',
                'in:asc,desc',
            ],

            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:50'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $minPrice = $this->input('min_price');
                $maxPrice = $this->input('max_price');

                if (
                    is_numeric($minPrice)
                    && is_numeric($maxPrice)
                    && (float) $minPrice > (float) $maxPrice
                ) {
                    $validator->errors()->add(
                        'max_price',
                        'The maximum price must be greater than or equal to the minimum price.'
                    );
                }
            },
        ];
    }
}
