<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Markaz ERP - Enterprise Workspace</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#6A0DAD',
                        'brand-hover': '#580b91',
                        'markaz-blue': '#155e9c',
                    },
                    fontFamily: {
                        sans: ['Cambria', 'Georgia', 'serif'],
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'float-delayed': 'float 6s ease-in-out 3s infinite',
                        'spin-slow': 'spin 15s linear infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Lucide Icons & Alpine.js -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Force Cambria Font Globally -->
    <style>
        body, h1, h2, h3, h4, h5, h6, p, a, span, button {
            font-family: 'Cambria', Georgia, serif !important;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased overflow-x-hidden selection:bg-brand selection:text-white flex flex-col min-h-screen">

    <!-- Colorful Animated Background Elements -->
    <div class="fixed inset-0 w-full h-full pointer-events-none z-0 overflow-hidden bg-slate-50">
        <div class="absolute top-[-10%] right-[-5%] w-[500px] h-[500px] bg-gradient-to-br from-purple-400 to-brand rounded-full mix-blend-multiply filter blur-[100px] opacity-40 animate-float"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[600px] h-[600px] bg-gradient-to-tr from-blue-400 to-teal-300 rounded-full mix-blend-multiply filter blur-[120px] opacity-40 animate-float-delayed"></div>
        <div class="absolute top-[20%] left-[30%] w-[400px] h-[400px] bg-gradient-to-r from-pink-300 to-orange-300 rounded-full mix-blend-multiply filter blur-[120px] opacity-30 animate-pulse"></div>
        <div class="absolute inset-0" style="background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 30px 30px; opacity: 0.5;"></div>
    </div>

    <!-- Header -->
    <header class="relative z-50 w-full bg-white/70 backdrop-blur-xl border-b border-white shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="bg-white p-1.5 rounded-xl shadow-md border border-slate-100 flex items-center justify-center">
                    <img src="<?php echo e(asset('markaz logo.png')); ?>" alt="Markaz Logo" class="h-10 w-auto object-contain rounded-lg">
                </div>
                <!-- Updated: Fully bold and solid markaz-blue color for both words -->
                <span class="font-extrabold text-2xl tracking-tight text-markaz-blue drop-shadow-sm">Markaz ERP</span>
            </div>
            <div>
                <a class="group flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-brand to-purple-600 text-white rounded-xl font-bold text-sm hover:shadow-lg hover:shadow-purple-500/30 hover:-translate-y-0.5 transition-all active:scale-95" href="<?php echo e(route('login')); ?>">
                    Sign In
                    <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow relative z-10 flex items-center justify-center px-6 py-12 lg:py-0">
        <div class="max-w-7xl mx-auto w-full grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            
            <!-- Left Content Area -->
            <div class="space-y-8 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white shadow-md border border-slate-100 text-brand font-bold text-xs tracking-wider uppercase">
                    <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                    Enterprise Resource Planning System
                </div>
                
                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight text-slate-900 leading-[1.1]">
                    Streamline Your <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand via-purple-500 to-blue-500">Daily Workflows</span>
                </h1>
                
                <p class="text-lg text-slate-600 max-w-xl mx-auto lg:mx-0 leading-relaxed font-medium">
                    Experience a seamless and highly efficient enterprise workspace tailored for Markaz. Manage your human resources, financial operations, and daily administrative workflows from a single, unified smart portal.
                </p>
                
                <div class="flex flex-col sm:flex-row items-center gap-4 justify-center lg:justify-start pt-4">
                    <a href="<?php echo e(route('login')); ?>" class="group flex items-center gap-3 px-8 py-4 bg-slate-900 text-white rounded-2xl font-bold text-base hover:bg-brand transition-colors shadow-xl hover:shadow-2xl hover:shadow-brand/30 active:scale-[0.98]">
                        Sign in to Markaz ERP
                        <i data-lucide="log-in" class="w-5 h-5 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>

            <!-- Right Colorful Card Slider -->
            <div class="relative w-full h-[500px] flex justify-center items-center lg:justify-end">
                <div class="absolute w-[450px] h-[450px] border-2 border-dashed border-brand/20 rounded-full animate-spin-slow z-0"></div>

                <div x-data="{ slide: 1 }" x-init="setInterval(() => { slide = slide === 3 ? 1 : slide + 1 }, 4000)" class="relative z-10 w-full max-w-md h-[380px]">
                    
                    <div class="absolute inset-0 bg-white rounded-3xl shadow-[0_20px_50px_-12px_rgba(106,13,173,0.2)] border border-slate-100 p-8 flex flex-col justify-between transition-all duration-700 transform"
                         :class="slide === 1 ? 'opacity-100 translate-y-0 scale-100' : 'opacity-0 translate-y-8 scale-95 pointer-events-none'">
                        <div class="bg-gradient-to-br from-purple-100 to-purple-50 w-16 h-16 rounded-2xl flex items-center justify-center text-brand mb-6 shadow-inner">
                            <i data-lucide="users" class="w-8 h-8"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-extrabold text-slate-900 mb-3">Human Resources</h3>
                            <p class="text-slate-500 leading-relaxed font-medium">
                                Manage employee profiles, attendance, leaves, and structural hierarchies seamlessly with vibrant dashboards.
                            </p>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full mt-6 overflow-hidden">
                            <div class="bg-brand h-full w-full rounded-full animate-pulse"></div>
                        </div>
                    </div>

                    <div class="absolute inset-0 bg-white rounded-3xl shadow-[0_20px_50px_-12px_rgba(16,185,129,0.2)] border border-slate-100 p-8 flex flex-col justify-between transition-all duration-700 transform"
                         :class="slide === 2 ? 'opacity-100 translate-y-0 scale-100' : 'opacity-0 translate-y-8 scale-95 pointer-events-none'">
                        <div class="bg-gradient-to-br from-emerald-100 to-teal-50 w-16 h-16 rounded-2xl flex items-center justify-center text-emerald-600 mb-6 shadow-inner">
                            <i data-lucide="wallet" class="w-8 h-8"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-extrabold text-slate-900 mb-3">Payroll & Finance</h3>
                            <p class="text-slate-500 leading-relaxed font-medium">
                                Automated salary slips, deductions, and financial tracking for Markaz operations with one-click voucher generation.
                            </p>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full mt-6 overflow-hidden">
                            <div class="bg-emerald-500 h-full w-full rounded-full animate-pulse"></div>
                        </div>
                    </div>

                    <div class="absolute inset-0 bg-white rounded-3xl shadow-[0_20px_50px_-12px_rgba(59,130,246,0.2)] border border-slate-100 p-8 flex flex-col justify-between transition-all duration-700 transform"
                         :class="slide === 3 ? 'opacity-100 translate-y-0 scale-100' : 'opacity-0 translate-y-8 scale-95 pointer-events-none'">
                        <div class="bg-gradient-to-br from-blue-100 to-blue-50 w-16 h-16 rounded-2xl flex items-center justify-center text-blue-600 mb-6 shadow-inner">
                            <i data-lucide="shield-check" class="w-8 h-8"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-extrabold text-slate-900 mb-3">Access Control</h3>
                            <p class="text-slate-500 leading-relaxed font-medium">
                                Robust security permissions, role assignments, and comprehensive administration capabilities for IT heads.
                            </p>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full mt-6 overflow-hidden">
                            <div class="bg-blue-500 h-full w-full rounded-full animate-pulse"></div>
                        </div>
                    </div>

                    <div class="absolute -bottom-12 left-0 right-0 flex justify-center gap-3">
                        <button @click="slide = 1" class="h-2.5 rounded-full transition-all duration-300" :class="slide === 1 ? 'w-10 bg-brand' : 'w-2.5 bg-slate-300 hover:bg-brand/50'"></button>
                        <button @click="slide = 2" class="h-2.5 rounded-full transition-all duration-300" :class="slide === 2 ? 'w-10 bg-emerald-500' : 'w-2.5 bg-slate-300 hover:bg-emerald-500/50'"></button>
                        <button @click="slide = 3" class="h-2.5 rounded-full transition-all duration-300" :class="slide === 3 ? 'w-10 bg-blue-500' : 'w-2.5 bg-slate-300 hover:bg-blue-500/50'"></button>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 bg-white/50 backdrop-blur-md border-t border-slate-200 mt-auto">
        <div class="max-w-7xl mx-auto px-6 py-6 text-center">
            <p class="text-sm font-semibold text-slate-500">&copy; <?php echo e(date('Y')); ?> Markaz ERP. All rights reserved.</p>
        </div>
    </footer>

    <!-- Initialize Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html><?php /**PATH C:\Users\USERE\Desktop\laravel-app\markaz-erp\resources\views/welcome.blade.php ENDPATH**/ ?>