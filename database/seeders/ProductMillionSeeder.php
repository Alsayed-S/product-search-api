<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ProductMillionSeeder extends Seeder
{
    public function run(): void
    {
        // Prevent accidentally seeding the test database.
        $database = DB::connection()->getDatabaseName();

        if ($database !== 'product_search') {
            throw new RuntimeException(
                "Expected database 'product_search', got '{$database}'."
            );
        }

        $categoryIds = DB::table('categories')
            ->orderBy('id')
            ->pluck('id')
            ->all();

        if (empty($categoryIds)) {
            $categoryIds = [
                DB::table('categories')->insertGetId([
                    'name' => 'Performance Test',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]),
            ];
        }

        $total = 1_000_000;
        $batchSize = 1_000;
        $now = now();

        for ($offset = 0; $offset < $total; $offset += $batchSize) {
            $rows = [];

            $end = min($offset + $batchSize, $total);

            for ($i = $offset; $i < $end; $i++) {
                $number = $i + 1;

                $rows[] = [
                    'category_id' => $categoryIds[$i % count($categoryIds)],
                    'name' => "Performance Product {$number}",
                    'description' =>
                        "Performance test product number {$number} " .
                        'with searchable electronics accessories and devices',
                    'price' => number_format(
                        (($number % 100000) + 1) / 10,
                        2,
                        '.',
                        ''
                    ),
                    'stock_quantity' => $number % 101,
                    'created_at' => $now->copy()->subDays($number % 365),
                    'updated_at' => $now,
                ];
            }

            DB::table('products')->insert($rows);

            $inserted = $end;

            if ($inserted % 100_000 === 0) {
                $this->command?->info(
                    "Inserted {$inserted} of {$total} products."
                );
            }
        }

        $this->command?->info(
            'Successfully inserted 1,000,000 performance test products.'
        );
    }
}
