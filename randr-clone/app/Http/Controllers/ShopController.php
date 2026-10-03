<?php
namespace App\Http\Controllers;
use App\Models\{Brand, Category, Product};
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $r)
    {
        return $this->list(Product::active()->when($r->q, fn ($q, $s) => $q->where('name', 'like', "%$s%")), $r->q ? "Search: {$r->q}" : 'All Products', $r);
    }
    public function collection(Category $category, Request $r)
    {
        abort_unless($category->is_active, 404);
        return $this->list(Product::active()->whereIn('category_id', $category->familyIds()), $category->name, $r, $category);
    }
    public function brand(Brand $brand, Request $r) { return $this->list(Product::active()->where('brand_id', $brand->id), $brand->name, $r); }
    public function offer(Request $r)
    {
        return $this->list(Product::active()->where(fn ($q) => $q->where('is_offer', true)->orWhereColumn('sale_price', '<', 'price')), 'Offer Zone', $r);
    }
    public function flash(Request $r) { return $this->list(Product::active()->where('flash_ends_at', '>', now()), 'Flash Sale', $r); }
    public function brands() { return view('brands', ['brands' => Brand::where('is_active', true)->get()]); }

    private function list($query, string $title, Request $r, ?Category $category = null)
    {
        $query = match ($r->sort) {
            'price_asc' => $query->orderBy('price'), 'price_desc' => $query->orderByDesc('price'),
            'popular' => $query->orderByDesc('sold_count'), default => $query->latest(),
        };
        return view('shop', ['products' => $query->paginate(12)->withQueryString(), 'title' => $title, 'category' => $category]);
    }
}