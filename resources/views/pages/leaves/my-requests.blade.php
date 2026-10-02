<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Leave Requests - Markaz ERP</title>
    
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
        body, h1, h2, h3, h4, h5, h6, p, a, span, button, input, label, select, textarea, th, td { 
            font-family: 'Cambria', Georgia, serif !important; 
        }
    </style>
</head>
<body class="text-slate-800 min-h-screen relative overflow-x-hidden bg-[#f4f7fb]">
    
    <!-- Header -->
    <header class="bg-white/80 backdrop-blur-xl border-b border-slate-200 px-8 py-4 flex justify-between items-center sticky top-0 z-20 shadow-sm">
        <div class="flex items-center gap-5">
            <a href="{{ route('leaves.index') }}" class="p-2.5 bg-white border border-slate-200 hover:bg-markaz-light hover:text-markaz-blue hover:border-markaz-blue rounded-xl text-slate-500 shadow-sm transition-all hover:scale-105 group">
                <i data-lucide="arrow-left" class="w-5 h-5 group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">My Leave Requests</h1>
                <p class="text-xs text-slate-500 font-medium">View and track the status of your leave applications</p>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <a href="{{ route('leaves.index') }}" class="px-5 py-2.5 text-sm font-bold text-markaz-blue bg-markaz-light hover:bg-blue-100 rounded-xl shadow-sm transition-all flex items-center gap-2 border border-blue-200">
                <i data-lucide="calendar" class="w-4 h-4"></i> Leave Calendar
            </a>
            
            <!-- Profile Dropdown -->
            <div class="relative" x-data="{ profileOpen: false }">
                <button @click="profileOpen = !profileOpen" class="flex items-center gap-3 focus:outline-none bg-white border border-slate-200 py-1.5 px-3 rounded-full hover:shadow-md transition-all">
                    <span class="text-sm font-bold text-slate-700 ml-2">{{ auth()->user()->name ?? 'User' }}</span>
                    <div class="w-8 h-8 rounded-full bg-markaz-blue flex items-center justify-center text-white font-bold text-sm">
                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                    </div>
                </button>
            </div>
        </div>
    </header>

    <!-- Content -->
    <main class="max-w-7xl mx-auto p-6 lg:p-8 mt-2 relative z-0">
        <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
            
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i data-lucide="list" class="w-5 h-5 text-markaz-blue"></i> Application History
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-sm border-b border-slate-100">
                            <th class="px-6 py-4 font-bold">Applied On</th>
                            <th class="px-6 py-4 font-bold">Leave Type</th>
                            <th class="px-6 py-4 font-bold">Duration</th>
                            <th class="px-6 py-4 font-bold">Dates</th>
                            <th class="px-6 py-4 font-bold">Reason</th>
                            <th class="px-6 py-4 font-bold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-100">
                        @forelse($leaves as $leave)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-6 py-4 text-slate-600 font-medium whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($leave->created_at)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 font-bold text-slate-700 bg-slate-100 px-3 py-1 rounded-lg">
                                        {{ $leave->leave_type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-800">
                                    {{ $leave->duration }} Day(s)
                                </td>
                                <td class="px-6 py-4 text-slate-600 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span class="text-markaz-blue font-bold">{{ \Carbon\Carbon::parse($leave->from_date)->format('d M Y') }}</span>
                                        @if($leave->from_date != $leave->to_date)
                                            <i data-lucide="arrow-right" class="w-3 h-3 text-slate-400"></i>
                                            <span class="text-markaz-blue font-bold">{{ \Carbon\Carbon::parse($leave->to_date)->format('d M Y') }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-500 max-w-xs truncate" title="{{ $leave->reason }}">
                                    {{ $leave->reason }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($leave->status == 'Approved')
                                        <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 px-3 py-1 rounded-full font-bold text-xs">
                                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div> Approved
                                        </span>
                                    @elseif($leave->status == 'Rejected')
                                        <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-700 border border-red-200 px-3 py-1 rounded-full font-bold text-xs">
                                            <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div> Rejected
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 border border-amber-200 px-3 py-1 rounded-full font-bold text-xs">
                                            <div class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></div> Pending
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                    <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                                    <p class="text-base font-medium">No leave requests found.</p>
                                    <p class="text-sm mt-1">You haven't applied for any leaves yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
</body>
</html>