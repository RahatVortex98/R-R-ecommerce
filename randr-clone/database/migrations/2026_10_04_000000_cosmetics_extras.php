<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $t) {
            $t->string('country_of_origin')->nullable()->after('brand_id');
            $t->boolean('is_authentic')->default(true)->after('is_certified');
        });
        Schema::create('product_variants', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->string('name'); // e.g. "Shade: Rose Pink" or "30ml"
            $t->string('sku')->nullable();
            $t->unsignedInteger('price')->nullable();
            $t->unsignedInteger('sale_price')->nullable();
            $t->unsignedInteger('stock')->default(0);
            $t->unsignedInteger('sort')->default(0);
            $t->timestamps();
        });
        Schema::table('order_items', function (Blueprint $t) {
            $t->foreignId('variant_id')->nullable()->after('product_id')->constrained('product_variants')->nullOnDelete();
            $t->string('variant_name')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $t) { $t->dropConstrainedForeignId('variant_id'); $t->dropColumn('variant_name'); });
        Schema::dropIfExists('product_variants');
        Schema::table('products', fn (Blueprint $t) => $t->dropColumn(['country_of_origin', 'is_authentic']));
    }
};