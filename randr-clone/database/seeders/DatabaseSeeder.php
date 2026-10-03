<?php
namespace Database\Seeders;
use App\Models\{Brand, Category, Page, Product, Setting, Testimonial, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => 'password', 'is_admin' => true, 'phone' => '01700000000']);

        Setting::put([
            'site_name' => 'Glow Imports', 'tagline' => '100% authentic skincare, makeup & haircare imported from Korea, UK & India.',
            'announcement' => 'Free delivery on orders over ৳3000', 'phone' => '+8801700000000', 'whatsapp' => '8801700000000',
            'email' => 'hello@example.com', 'address' => 'Dhaka, Bangladesh', 'facebook' => '', 'instagram' => '',
            'bkash_number' => '01700000000', 'shipping_inside_dhaka' => '60', 'shipping_outside_dhaka' => '120', 'free_shipping_over' => '3000',
        ]);

        $tree = [
            'Skincare' => ['Cleanser', 'Serum', 'Moisturizer', 'Sunscreen', 'Toner'],
            'Makeup' => ['Lip', 'Face', 'Eye', 'Cheek'],
            'Haircare' => ['Shampoo', 'Hair Oil', 'Hair Mask', 'Styling'],
        ];
        $n = 0; $leaf = [];
        foreach ($tree as $name => $kids) {
            $p = Category::create(['name' => $name, 'slug' => Str::slug($name), 'sort' => $n, 'is_featured' => true, 'show_on_home' => true]);
            $n++;
            foreach ($kids as $k => $kid) $leaf[] = Category::create(['name' => $kid, 'slug' => Str::slug($name.'-'.$kid), 'parent_id' => $p->id, 'sort' => $k]);
        }

        $brandData = [
            'COSRX' => 'South Korea', 'Innisfree' => 'South Korea', 'Beauty of Joseon' => 'South Korea',
            'The Ordinary' => 'United Kingdom', 'Simple' => 'United Kingdom',
            'Lakme' => 'India', 'Mamaearth' => 'India', 'Biotique' => 'India',
        ];
        $brands = collect($brandData)->map(fn ($c, $b) => Brand::create(['name' => $b, 'slug' => Str::slug($b)]))->values();
        $brandNames = array_keys($brandData);

        $i = 0;
        foreach ($leaf as $cat) {
            foreach (['Default', '30ml / Shade A', '50ml / Shade B'] as $variantSet) {
                $i++;
                $brandName = $brandNames[$i % count($brandNames)];
                $country = $brandData[$brandName];
                $price = 400 + ($i * 173) % 2200;
                $product = Product::create([
                    'category_id' => $cat->id, 'brand_id' => $brands->firstWhere('name', $brandName)->id,
                    'name' => "{$brandName} {$cat->name}", 'slug' => Str::slug("{$brandName} {$cat->name}").'-'.$i,
                    'sku' => 'CSM'.str_pad($i, 4, '0', STR_PAD_LEFT),
                    'description' => "Imported {$cat->name} from {$country}. Replace with real product description.",
                    'country_of_origin' => $country, 'is_authentic' => true,
                    'price' => $price, 'sale_price' => $i % 5 === 0 ? (int) ($price * 0.85) : null, 'stock' => 40,
                    'is_best_selling' => $i % 3 === 0, 'is_new' => $i % 4 === 0, 'is_offer' => $i % 7 === 0,
                    'is_certified' => $i % 6 === 0,
                    'flash_ends_at' => $i % 9 === 0 ? now()->addDays(3) : null,
                ]);
                if ($i % 2 === 0) {
                    foreach (['Shade: Nude Rose', 'Shade: Coral Red'] as $vi => $vName)
                        $product->variants()->create(['name' => $vName, 'price' => $price + ($vi * 50), 'stock' => 20, 'sort' => $vi]);
                }
            }
        }

        foreach ([
            ['About Us', 'information'], ['Contact Us', 'information'], ['Terms & Conditions', 'information'], ['Privacy Policy', 'information'],
            ['How to Order', 'support'], ['Payment', 'support'], ['Shipping', 'support'], ['FAQ', 'support'],
            ['Refund Policy', 'policy'], ['Happy Return', 'policy'], ['Cancellation', 'policy'], ['Exchange', 'policy'],
        ] as [$title, $section]) Page::create(['title' => $title, 'slug' => Str::slug($title), 'section' => $section, 'body' => "{$title}\n\nReplace this text from Admin → Pages."]);

        foreach ([['Customer One', 'Student'], ['Customer Two', 'Banker'], ['Customer Three', 'Entrepreneur']] as [$nm, $role])
            Testimonial::create(['name' => $nm, 'role' => $role, 'body' => '100% authentic, fast delivery. Will order again!']);
    }
}