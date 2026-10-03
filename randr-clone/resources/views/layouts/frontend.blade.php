<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ghorer Bazar - Safe Food, Healthy Life</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-[#f9f9f9]">
    <!-- Top Bar -->
    <div class="bg-[#6a9732] text-white text-sm py-2 text-center">Safe Food, Healthy Life | Call: 09613-525310</div>
    
    <!-- Header -->
    <header class="bg-white shadow sticky top-0 z-50">
        <div class="container mx-auto flex items-center justify-between py-4 px-4">
            <a href="/" class="text-2xl font-bold text-[#6a9732]">GHORER<span class="text-black">BAZAR</span></a>
            <div class="flex-1 mx-8 hidden md:block">
                <form action="/search" class="flex">
                    <input type="text" placeholder="Search for products..." class="w-full border border-gray-300 rounded-l-full px-4 py-2 focus:outline-none">
                    <button class="bg-[#6a9732] text-white px-6 rounded-r-full"><i class="fa fa-search"></i></button>
                </form>
            </div>
            <div class="flex items-center gap-6">
                <a href="/cart" class="relative"><i class="fa fa-shopping-cart text-xl"></i> <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{{ $cartCount ?? 0 }}</span> Cart</a>
                <a href="/login" class="bg-[#6a9732] text-white px-5 py-2 rounded-full">Login</a>
            </div>
        </div>
        <!-- Category Nav -->
        <nav class="bg-[#f1f8e9] border-t">
            <div class="container mx-auto flex gap-6 px-4 py-3 overflow-x-auto text-sm font-medium">
                <a href="#">Ghee</a><a href="#">Honey</a><a href="#">Dates</a><a href="#">Mustard Oil</a><a href="#">Tea</a><a href="#">Nuts & Seeds</a><a href="#">Spices</a>
            </div>
        </nav>
    </header>

    <main></main>

    <footer class="bg-[#222] text-gray-300 py-10 mt-10">
        <div class="container mx-auto grid md:grid-cols-4 gap-8 px-4">
            <div><h3 class="text-white font-bold mb-3">GHORER BAZAR</h3><p>We deliver 100% pure and organic food across Bangladesh.</p></div>
            <div><h4 class="font-bold text-white">Contact</h4><p>House 18, Road 1, Dhaka</p><p>09613-525310</p></div>
            <div><h4 class="font-bold text-white">Information</h4><p>About Us | Delivery Policy | Return Policy</p></div>
            <div><h4 class="font-bold text-white">Payment</h4><p>Cash On Delivery, bKash, SSLCommerz</p></div>
        </div>
    </footer>
</body>
</html>
