<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran - {{ $transaction->invoice_number }}</title>
    <style>
        body { font-family: monospace; width: 300px; padding: 10px; margin: auto; }
        .text-center { text-align: center; }
        .line { border-top: 1px dashed #000; margin: 8px 0; }
        .flex { display: flex; justify-content: space-between; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>

    <div class="text-center">
        <h2>MAHOGANY CAFE</h2>
        <p>Jl. Utama Cafe No. 123</p>
        <p>{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
        <p>Invoice: {{ $transaction->invoice_number }}</p>
    </div>

    <div class="line"></div>

    @foreach($transaction->details as $detail)
        <div>
            <div>{{ $detail->product->name ?? 'Item' }}</div>
            <div class="flex">
                <span>{{ $detail->qty }} x {{ number_format($detail->price, 0, ',', '.') }}</span>
                <span>{{ number_format($detail->subtotal, 0, ',', '.') }}</span>
            </div>
        </div>
    @endforeach

    <div class="line"></div>

    <div class="flex">
        <span>Total:</span>
        <strong>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</strong>
    </div>
    <div class="flex">
        <span>Bayar:</span>
        <span>Rp {{ number_format($transaction->pay_amount, 0, ',', '.') }}</span>
    </div>
    <div class="flex">
        <span>Kembali:</span>
        <span>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
    </div>

    <div class="line"></div>
    <p class="text-center">Terima Kasih Atas Kunjungan Anda!</p>

    <div class="no-print" style="margin-top: 15px; text-align: center;">
        <button onclick="window.print()">Cetak Struk</button>
        <a href="{{ route('pos.index') }}">Kembali ke Kasir</a>
    </div>

    <script>
        // Automatis buka dialog print saat halaman dimuat
        window.onload = function() { window.print(); }
    </script>
</body>
</html>