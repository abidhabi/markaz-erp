<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title><?php echo e($pageTitle ?? 'Dashboard'); ?> - Markaz ERP</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'markaz-blue': '#155e9c',
                        'markaz-light': '#e8f0f8',
                    },
                    fontFamily: {
                        sans: ['Cambria', 'Georgia', 'serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Lucide Icons & Alpine.js -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        body, h1, h2, h3, h4, h5, h6, p, a, span, button, input, label {
            font-family: 'Cambria', Georgia, serif !important;
        }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased flex h-screen overflow-hidden selection:bg-markaz-blue selection:text-white">

    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-slate-200 flex flex-col h-full shadow-[4px_0_24px_rgba(0,0,0,0.02)] z-25">
        <div class="h-20 flex items-center px-6 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <img src="<?php echo e(asset('markaz logo.png')); ?>" alt="Markaz Logo" class="h-10 w-auto rounded object-contain">
                <h2 class="text-xl font-bold text-markaz-blue tracking-tight">Markaz ERP</h2>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-1">
            <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-3 px-4 py-3 bg-markaz-blue text-white rounded-xl shadow-md shadow-markaz-blue/20 transition-all">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span class="font-semibold text-sm">Dashboard</span>
            </a>

            <div class="pt-4 pb-2 px-4">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Modules</p>
            </div>
            
            <a href="<?php echo e(route('leaves.index')); ?>" class="flex items-center gap-3 px-4 py-3 text-slate-500 hover:text-markaz-blue hover:bg-markaz-light rounded-xl transition-all group">
                <i data-lucide="calendar-days" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                <span class="font-medium text-sm">Leaves</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-[#f4f7fb]">
        
        <!-- Top Header with Profile Dropdown -->
        <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-8 z-20">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Welcome back, <?php echo e(auth()->user()->name ?? 'Admin'); ?>!</h1>
                <p class="text-sm text-slate-500 mt-1">Here is what's happening in your workspace today.</p>
            </div>

            <!-- Profile Dropdown (Alpine.js) -->
            <div class="relative" x-data="{ profileOpen: false }">
                <button @click="profileOpen = !profileOpen" class="flex items-center gap-3 focus:outline-none bg-white border border-slate-200 py-1.5 px-3 rounded-full hover:shadow-md transition-all">
                    <span class="text-sm font-bold text-slate-700 ml-2"><?php echo e(auth()->user()->name ?? 'Admin'); ?></span>
                    <div class="w-8 h-8 rounded-full bg-markaz-blue flex items-center justify-center text-white font-bold text-sm">
                        <?php echo e(substr(auth()->user()->name ?? 'A', 0, 1)); ?>

                    </div>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400"></i>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="profileOpen" @click.away="profileOpen = false" x-transition.opacity.duration.200ms class="absolute right-0 mt-3 w-48 bg-white border border-slate-100 rounded-xl shadow-lg py-2 z-50" style="display: none;">
                    <div class="px-4 py-2 border-b border-slate-50 mb-1">
                        <p class="text-sm font-bold text-slate-800"><?php echo e(auth()->user()->name ?? 'Admin'); ?></p>
                        <p class="text-xs text-slate-400"><?php echo e(auth()->user()->email ?? 'admin@markaz.in'); ?></p>
                    </div>
                    
                    <a href="<?php echo e(route('profile')); ?>" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-markaz-light hover:text-markaz-blue transition-colors">
                        <i data-lucide="user" class="w-4 h-4"></i> My Profile
                    </a>
                    
                    <a href="<?php echo e(route('profile.edit')); ?>" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-markaz-light hover:text-markaz-blue transition-colors">
                        <i data-lucide="key-round" class="w-4 h-4"></i> Reset Password
                    </a>
                    
                    <div class="border-t border-slate-100 mt-1 pt-1">
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition-colors">
                                <i data-lucide="log-out" class="w-4 h-4"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dashboard Content Area -->
        <main class="flex-1 overflow-y-auto p-8 z-10">
            
            <div class="flex flex-wrap gap-6">
                
                <!-- LEAVES MODULE (Super Attractive Light Gradient UI) -->
                <a href="<?php echo e(route('leaves.index')); ?>" class="relative group block w-36 h-36 rounded-3xl shadow-[0_4px_20px_rgb(21,94,156,0.06)] hover:shadow-[0_10px_30px_rgb(21,94,156,0.15)] hover:-translate-y-1.5 transition-all duration-300 overflow-hidden bg-gradient-to-br from-white via-sky-50/50 to-blue-50/80 border border-blue-100/80 flex flex-col items-center justify-center">
                    
                    <!-- Decorative subtle background glow -->
                    <div class="absolute -top-10 -right-10 w-24 h-24 bg-blue-200/40 rounded-full blur-xl group-hover:bg-blue-300/60 transition-all duration-500"></div>

                    <!-- Leaves Icon with floating effect -->
                    <img src="<?php echo e(asset('leaves.png')); ?>" 
                         class="relative z-10 w-16 h-16 object-contain opacity-95 group-hover:scale-110 group-hover:-translate-y-1 transition-all duration-500 mb-2 filter drop-shadow-md" 
                         alt="Leave Module Icon">
                    
                    <!-- Content -->
                    <div class="relative z-10 text-center">
                        <h3 class="text-base font-bold text-slate-800 group-hover:text-markaz-blue transition-colors">Leaves</h3>
                        <p class="text-[11px] text-slate-500 group-hover:text-markaz-blue/80 mt-0.5 font-semibold transition-colors">Apply Leaves</p>
                    </div>
                </a>

            </div>
        </main>
    </div>

    <!-- Initialize Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html><?php /**PATH C:\Users\USERE\Desktop\laravel-app\markaz-erp\resources\views/pages/dashboard.blade.php ENDPATH**/ ?>