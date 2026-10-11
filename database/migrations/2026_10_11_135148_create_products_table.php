
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('name', 255);
            $table->text('description');
            $table->decimal('price', 12, 2);
            $table->unsignedInteger('stock_quantity')->default(0);

            $table->timestamps();

            // Full-text search across product name and description.
            $table->fullText(
                ['name', 'description'],
                'products_name_description_fulltext'
            );

            // Support common price and date ordering patterns.
            $table->index(
                ['price', 'id'],
                'products_price_id_index'
            );

            $table->index(
                ['created_at', 'id'],
                'products_created_id_index'
            );

            // Support category-filtered ordering.
            $table->index(
                ['category_id', 'price', 'id'],
                'products_category_price_id_index'
            );

            $table->index(
                ['category_id', 'created_at', 'id'],
                'products_category_created_id_index'
            );

            $table->index('stock_quantity');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
