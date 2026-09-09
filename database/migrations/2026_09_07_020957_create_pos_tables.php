<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambahkan kolom role pada tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'kasir'])->default('kasir')->after('password');
        });

        // Tabel Kategori
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Tabel Produk / Menu
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('price', 12, 2);
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // Tabel Transaksi Header
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total_amount', 12, 2);
            $table->decimal('pay_amount', 12, 2);
            $table->decimal('change_amount', 12, 2);
            $table->timestamps();
        });

        // Tabel Detail Transaksi
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('qty');
            $table->decimal('price', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};