<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Toko Baju Ghani | Laravel & Docker')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#fdf4ff',
                            500: '#d946ef',
                            600: '#c026d3',
                            700: '#a21caf',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        .glass-card { background: rgba(30, 41, 59, 0.75); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.08); }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col antialiased selection:bg-fuchsia-600 selection:text-white">

    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-40 bg-slate-900/90 backdrop-blur-md border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('toko.index') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-fuchsia-600 via-indigo-600 to-cyan-400 p-0.5 shadow-lg shadow-fuchsia-500/20">
                    <div class="w-full h-full bg-slate-950 rounded-[10px] flex items-center justify-center">
                        <i class="fa-solid fa-shirt text-fuchsia-400 text-lg"></i>
                    </div>
                </div>
                <div>
                    <span class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-white via-slate-100 to-fuchsia-400 bg-clip-text text-transparent">
                        TokoBaju-Ghani
                    </span>
                    <span class="ml-2 text-xs font-semibold px-2 py-0.5 rounded-full bg-fuchsia-500/10 text-fuchsia-400 border border-fuchsia-500/20">
                        Laravel 10 + Docker
                    </span>
                </div>
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center gap-1 bg-slate-950/60 p-1 rounded-xl border border-slate-800 text-sm font-semibold">
                <a href="{{ route('toko.index') }}" class="px-4 py-2 rounded-lg transition {{ request()->routeIs('toko.index') ? 'bg-fuchsia-600 text-white shadow-lg shadow-fuchsia-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                    <i class="fa-solid fa-store mr-1.5"></i> Katalog Busana
                </a>
                <a href="{{ route('mahasiswa.index') }}" class="px-4 py-2 rounded-lg transition {{ request()->routeIs('mahasiswa.index') ? 'bg-fuchsia-600 text-white shadow-lg shadow-fuchsia-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                    <i class="fa-solid fa-graduation-cap mr-1.5"></i> Data Mahasiswa
                </a>
                <a href="/health" target="_blank" class="px-4 py-2 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/50 transition">
                    <i class="fa-solid fa-heart-pulse mr-1.5 text-emerald-400"></i> Health Check
                </a>
            </nav>

            <!-- Status Indicator & Cart Trigger -->
            <div class="flex items-center gap-3">
                <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    PostgreSQL Live
                </span>

                <button onclick="toggleCartDrawer()" class="relative p-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 transition">
                    <i class="fa-solid fa-cart-shopping text-base"></i>
                    <span id="cart-badge" class="absolute -top-1 -right-1 bg-fuchsia-600 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center border-2 border-slate-950 hidden">0</span>
                </button>
            </div>
        </div>

        <!-- Mobile Nav Bar -->
        <div class="md:hidden flex items-center justify-around border-t border-slate-800/80 bg-slate-900/60 py-2 text-xs font-semibold">
            <a href="{{ route('toko.index') }}" class="flex items-center gap-1.5 py-1 px-3 rounded-lg {{ request()->routeIs('toko.index') ? 'text-fuchsia-400 bg-fuchsia-500/10' : 'text-slate-400' }}">
                <i class="fa-solid fa-store"></i> Katalog
            </a>
            <a href="{{ route('mahasiswa.index') }}" class="flex items-center gap-1.5 py-1 px-3 rounded-lg {{ request()->routeIs('mahasiswa.index') ? 'text-fuchsia-400 bg-fuchsia-500/10' : 'text-slate-400' }}">
                <i class="fa-solid fa-graduation-cap"></i> Mahasiswa
            </a>
            <a href="/health" target="_blank" class="flex items-center gap-1.5 py-1 px-3 rounded-lg text-slate-400">
                <i class="fa-solid fa-heart-pulse text-emerald-400"></i> Health
            </a>
        </div>
    </header>

    <!-- Global Alert Banners -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 flex items-center justify-between text-sm shadow-lg mb-4">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-400 text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-300 flex items-center justify-between text-sm shadow-lg mb-4">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation text-rose-400 text-lg"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-400 hover:text-white">&times;</button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-auto border-t border-slate-800 bg-slate-900/60 py-6 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-3">
            <span>&copy; {{ date('Y') }} <strong>Toko Baju Ghani</strong> - Praktikum Pemrograman Web (Semester 5).</span>
            <span class="flex items-center gap-2">
                <i class="fa-brands fa-docker text-sky-400"></i> Docker Stack
                <span class="text-slate-700">&bull;</span>
                <i class="fa-solid fa-database text-blue-400"></i> PostgreSQL 15
                <span class="text-slate-700">&bull;</span>
                <i class="fa-brands fa-laravel text-rose-500"></i> Laravel 10
            </span>
        </div>
    </footer>

    <!-- Shopping Cart Drawer (Offcanvas) -->
    <div id="cart-drawer-backdrop" onclick="toggleCartDrawer()" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 hidden transition-opacity"></div>
    <div id="cart-drawer" class="fixed top-0 right-0 h-full w-full sm:w-96 bg-slate-900 border-l border-slate-800 z-50 transform translate-x-full transition-transform duration-300 flex flex-col shadow-2xl">
        <div class="p-5 border-b border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-fuchsia-600/20 text-fuchsia-400 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
                <h3 class="font-bold text-white text-base">Keranjang Belanja</h3>
            </div>
            <button onclick="toggleCartDrawer()" class="p-2 text-slate-400 hover:text-white rounded-lg hover:bg-slate-800 transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div id="cart-items" class="flex-1 overflow-y-auto p-5 space-y-4">
            <!-- Items injected by JS -->
        </div>

        <div class="p-5 border-t border-slate-800 bg-slate-950/60 space-y-4">
            <div class="flex justify-between items-center text-sm">
                <span class="text-slate-400">Total Tagihan:</span>
                <span id="cart-total" class="text-lg font-extrabold text-white">Rp 0</span>
            </div>
            <button onclick="checkoutWhatsApp()" class="w-full py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold text-sm hover:from-emerald-500 hover:to-teal-500 transition flex items-center justify-center gap-2 shadow-lg shadow-emerald-600/30">
                <i class="fa-brands fa-whatsapp text-lg"></i> Checkout WhatsApp
            </button>
            <button onclick="clearCart()" class="w-full py-2 rounded-xl text-xs text-rose-400 hover:text-rose-300 hover:bg-rose-500/10 transition">
                Kosongkan Keranjang
            </button>
        </div>
    </div>

    <script>
        // Shopping Cart Implementation
        let cart = JSON.parse(localStorage.getItem('tb_cart') || '[]');

        function saveCart() {
            localStorage.setItem('tb_cart', JSON.stringify(cart));
            updateCartUI();
        }

        function addToCart(id, name, price, image) {
            const existing = cart.find(item => item.id === id);
            if (existing) {
                existing.qty += 1;
            } else {
                cart.push({ id, name, price: Number(price), image, qty: 1 });
            }
            saveCart();
            toggleCartDrawer(true);
        }

        function updateQty(id, delta) {
            const item = cart.find(i => i.id === id);
            if (!item) return;
            item.qty += delta;
            if (item.qty <= 0) {
                cart = cart.filter(i => i.id !== id);
            }
            saveCart();
        }

        function clearCart() {
            if (confirm('Yakin ingin mengosongkan keranjang?')) {
                cart = [];
                saveCart();
            }
        }

        function toggleCartDrawer(openOnly = false) {
            const drawer = document.getElementById('cart-drawer');
            const backdrop = document.getElementById('cart-drawer-backdrop');
            const isOpen = !drawer.classList.contains('translate-x-full');

            if (openOnly) {
                drawer.classList.remove('translate-x-full');
                backdrop.classList.remove('hidden');
                return;
            }

            if (isOpen) {
                drawer.classList.add('translate-x-full');
                backdrop.classList.add('hidden');
            } else {
                drawer.classList.remove('translate-x-full');
                backdrop.classList.remove('hidden');
            }
        }

        function updateCartUI() {
            const container = document.getElementById('cart-items');
            const badge = document.getElementById('cart-badge');
            const totalEl = document.getElementById('cart-total');

            const totalItems = cart.reduce((sum, i) => sum + i.qty, 0);
            const totalPrice = cart.reduce((sum, i) => sum + (i.price * i.qty), 0);

            if (totalItems > 0) {
                badge.textContent = totalItems;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }

            totalEl.textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');

            if (cart.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-12 text-slate-500 space-y-2">
                        <i class="fa-solid fa-basket-shopping text-3xl opacity-40"></i>
                        <p class="text-xs">Keranjang belanja Anda masih kosong.</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = cart.map(item => `
                <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-800/70 border border-slate-700/60">
                    <img src="${item.image}" class="w-14 h-14 rounded-lg object-cover" alt="${item.name}">
                    <div class="flex-1 min-w-0">
                        <h4 class="text-xs font-bold text-white truncate">${item.name}</h4>
                        <p class="text-xs text-fuchsia-400 font-semibold mt-0.5">Rp ${item.price.toLocaleString('id-ID')}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <button onclick="updateQty(${item.id}, -1)" class="w-6 h-6 rounded-md bg-slate-700 hover:bg-slate-600 text-xs text-white flex items-center justify-center">-</button>
                            <span class="text-xs font-mono font-bold text-slate-200 px-1">${item.qty}</span>
                            <button onclick="updateQty(${item.id}, 1)" class="w-6 h-6 rounded-md bg-slate-700 hover:bg-slate-600 text-xs text-white flex items-center justify-center">+</button>
                        </div>
                    </div>
                    <div class="text-xs font-bold text-slate-300">
                        Rp ${(item.price * item.qty).toLocaleString('id-ID')}
                    </div>
                </div>
            `).join('');
        }

        function checkoutWhatsApp() {
            if (cart.length === 0) {
                alert('Keranjang belanja masih kosong! Silakan pilih produk terlebih dahulu.');
                return;
            }
            const phone = "6281234567890"; // Ganti dengan nomor WhatsApp Toko
            let msg = "*Halo Toko Baju Ghani, saya ingin memesan:*\n\n";
            let total = 0;
            cart.forEach((item, idx) => {
                const sub = item.price * item.qty;
                total += sub;
                msg += `${idx + 1}. ${item.name} (${item.qty}x) = Rp ${sub.toLocaleString('id-ID')}\n`;
            });
            msg += `\n*Total Tagihan: Rp ${total.toLocaleString('id-ID')}*\n`;
            msg += "\nMohon konfirmasi ketersediaan dan nomor rekening pembayaran. Terima kasih!";

            const url = `https://wa.me/${phone}?text=${encodeURIComponent(msg)}`;
            window.open(url, '_blank');
        }

        // Initialize UI on load
        document.addEventListener('DOMContentLoaded', updateCartUI);
    </script>
</body>
</html>
