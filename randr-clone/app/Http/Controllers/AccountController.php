<?php
namespace App\Http\Controllers;
use App\Models\Order;

class AccountController extends Controller
{
    public function orders() { return view('account-orders', ['orders' => Order::with('items')->where('user_id', auth()->id())->latest()->paginate(10)]); }
}