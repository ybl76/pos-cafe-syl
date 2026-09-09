<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class POSController extends Controller
{
    /**
     * Menampilkan halaman kasir POS
     */
    public function index(Request $request)
    {
        $categories = Category::all();
        $query = Product::with('category');

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->get();

        return view('pos.index', compact('categories', 'products'));
    }

    /**
     * Memproses dan menyimpan transaksi pembayaran
     */
    public function store(Request $request)
    {
        // Validasi input dari form POS
        $request->validate([
            'cart' => 'required',
            'pay_amount' => 'required|numeric|min:0',
        ]);

        $cart = json_decode($request->cart, true);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang belanjaan masih kosong!');
        }

        DB::beginTransaction();

        try {
            // Hitung total bayar
            $totalAmount = array_sum(array_column($cart, 'subtotal'));
            $payAmount = (float) $request->pay_amount;

            if ($payAmount < $totalAmount) {
                return redirect()->back()->with('error', 'Uang pembayaran kurang dari total tagihan!');
            }

            // Simpan Data Header Transaksi
            $transaction = Transaction::create([
                'invoice_number' => 'INV-' . date('YmdHis') . '-' . rand(100, 999),
                'user_id'        => Auth::id() ?? 1, // Menggunakan Auth::id() agar tidak error/warning
                'total_amount'   => $totalAmount,
                'pay_amount'     => $payAmount,
                'change_amount'  => $payAmount - $totalAmount,
            ]);

            // Simpan Detail Item Transaksi
            foreach ($cart as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $item['id'],
                    'qty'            => $item['qty'],
                    'price'          => $item['price'],
                    'subtotal'       => $item['subtotal'],
                ]);
            }

            DB::commit();

            // Redirect langsung ke halaman cetak struk
            return redirect()->route('pos.receipt', $transaction->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan struk transaksi untuk dicetak
     */
    public function receipt($id)
    {
        $transaction = Transaction::with('details.product')->findOrFail($id);

        return view('pos.receipt', compact('transaction'));
    }
}