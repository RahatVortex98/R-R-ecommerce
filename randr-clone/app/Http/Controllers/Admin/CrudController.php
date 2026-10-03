<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Banner, Brand, Category, Coupon, Page, Product, Testimonial};
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CrudController extends Controller
{
    private function f($type, $rules = null, $options = null, $default = null) { return compact('type', 'rules', 'options', 'default'); }

    private function cfg(string $r): array
    {
        $cats = fn () => Category::orderBy('name')->pluck('name', 'id');
        $brands = fn () => Brand::orderBy('name')->pluck('name', 'id');
        $all = [
            'products' => ['model' => Product::class, 'title' => 'Products', 'slug' => 'name', 'cols' => ['name', 'price', 'sale_price', 'stock', 'is_active'], 'fields' => [
                'name' => $this->f('text', 'required|max:255'),
                'category_id' => $this->f('select', 'required|exists:categories,id', $cats),
                'brand_id' => $this->f('select', 'nullable|exists:brands,id', $brands),
                'price' => $this->f('number', 'required|integer|min:0'),
                'sale_price' => $this->f('number', 'nullable|integer|min:0'),
                'stock' => $this->f('number', 'required|integer|min:0'),
                'sku' => $this->f('text', 'nullable|max:100'),
                'image' => $this->f('image', 'nullable|image|max:4096'),
                'description' => $this->f('textarea', 'nullable'),
                'is_best_selling' => $this->f('check'), 'is_new' => $this->f('check'), 'is_offer' => $this->f('check'),
                'is_certified' => $this->f('check'), 'is_active' => $this->f('check', null, null, true),
                'flash_ends_at' => $this->f('datetime', 'nullable|date'),
            ]],
            'categories' => ['model' => Category::class, 'title' => 'Categories', 'slug' => 'name', 'cols' => ['name', 'parent_id', 'sort', 'is_featured', 'show_on_home', 'is_active'], 'fields' => [
                'name' => $this->f('text', 'required|max:255'),
                'parent_id' => $this->f('select', 'nullable|exists:categories,id', $cats),
                'image' => $this->f('image', 'nullable|image|max:4096'),
                'sort' => $this->f('number', 'nullable|integer', null, 0),
                'is_featured' => $this->f('check'), 'show_on_home' => $this->f('check'), 'is_active' => $this->f('check', null, null, true),
            ]],
            'brands' => ['model' => Brand::class, 'title' => 'Brands', 'slug' => 'name', 'cols' => ['name', 'is_active'], 'fields' => [
                'name' => $this->f('text', 'required|max:255'),
                'logo' => $this->f('image', 'nullable|image|max:4096'),
                'is_active' => $this->f('check', null, null, true),
            ]],
            'banners' => ['model' => Banner::class, 'title' => 'Banners', 'cols' => ['position', 'link', 'sort', 'is_active'], 'fields' => [
                'position' => $this->f('select', 'required', ['hero' => 'Hero slider (2000x800)', 'promo' => 'Side promo (1000x400)']),
                'image' => $this->f('image', 'nullable|image|max:6144'),
                'link' => $this->f('text', 'nullable|max:255'),
                'sort' => $this->f('number', 'nullable|integer', null, 0),
                'is_active' => $this->f('check', null, null, true),
            ]],
            'testimonials' => ['model' => Testimonial::class, 'title' => 'Testimonials', 'cols' => ['name', 'role', 'is_active'], 'fields' => [
                'name' => $this->f('text', 'required|max:120'), 'role' => $this->f('text', 'nullable|max:120'),
                'body' => $this->f('textarea', 'required'), 'photo' => $this->f('image', 'nullable|image|max:2048'),
                'is_active' => $this->f('check', null, null, true),
            ]],
            'pages' => ['model' => Page::class, 'title' => 'Pages', 'slug' => 'title', 'cols' => ['title', 'slug', 'section', 'is_active'], 'fields' => [
                'title' => $this->f('text', 'required|max:255'),
                'section' => $this->f('select', 'required', ['information' => 'Information', 'support' => 'Support', 'policy' => 'Consumer Policy']),
                'body' => $this->f('textarea', 'nullable'), 'is_active' => $this->f('check', null, null, true),
            ]],
            'coupons' => ['model' => Coupon::class, 'title' => 'Coupons', 'cols' => ['code', 'type', 'value', 'used', 'is_active'], 'fields' => [
                'code' => $this->f('text', 'required|max:50|unique:coupons,code,{id}'),
                'type' => $this->f('select', 'required', ['percent' => 'Percent (%)', 'fixed' => 'Fixed (৳)']),
                'value' => $this->f('number', 'required|integer|min:1'),
                'min_order' => $this->f('number', 'nullable|integer|min:0', null, 0),
                'max_uses' => $this->f('number', 'nullable|integer|min:1'),
                'expires_at' => $this->f('datetime', 'nullable|date'),
                'is_active' => $this->f('check', null, null, true),
            ]],
        ];
        abort_unless(isset($all[$r]), 404);
        return $all[$r] + ['resource' => $r];
    }

    private function data(Request $req, array $c, $model = null): array
    {
        $rules = collect($c['fields'])->map(fn ($f) => str_replace('{id}', $model->id ?? 'NULL', $f['rules'] ?: 'nullable'))->all();
        $data = $req->validate($rules);
        foreach ($c['fields'] as $k => $f) {
            if ($f['type'] === 'check') $data[$k] = $req->boolean($k);
            elseif ($f['type'] === 'image') {
                if ($req->hasFile($k)) $data[$k] = $req->file($k)->store('uploads', 'public'); else unset($data[$k]);
            } elseif (($data[$k] ?? null) === null && $f['default'] !== null) $data[$k] = $f['default'];
        }
        if (!$model && isset($c['slug'])) {
            $base = Str::slug($data[$c['slug']]) ?: Str::random(8); $slug = $base; $n = 1;
            while ($c['model']::where('slug', $slug)->exists()) $slug = $base.'-'.$n++;
            $data['slug'] = $slug;
        }
        if (isset($data['code'])) $data['code'] = strtoupper($data['code']);
        return $data;
    }

    public function index(string $resource)
    {
        $c = $this->cfg($resource);
        return view('admin.crud.index', ['cfg' => $c, 'resource' => $resource, 'rows' => $c['model']::latest('id')->paginate(20)]);
    }
    public function create(string $resource) { return view('admin.crud.form', ['cfg' => $this->cfg($resource), 'resource' => $resource, 'model' => null]); }
    public function store(Request $req, string $resource)
    {
        $c = $this->cfg($resource);
        $c['model']::create($this->data($req, $c));
        return redirect()->route('admin.crud.index', $resource)->with('ok', 'Saved.');
    }
    public function edit(string $resource, int $id)
    {
        $c = $this->cfg($resource);
        return view('admin.crud.form', ['cfg' => $c, 'resource' => $resource, 'model' => $c['model']::findOrFail($id)]);
    }
    public function update(Request $req, string $resource, int $id)
    {
        $c = $this->cfg($resource); $m = $c['model']::findOrFail($id);
        $m->update($this->data($req, $c, $m));
        return redirect()->route('admin.crud.index', $resource)->with('ok', 'Updated.');
    }
    public function destroy(string $resource, int $id)
    {
        $c = $this->cfg($resource);
        try { $c['model']::findOrFail($id)->delete(); }
        catch (QueryException) { return back()->with('error', 'Cannot delete: it is still in use.'); }
        return back()->with('ok', 'Deleted.');
    }
}