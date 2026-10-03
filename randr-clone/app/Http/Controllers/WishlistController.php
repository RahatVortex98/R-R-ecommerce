<?php
namespace App\Http\Controllers;
use App\Models\Product;

class WishlistController extends Controller
{
    public function index() { return view('wishlist', ['products' => auth()->user()->wishlist()->get()]); }
    public function toggle(Product $product) { auth()->user()->wishlist()->toggle($product->id); return back(); }
}