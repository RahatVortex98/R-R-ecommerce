<?php
namespace App\Http\Controllers;
use App\Models\{Banner, Brand, Category, Product, Testimonial};

class HomeController extends Controller
{
    public function index()
    {
        $banners = fn ($pos) => Banner::where('is_active', true)->where('position', $pos)->orderBy('sort')->get();
        $flash = Product::active()->where('flash_ends_at', '>', now())->orderBy('flash_ends_at')->take(10)->get();
        return view('home', [
            'hero' => $banners('hero'), 'promo' => $banners('promo'),
            'featured' => Category::where('is_featured', true)->where('is_active', true)->orderBy('sort')->get(),
            'top' => Product::active()->where('is_best_selling', true)->latest()->take(10)->get(),
            'flash' => $flash, 'flashEnds' => $flash->first()?->flash_ends_at,
            'brands' => Brand::where('is_active', true)->get(),
            'sections' => Category::where('show_on_home', true)->where('is_active', true)->orderBy('sort')->get()
                ->map(fn ($c) => ['cat' => $c, 'products' => Product::active()->whereIn('category_id', $c->familyIds())->latest()->take(10)->get()]),
            'certified' => Product::active()->where('is_certified', true)->take(10)->get(),
            'more' => Product::active()->latest()->paginate(10)->withPath(route('load.more')),
            'testimonials' => Testimonial::where('is_active', true)->latest()->take(6)->get(),
        ]);
    }
}