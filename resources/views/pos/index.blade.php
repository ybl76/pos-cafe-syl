<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir POS - Mahogany Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#F9F6F0] text-stone-800 antialiased min-h-screen">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sisi Kiri: Menu & Produk -->
        <div class="flex-1 flex flex-col h-full overflow-y-auto p-6">
            
            <!-- Header Kasir -->
            <div class="flex justify-between items-center mb-6 bg-gradient-to-r from-[#3D1E0B] via-[#4A2511] to-[#5C2E15] text-white p-6 rounded-3xl shadow-xl">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-[#E6C594]/20 border border-[#E6C594]/30 rounded-2xl flex items-center justify-center text-[#E6C594]">
                        <i data-lucide="coffee" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-white">MAHOGANY CAFE</h1>
                        <p class="text-xs text-[#E6C594]">
                            Kasir: <span class="font-semibold text-white">{{ auth()->user()->name ?? 'Guest' }}</span> 
                            <span class="ml-1 px-2 py-0.5 rounded-full text-[10px] bg-[#E6C594] text-[#3D1E0B] uppercase font-bold">{{ auth()->user()->role ?? 'kasir' }}</span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    {{-- Tombol Laporan Penjualan hanya muncul jika user berpangkat Admin --}}
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <a href="{{ route('reports.index') }}" class="flex items-center gap-2 bg-[#E6C594] text-[#3D1E0B] hover:bg-[#F3DAB1] px-5 py-2.5 rounded-2xl text-sm font-bold transition duration-300 shadow-sm">
                            <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                            <span>Laporan Penjualan</span>
                        </a>
                    @endif

                    <!-- Form Logout -->
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="p-2.5 bg-red-500/20 text-red-200 hover:bg-red-500 hover:text-white rounded-2xl transition duration-200" title="Keluar">
                            <i data-lucide="log-out" class="w-5 h-5"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Pesan Notifikasi/Error -->
            @if(session('error'))
                <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-2xl mb-4 text-sm font-semibold flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-5 h-5"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Filter Kategori -->
            <div class="flex items-center gap-3 overflow-x-auto pb-4 mb-2 no-scrollbar">
                <a href="{{ route('pos.index') }}" 
                   class="px-5 py-2.5 rounded-2xl text-sm font-bold transition duration-200 whitespace-nowrap {{ !request('category_id') ? 'bg-[#4A2511] text-white shadow-md' : 'bg-white text-stone-600 hover:bg-stone-100' }}">
                   Semua Menu
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('pos.index', ['category_id' => $cat->id]) }}" 
                       class="px-5 py-2.5 rounded-2xl text-sm font-bold transition duration-200 whitespace-nowrap {{ request('category_id') == $cat->id ? 'bg-[#4A2511] text-white shadow-md' : 'bg-white text-stone-600 hover:bg-stone-100' }}">
                       {{ $cat->name }}
                    </a>
                @endforeach
            </div>

            <!-- Grid Produk -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 overflow-y-auto pr-2">
                @forelse($products as $product)
                    <div data-id="{{ $product->id }}" 
                         data-name="{{ $product->name }}" 
                         data-price="{{ $product->price }}"
                         class="product-card bg-white p-4 rounded-3xl border border-stone-100 hover:border-[#8C4A27] shadow-sm hover:shadow-md transition cursor-pointer flex flex-col justify-between group">
                        <div>
                            <span class="text-[10px] font-bold text-[#8C4A27] uppercase tracking-wider bg-[#FAF4EC] px-2.5 py-1 rounded-lg">
                                {{ $product->category->name ?? 'Umum' }}
                            </span>
                            <h3 class="font-bold text-stone-800 mt-3 group-hover:text-[#4A2511] transition">{{ $product->name }}</h3>
                        </div>
                        <div class="mt-4 flex justify-between items-center">
                            <span class="font-black text-[#4A2511]">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            <div class="w-8 h-8 rounded-xl bg-stone-100 group-hover:bg-[#4A2511] group-hover:text-white flex items-center justify-center transition">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 text-stone-400 font-medium">
                        Belum ada menu di kategori ini.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Sisi Kanan: Panel Keranjang & Pembayaran -->
        <div class="w-96 bg-white border-l border-stone-200 flex flex-col h-full shadow-lg">
            
            <div class="p-6 border-b border-stone-100 flex items-center justify-between">
                <div class="flex items-center gap-2 text-[#3D1E0B]">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    <h2 class="font-bold text-lg">Pesanan Saat Ini</h2>
                </div>
                <button onclick="clearCart()" class="text-xs font-semibold text-red-500 hover:text-red-700">Kosongkan</button>
            </div>

            <!-- Daftar Item di Keranjang -->
            <div id="cart-items" class="flex-1 overflow-y-auto p-6 space-y-4">
                <div class="text-center text-stone-400 py-12 text-sm">Keranjang masih kosong</div>
            </div>

            <!-- Ringkasan Pembayaran & Form Submit -->
            <div class="p-6 border-t border-stone-100 bg-[#FAF4EC]/50 space-y-4">
                <div class="space-y-2">
                    <div class="flex justify-between text-sm text-stone-500">
                        <span>Total Tagihan</span>
                        <span id="total-price-text" class="font-bold text-[#4A2511] text-lg">Rp 0</span>
                    </div>
                </div>

                <!-- Form Pembayaran -->
                <form action="{{ route('pos.store') }}" method="POST" id="checkout-form" class="space-y-4">
                    @csrf
                    <!-- Hidden Input Cart JSON -->
                    <input type="hidden" name="cart" id="cart-json-input">

                    <div>
                        <label for="pay_amount" class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-2">Uang Diterima (Rp)</label>
                        <input type="number" name="pay_amount" id="pay_amount" required min="0" placeholder="Masukkan jumlah uang"
                               class="w-full px-4 py-3 rounded-2xl border border-stone-200 focus:outline-none focus:ring-2 focus:ring-[#8C4A27] text-stone-800 font-bold">
                    </div>

                    <button type="submit" id="btn-submit" disabled 
                            class="w-full bg-[#4A2511] text-white py-3.5 rounded-2xl font-bold hover:bg-[#3D1E0B] transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                        <span>Bayar & Cetak Struk</span>
                    </button>
                </form>
            </div>

        </div>

    </div>

    <script>
        lucide.createIcons();

        let cart = [];

        // Event listener klik kartu produk
        document.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('click', function() {
                const id = parseInt(this.dataset.id);
                const name = this.dataset.name;
                const price = parseFloat(this.dataset.price);
                
                addToCart(id, name, price);
            });
        });

        function addToCart(id, name, price) {
            const existingItem = cart.find(item => item.id === id);
            if (existingItem) {
                existingItem.qty += 1;
                existingItem.subtotal = existingItem.qty * existingItem.price;
            } else {
                cart.push({
                    id: id,
                    name: name,
                    price: price,
                    qty: 1,
                    subtotal: price
                });
            }
            updateCartUI();
        }

        function updateQty(id, change) {
            const item = cart.find(item => item.id === id);
            if (item) {
                item.qty += change;
                if (item.qty <= 0) {
                    cart = cart.filter(i => i.id !== id);
                } else {
                    item.subtotal = item.qty * item.price;
                }
            }
            updateCartUI();
        }

        function clearCart() {
            cart = [];
            updateCartUI();
        }

        function updateCartUI() {
            const container = document.getElementById('cart-items');
            const totalText = document.getElementById('total-price-text');
            const jsonInput = document.getElementById('cart-json-input');
            const submitBtn = document.getElementById('btn-submit');

            if (cart.length === 0) {
                container.innerHTML = '<div class="text-center text-stone-400 py-12 text-sm">Keranjang masih kosong</div>';
                totalText.innerText = 'Rp 0';
                jsonInput.value = '';
                submitBtn.disabled = true;
                return;
            }

            let html = '';
            let total = 0;

            cart.forEach(item => {
                total += item.subtotal;
                html += `
                    <div class="flex items-center justify-between bg-white p-3.5 rounded-2xl border border-stone-100 shadow-sm">
                        <div class="flex-1 pr-2">
                            <h4 class="font-bold text-sm text-stone-800">${item.name}</h4>
                            <p class="text-xs text-stone-500">Rp ${item.price.toLocaleString('id-ID')} x ${item.qty}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="updateQty(${item.id}, -1)" class="w-7 h-7 bg-stone-100 rounded-lg flex items-center justify-center text-stone-600 font-bold hover:bg-stone-200">-</button>
                            <span class="text-xs font-bold w-4 text-center">${item.qty}</span>
                            <button type="button" onclick="updateQty(${item.id}, 1)" class="w-7 h-7 bg-stone-100 rounded-lg flex items-center justify-center text-stone-600 font-bold hover:bg-stone-200">+</button>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
            totalText.innerText = 'Rp ' + total.toLocaleString('id-ID');
            jsonInput.value = JSON.stringify(cart);
            submitBtn.disabled = false;
        }

        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            document.getElementById('cart-json-input').value = JSON.stringify(cart);
        });
    </script>

</body>
</html>