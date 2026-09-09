<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mahogany Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#F9F6F0] text-stone-800 antialiased min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-3xl border border-stone-100 shadow-xl p-8 space-y-6">
        
        <div class="text-center space-y-2">
            <div class="w-16 h-16 bg-[#FAF4EC] border border-[#E6C594]/40 rounded-2xl flex items-center justify-center text-[#4A2511] mx-auto">
                <i data-lucide="coffee" class="w-8 h-8"></i>
            </div>
            <h1 class="text-2xl font-black text-[#4A2511]">MAHOGANY CAFE</h1>
            <p class="text-xs text-stone-500 font-medium">Masukkan akun untuk masuk ke sistem POS</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-2xl text-xs font-semibold">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-2">Email</label>
                <input type="email" name="email" id="email" required value="{{ old('email') }}" placeholder="admin@mahogany.com"
                       class="w-full px-4 py-3 rounded-2xl border border-stone-200 text-sm font-semibold text-stone-800 focus:outline-none focus:ring-2 focus:ring-[#8C4A27]">
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-2">Password</label>
                <input type="password" name="password" id="password" required placeholder="••••••••"
                       class="w-full px-4 py-3 rounded-2xl border border-stone-200 text-sm font-semibold text-stone-800 focus:outline-none focus:ring-2 focus:ring-[#8C4A27]">
            </div>

            <button type="submit" class="w-full bg-[#4A2511] text-white py-3.5 rounded-2xl font-bold hover:bg-[#3D1E0B] transition duration-200 flex items-center justify-center gap-2">
                <i data-lucide="log-in" class="w-5 h-5"></i>
                <span>Masuk Sistem</span>
            </button>
        </form>

        <div class="border-t border-stone-100 pt-4 text-center">
            <p class="text-[11px] text-stone-400">Gunakan akun dari database seeder untuk tes login.</p>
        </div>

    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>