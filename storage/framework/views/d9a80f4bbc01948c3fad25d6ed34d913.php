<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Admin Login - Markaz ERP</title>

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
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
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
        body, h1, h2, h3, h4, h5, h6, p, a, span, button, input, label {
            font-family: 'Cambria', Georgia, serif !important;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased overflow-x-hidden selection:bg-brand selection:text-white">

    <!-- Colorful Animated Background Elements -->
    <div class="fixed inset-0 w-full h-full pointer-events-none z-0 overflow-hidden bg-slate-50">
        <div class="absolute top-[-10%] right-[-5%] w-[500px] h-[500px] bg-gradient-to-br from-purple-400 to-brand rounded-full mix-blend-multiply filter blur-[100px] opacity-40 animate-float"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[600px] h-[600px] bg-gradient-to-tr from-blue-400 to-teal-300 rounded-full mix-blend-multiply filter blur-[120px] opacity-40 animate-float-delayed"></div>
        <div class="absolute top-[20%] left-[30%] w-[400px] h-[400px] bg-gradient-to-r from-pink-300 to-orange-300 rounded-full mix-blend-multiply filter blur-[120px] opacity-30 animate-pulse-slow"></div>
        <div class="absolute inset-0" style="background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 30px 30px; opacity: 0.5;"></div>
    </div>

    <!-- Login Container -->
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative z-10 px-4">
        
        <!-- Premium Glassmorphism Card -->
        <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white/70 backdrop-blur-2xl border border-white shadow-[0_20px_50px_-12px_rgba(106,13,173,0.15)] overflow-hidden rounded-[2rem]">
            
            <!-- Logo Section -->
            <div class="flex flex-col items-center justify-center mb-8">
                <a href="/" class="bg-white p-2 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-center mb-4 hover:scale-105 transition-transform">
                    <img src="<?php echo e(asset('markaz logo.png')); ?>" alt="Markaz Logo" class="h-14 w-auto object-contain rounded-lg">
                </a>
                <h2 class="text-3xl font-extrabold text-markaz-blue drop-shadow-sm tracking-tight">Markaz ERP</h2>
            </div>

            <!-- Validation Errors -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm font-medium">
                    <?php echo e($errors->first()); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Login Form -->
            <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-6">
                <?php echo csrf_field(); ?>

                <!-- Admin Username / Email -->
                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700">Email Address</label>
                    <input id="email" name="email" type="text" value="<?php echo e(old('email')); ?>" required autofocus autocomplete="username" 
                           class="mt-2 block w-full px-4 py-3 bg-white/80 border border-slate-200 rounded-xl focus:border-brand focus:ring-brand focus:ring-2 transition-all shadow-sm text-slate-900" 
                           placeholder="Enter your Email">
                </div>

                <!-- Password with Toggle -->
                <div x-data="{ showPassword: false }">
                    <label for="password" class="block text-sm font-bold text-slate-700">Password</label>
                    <div class="relative mt-2">
                        <input id="password" name="password" :type="showPassword ? 'text' : 'password'" required autocomplete="current-password" 
                               class="block w-full px-4 py-3 bg-white/80 border border-slate-200 rounded-xl focus:border-brand focus:ring-brand focus:ring-2 transition-all shadow-sm text-slate-900 pr-12" 
                               placeholder="Enter your password">
                        
                        <!-- Eye Icon Button -->
                        <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 flex items-center px-4 text-slate-400 hover:text-brand focus:outline-none transition-colors">
                            <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg x-show="showPassword" style="display: none;" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                                <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                                <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
                                <line x1="2" x2="22" y1="2" y2="22"></line>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between mt-4">
                    <label for="remember" class="flex items-center cursor-pointer">
                        <input id="remember" name="remember" type="checkbox" class="rounded border-slate-300 text-brand shadow-sm focus:ring-brand w-4 h-4 cursor-pointer">
                        <span class="ms-2 text-sm font-medium text-slate-600">Remember me</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center items-center gap-2 py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-brand/30 text-base font-bold text-white bg-gradient-to-r from-brand to-purple-600 hover:from-brand-hover hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand transition-all hover:-translate-y-0.5 active:scale-95">
                        Sign In
                        <i data-lucide="log-in" class="w-5 h-5"></i>
                    </button>
                </div>
            </form>
            
        </div>
        
        <!-- Minimal Footer -->
        <div class="mt-8 text-center text-sm font-semibold text-slate-500">
            &copy; <?php echo e(date('Y')); ?> Markaz Saquafathi Sunniyya.
        </div>
    </div>

    <!-- Initialize Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html><?php /**PATH C:\Users\USERE\Desktop\laravel-app\markaz-erp\resources\views/auth/login.blade.php ENDPATH**/ ?>