<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Order, Product};

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'stats' => [
                'Orders today' => Order::whereDate('created_at', today())->count(),
                'Pending orders' => Order::where('status', 'pending')->count(),
                'Revenue (delivered)' => '৳'.number_format(Order::where('status', 'delivered')->sum('total')),
                'Products' => Product::count(),
                'Low stock (<5)' => Product::where('stock', '<', 5)->count(),
            ],
            'latest' => Order::latest()->take(8)->get(),
        ]);
    }
}