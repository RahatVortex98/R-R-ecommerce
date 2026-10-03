<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            if (!Schema::hasColumn('users', 'is_admin')) {
                $t->boolean('is_admin')->default(false);
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $t->string('phone')->nullable();
            }
            if (!Schema::hasColumn('users', 'address')) {
                $t->text('address')->nullable();
            }
        });
        Schema::create('categories', function (Blueprint $t) {
            $t->id();
            $t->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $t->string('name'); $t->string('slug')->unique(); $t->string('image')->nullable();
            $t->boolean('is_featured')->default(false);
            $t->boolean('show_on_home')->default(false);
            $t->boolean('is_active')->default(true);
            $t->unsignedInteger('sort')->default(0);
            $t->timestamps();
        });
        Schema::create('brands', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->string('slug')->unique(); $t->string('logo')->nullable();
            $t->boolean('is_active')->default(true); $t->timestamps();
        });
        Schema::create('products', function (Blueprint $t) {
            $t->id();
            $t->foreignId('category_id')->constrained();
            $t->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $t->string('name'); $t->string('slug')->unique(); $t->string('sku')->nullable();
            $t->text('description')->nullable();
            $t->unsignedInteger('price'); $t->unsignedInteger('sale_price')->nullable();
            $t->unsignedInteger('stock')->default(0); $t->unsignedInteger('sold_count')->default(0);
            $t->string('image')->nullable();
            $t->boolean('is_best_selling')->default(false);
            $t->boolean('is_new')->default(false);
            $t->boolean('is_offer')->default(false);
            $t->boolean('is_certified')->default(false);
            $t->boolean('is_active')->default(true);
            $t->timestamp('flash_ends_at')->nullable();
            $t->timestamps();
        });
        Schema::create('banners', function (Blueprint $t) {
            $t->id(); $t->string('position')->default('hero'); $t->string('image')->nullable();
            $t->string('link')->nullable(); $t->unsignedInteger('sort')->default(0);
            $t->boolean('is_active')->default(true); $t->timestamps();
        });
        Schema::create('testimonials', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->string('role')->nullable(); $t->text('body');
            $t->string('photo')->nullable(); $t->boolean('is_active')->default(true); $t->timestamps();
        });
        Schema::create('pages', function (Blueprint $t) {
            $t->id(); $t->string('title'); $t->string('slug')->unique();
            $t->string('section')->default('information'); $t->longText('body')->nullable();
            $t->boolean('is_active')->default(true); $t->timestamps();
        });
        Schema::create('coupons', function (Blueprint $t) {
            $t->id(); $t->string('code')->unique(); $t->string('type')->default('percent');
            $t->unsignedInteger('value'); $t->unsignedInteger('min_order')->default(0);
            $t->unsignedInteger('max_uses')->nullable(); $t->unsignedInteger('used')->default(0);
            $t->timestamp('expires_at')->nullable(); $t->boolean('is_active')->default(true); $t->timestamps();
        });
        Schema::create('orders', function (Blueprint $t) {
            $t->id(); $t->string('order_no')->unique();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->string('name'); $t->string('phone'); $t->string('email')->nullable(); $t->text('address');
            $t->string('area')->default('inside_dhaka');
            $t->unsignedInteger('subtotal'); $t->unsignedInteger('shipping')->default(0);
            $t->unsignedInteger('discount')->default(0); $t->unsignedInteger('total');
            $t->string('coupon_code')->nullable();
            $t->string('payment_method')->default('cod'); $t->string('trx_id')->nullable();
            $t->string('payment_status')->default('unpaid'); $t->string('status')->default('pending');
            $t->text('note')->nullable(); $t->timestamps();
        });
        Schema::create('order_items', function (Blueprint $t) {
            $t->id(); $t->foreignId('order_id')->constrained()->cascadeOnDelete();
            $t->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $t->string('name'); $t->unsignedInteger('price'); $t->unsignedInteger('qty');
        });
        Schema::create('wishlists', function (Blueprint $t) {
            $t->id(); $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->unique(['user_id','product_id']); $t->timestamps();
        });
        Schema::create('settings', function (Blueprint $t) {
            $t->id(); $t->string('key')->unique(); $t->text('value')->nullable();
        });
    }

    public function down(): void
    {
        foreach (['settings','wishlists','order_items','orders','coupons','pages','testimonials','banners','products','brands','categories'] as $tb) Schema::dropIfExists($tb);
        
        Schema::table('users', function (Blueprint $t) {
            $columnsToDrop = array_filter(['is_admin', 'phone', 'address'], fn($col) => Schema::hasColumn('users', $col));
            if (!empty($columnsToDrop)) {
                $t->dropColumn($columnsToDrop);
            }
        });
    }
};
