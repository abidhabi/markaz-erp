<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password - Markaz ERP</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: { 
                    colors: { 
                        'markaz-blue': '#155e9c', 
                        'markaz-light': '#e8f0f8',
                        'markaz-hover': '#114a7b'
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
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="text-slate-800 min-h-screen relative overflow-x-hidden bg-[#f4f7fb]">
    
    <!-- Decorative Background Elements -->
    <div class="fixed inset-0 z-[-1] pointer-events-none">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#cbd5e1_1px,transparent_1px),linear-gradient(to_bottom,#cbd5e1_1px,transparent_1px)] bg-[size:30px_30px] opacity-10"></div>
        <div class="absolute top-[-10%] left-[-5%] w-[500px] h-[500px] bg-blue-400 rounded-full mix-blend-multiply filter blur-[120px] opacity-20"></div>
    </div>
    
    <!-- Top Header -->
    <header class="bg-white/80 backdrop-blur-xl border-b border-slate-200 px-8 py-4 flex justify-between items-center sticky top-0 z-20 shadow-sm">
        <div class="flex items-center gap-5">
            <a href="{{ route('dashboard') }}" class="p-2.5 bg-white border border-slate-200 hover:bg-markaz-light hover:text-markaz-blue hover:border-markaz-blue rounded-xl text-slate-500 shadow-sm transition-all hover:scale-105 group">
                <i data-lucide="arrow-left" class="w-5 h-5 group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Security Settings</h1>
                <p class="text-xs text-slate-500 font-medium">Ensure your account is using a secure password</p>
            </div>
        </div>
        
        <!-- Profile Dropdown -->
        <div class="relative" x-data="{ profileOpen: false }">
            <button @click="profileOpen = !profileOpen" class="flex items-center gap-3 focus:outline-none bg-white border border-slate-200 py-1.5 px-3 rounded-full hover:shadow-md transition-all">
                <span class="text-sm font-bold text-slate-700 ml-2">{{ auth()->user()->name ?? 'Admin' }}</span>
                <div class="w-8 h-8 rounded-full bg-markaz-blue flex items-center justify-center text-white font-bold text-sm">
                    {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                </div>
                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400"></i>
            </button>
            <div x-show="profileOpen" @click.away="profileOpen = false" x-transition.opacity.duration.200ms class="absolute right-0 mt-3 w-48 bg-white border border-slate-100 rounded-2xl shadow-xl py-2 z-50">
                <a href="{{ route('profile') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-markaz-light hover:text-markaz-blue">My Profile</a>
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-markaz-blue bg-markaz-light font-semibold">Reset Password</a>
                <div class="border-t border-slate-100 mt-1 pt-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-2xl mx-auto p-6 lg:p-10">
        
        <div class="bg-white p-8 lg:p-10 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100">
            
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                <div class="p-3 bg-blue-50 text-markaz-blue rounded-2xl">
                    <i data-lucide="key-round" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800">Update Password</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Ensure your account is using a long, random password to stay secure.</p>
                </div>
            </div>

            @if(session('status') === 'password-updated' || session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-sm font-medium flex items-center gap-2">
                    <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600"></i>
                    Password updated successfully.
                </div>
            @endif

            @if ($errors->updatePassword->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->updatePassword->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Standard Password Update Form -->
            <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
                @csrf
                @method('put')

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Current Password *</label>
                    <input type="password" name="current_password" required autocomplete="current-password"
                           class="w-full border border-slate-300 rounded-xl px-4 py-3 bg-white shadow-sm focus:ring-4 focus:ring-markaz-blue/20 focus:border-markaz-blue outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">New Password *</label>
                    <input type="password" name="password" required autocomplete="new-password"
                           class="w-full border border-slate-300 rounded-xl px-4 py-3 bg-white shadow-sm focus:ring-4 focus:ring-markaz-blue/20 focus:border-markaz-blue outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Confirm Password *</label>
                    <input type="password" name="password_confirmation" required autocomplete="new-password"
                           class="w-full border border-slate-300 rounded-xl px-4 py-3 bg-white shadow-sm focus:ring-4 focus:ring-markaz-blue/20 focus:border-markaz-blue outline-none transition-all">
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">
                    <a href="{{ route('dashboard') }}" class="px-6 py-3 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all">Cancel</a>
                    <button type="submit" class="px-6 py-3 text-sm font-bold text-white bg-markaz-blue hover:bg-markaz-hover rounded-xl shadow-[0_4px_14px_0_rgba(21,94,156,0.39)] transition-all flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i> Save Changes
                    </button>
                </div>
            </form>

        </div>
    </main>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>