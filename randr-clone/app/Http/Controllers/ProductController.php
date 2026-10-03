<?php
namespace App\Http\Controllers;
use App\Models\Product;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);
        $related = Product::active()->where('category_id', $product->category_id)->where('id', '!=', $product->id)->take(5)->get();
        return view('product', compact('product', 'related'));
    }
    public function loadMore()
    {
        $p = Product::active()->latest()->paginate(10);
        return response()->json(['html' => view('partials.product-cards', ['products' => $p])->render(), 'next' => $p->nextPageUrl()]);
    }
}