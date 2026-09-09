<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - Mahogany Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#F9F6F0] text-stone-800 antialiased min-h-screen p-6">

    <div class="max-w-7xl mx-auto space-y-6">
        
        <!-- Header Laporan -->
        <div class="flex justify-between items-center bg-gradient-to-r from-[#3D1E0B] via-[#4A2511] to-[#5C2E15] text-white p-6 rounded-3xl shadow-xl">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-[#E6C594]/20 border border-[#E6C594]/30 rounded-2xl flex items-center justify-center text-[#E6C594]">
                    <i data-lucide="bar-chart-3" class="w-6 h-6"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-tight text-white">LAPORAN PENJUALAN</h1>
                    <p class="text-xs text-[#E6C594]">Mahogany Cafe Management</p>
                </div>
            </div>
            <a href="{{ route('pos.index') }}" class="flex items-center gap-2 bg-[#E6C594] text-[#3D1E0B] hover:bg-[#F3DAB1] px-5 py-2.5 rounded-2xl text-sm font-bold transition duration-300">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Kembali ke Kasir</span>
            </a>
        </div>

        <!-- Ringkasan Pendapatan -->
        <div class="bg-white p-6 rounded-3xl border border-stone-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-stone-400 uppercase tracking-wider">Total Pendapatan</span>
                <h2 class="text-3xl font-black text-[#4A2511] mt-1">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </h2>
            </div>
            <div class="w-12 h-12 bg-[#FAF4EC] rounded-2xl flex items-center justify-center text-[#8C4A27]">
                <i data-lucide="wallet" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Form Filter Tanggal -->
        <form method="GET" action="{{ route('reports.index') }}" class="bg-white p-5 rounded-3xl border border-stone-100 shadow-sm flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-2">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" 
                       class="px-4 py-2.5 rounded-xl border border-stone-200 text-sm font-semibold text-stone-700 focus:outline-none focus:ring-2 focus:ring-[#8C4A27]">
            </div>
            <div>
                <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-2">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" 
                       class="px-4 py-2.5 rounded-xl border border-stone-200 text-sm font-semibold text-stone-700 focus:outline-none focus:ring-2 focus:ring-[#8C4A27]">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-[#4A2511] text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-[#3D1E0B] transition flex items-center gap-2">
                    <i data-lucide="filter" class="w-4 h-4"></i>
                    <span>Filter</span>
                </button>
                <a href="{{ route('reports.index') }}" class="bg-stone-100 text-stone-600 px-4 py-2.5 rounded-xl text-sm font-bold hover:bg-stone-200 transition flex items-center gap-2">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                    <span>Reset</span>
                </a>
            </div>
        </form>

        <!-- Tabel Riwayat Transaksi -->
        <div class="bg-white rounded-3xl border border-stone-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-stone-100">
                <h3 class="font-bold text-stone-800 text-lg">Riwayat Transaksi</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-stone-600">
                    <thead class="bg-[#FAF4EC] text-[#4A2511] font-bold text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">No. Faktur</th>
                            <th class="px-6 py-4">Tanggal & Waktu</th>
                            <th class="px-6 py-4">Detail Menu</th>
                            <th class="px-6 py-4">Bayar</th>
                            <th class="px-6 py-4">Kembali</th>
                            <th class="px-6 py-4 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse($transactions as $trx)
                            <tr class="hover:bg-stone-50 transition">
                                <td class="px-6 py-4 font-bold text-stone-800">{{ $trx->invoice_number }}</td>
                                <td class="px-6 py-4 text-xs font-medium text-stone-500">
                                    {{ $trx->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <ul class="space-y-1">
                                        @foreach($trx->details as $detail)
                                            <li class="text-xs">
                                                <span class="font-semibold text-stone-800">{{ $detail->product->name ?? 'Produk Dihapus' }}</span> 
                                                <span class="text-stone-400">x{{ $detail->qty }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold">Rp {{ number_format($trx->pay_amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-xs font-semibold">Rp {{ number_format($trx->change_amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right font-black text-[#4A2511]">
                                    Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-stone-400">
                                    Belum ada transaksi pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>