<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Product, ProductVariant};
use Illuminate\Http\Request;

class VariantController extends Controller
{
    private array $rules = ['name' => 'required|max:120', 'sku' => 'nullable|max:100', 'price' => 'nullable|integer|min:0', 'sale_price' => 'nullable|integer|min:0', 'stock' => 'required|integer|min:0'];
    public function store(Request $r, Product $product)
    {
        $product->variants()->create($r->validate($this->rules));
        return back()->with('ok', 'Variant added.');
    }
    public function update(Request $r, ProductVariant $variant)
    {
        $variant->update($r->validate($this->rules));
        return back()->with('ok', 'Variant updated.');
    }
    public function destroy(ProductVariant $variant) { $variant->delete(); return back()->with('ok', 'Variant deleted.'); }
}