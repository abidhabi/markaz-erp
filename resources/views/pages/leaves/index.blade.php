<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Leaves & Calendar - Markaz ERP</title>
    
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
    
    <!-- FullCalendar CSS & JS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    
    <style>
        :root {
            --fc-font-family: 'Cambria', Georgia, serif;
            --fc-border-color: #e2e8f0;
            --fc-today-bg-color: #f0f7ff;
        }

        body, .fc, h1, h2, h3, h4, h5, h6, p, a, span, button, input, label, select, textarea { 
            font-family: 'Cambria', Georgia, serif !important; 
        }

        .fc-header-toolbar {
            display: flex !important;
            flex-wrap: wrap;
            align-items: center !important;
            justify-content: space-between !important;
            gap: 1rem;
            margin-bottom: 2rem !important;
        }

        .fc-toolbar-chunk {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .fc-toolbar-title { 
            font-size: 1.6rem !important; 
            font-weight: 800 !important; 
            color: #1e293b; 
        }
        
        .fc-button { 
            background-color: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            color: #334155 !important;
            text-transform: capitalize !important; 
            border-radius: 0.75rem !important; 
            padding: 0.5rem 1rem !important; 
            font-weight: 600 !important; 
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important; 
            transition: all 0.2s ease !important; 
        }

        .fc-button:hover { 
            background-color: #f8fafc !important;
            border-color: #155e9c !important;
            color: #155e9c !important;
        }

        .fc-button-active, .fc-button-primary:not(:disabled):active, .fc-button-primary:not(:disabled).fc-button-active {
            background-color: #155e9c !important;
            border-color: #155e9c !important;
            color: #ffffff !important;
        }

        .fc-prev-button, .fc-next-button, .fc-today-button {
            background-color: #155e9c !important;
            border-color: #155e9c !important;
            color: #ffffff !important;
        }

        .fc-theme-standard .fc-scrollgrid { 
            border-radius: 1.25rem; 
            overflow: hidden; 
            border: 1px solid #e2e8f0 !important; 
        }
        .fc-col-header-cell-cushion { 
            padding: 1rem 0 !important; 
            font-size: 0.95rem !important; 
            font-weight: 700 !important; 
            color: #475569 !important; 
            text-transform: uppercase !important; 
        }
        .fc-daygrid-day-number { 
            font-size: 1.05rem !important; 
            font-weight: 700 !important; 
            color: #334155 !important; 
            padding: 0.5rem 0.75rem !important; 
        }
        .fc-daygrid-day:hover { 
            background-color: #f8fafc !important; 
            cursor: pointer; 
        }
        
        .fc-day-today { 
            background: linear-gradient(135deg, #f0f7ff 0%, #e0f2fe 100%) !important; 
        }
        .fc-day-today .fc-daygrid-day-number {
            color: #155e9c !important;
            background: #fff;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 4px;
            box-shadow: 0 2px 4px rgba(21,94,156,0.15);
        }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body x-data="leaveForm()" @open-leave-modal.window="openModal($event.detail)" class="text-slate-800 min-h-screen relative overflow-x-hidden bg-[#f4f7fb]">
    
    @php
        // Explicitly fetch user roles to avoid blade directive cache issues
        $user = auth()->user();
        $isSuperAdmin = $user ? $user->hasRole('super-admin') : false;
        $isAdmin = $user ? $user->hasRole('admin') : false;
        $hasManagerAccess = $isSuperAdmin || $isAdmin;
        // Super Admin has all access, others check for 'leaves.view'
        $canViewLeaves = $isSuperAdmin || ($user ? $user->can('leaves.view') : false);
    @endphp

    <!-- Odoo Style Top Navigation Bar -->
    <nav class="bg-white/95 backdrop-blur-xl border-b border-slate-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                
                <!-- Left: Logo & Menus -->
                <div class="flex items-center gap-6">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-markaz-blue hover:text-markaz-hover transition-colors">
                        <div class="p-2 bg-markaz-light rounded-xl">
                            <i data-lucide="layout-grid" class="w-5 h-5"></i>
                        </div>
                        <span class="text-xl font-black tracking-tight">Leaves</span>
                    </a>

                    <div class="h-6 w-px bg-slate-200 hidden md:block"></div>

                    <!-- Menus -->
                    <div class="hidden md:flex items-center space-x-1">
                        
                        <!-- 1. My Leaves Menu -->
                        @if($canViewLeaves)
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="px-3 py-2 text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-markaz-blue rounded-lg flex items-center gap-1.5 transition-all">
                                My Leaves <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-slate-400"></i>
                            </button>
                            <div x-show="open" x-transition class="absolute left-0 mt-1 w-52 bg-white border border-slate-100 shadow-xl rounded-xl py-2 z-50" style="display: none;">
                                <button type="button" @click="window.dispatchEvent(new CustomEvent('open-leave-modal', { detail: '{{ date('Y-m-d') }}' })); open = false" class="w-full text-left px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-markaz-light hover:text-markaz-blue">
                                    1. New Request
                                </button>
                                <a href="{{ route('leaves.my-requests') }}" class="block px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-markaz-light hover:text-markaz-blue">
                                    2. Leave Requests
                                </a>
                                <a href="#" class="block px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-markaz-light hover:text-markaz-blue">
                                    3. Allocation Requests
                                </a>
                            </div>
                        </div>
                        @endif

                        <!-- 2. Managers Menu -->
                        @if($hasManagerAccess)
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="px-3 py-2 text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-markaz-blue rounded-lg flex items-center gap-1.5 transition-all">
                                Managers <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-slate-400"></i>
                            </button>
                            <div x-show="open" x-transition class="absolute left-0 mt-1 w-48 bg-white border border-slate-100 shadow-xl rounded-xl py-2 z-50" style="display: none;">
                                <a href="#" class="block px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-markaz-light hover:text-markaz-blue">1. All Leaves</a>
                                <a href="#" class="block px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-markaz-light hover:text-markaz-blue">2. All Allocations</a>
                            </div>
                        </div>
                        
                        <!-- 3. Reporting Menu -->
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="px-3 py-2 text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-markaz-blue rounded-lg flex items-center gap-1.5 transition-all">
                                Reporting <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-slate-400"></i>
                            </button>
                            <div x-show="open" x-transition class="absolute left-0 mt-1 w-56 bg-white border border-slate-100 shadow-xl rounded-xl py-2 z-50" style="display: none;">
                                <a href="#" class="block px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-markaz-light hover:text-markaz-blue">Employees Leaves Report</a>
                            </div>
                        </div>
                        @endif

                        <!-- 4. Configuration Menu -->
                        @if($isSuperAdmin)
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="px-3 py-2 text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-markaz-blue rounded-lg flex items-center gap-1.5 transition-all">
                                Configuration <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-slate-400"></i>
                            </button>
                            <div x-show="open" x-transition class="absolute left-0 mt-1 w-48 bg-white border border-slate-100 shadow-xl rounded-xl py-2 z-50" style="display: none;">
                                <a href="#" class="block px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-markaz-light hover:text-markaz-blue">Leave Types</a>
                                
                                <a href="{{ route('settings.access-rights') }}" class="block px-4 py-2.5 text-sm font-bold text-markaz-blue bg-blue-50/50 hover:bg-markaz-light border-t border-slate-100">
                                    <i data-lucide="shield" class="w-4 h-4 inline-block mr-1 mb-0.5"></i> Access Rights
                                </a>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>

                <!-- Right: Profile -->
                <div class="flex items-center gap-4">
                    <div class="relative" x-data="{ profileOpen: false }">
                        <button @click="profileOpen = !profileOpen" class="flex items-center gap-3 focus:outline-none bg-slate-50 hover:bg-slate-100 border border-slate-200 py-1.5 px-3 rounded-full transition-all">
                            <span class="text-sm font-bold text-slate-700 ml-1">{{ auth()->user()->name ?? 'Admin' }}</span>
                            <div class="w-7 h-7 rounded-full bg-markaz-blue flex items-center justify-center text-white font-bold text-xs shadow-inner">
                                {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                            </div>
                        </button>
                    </div>
                </div>
                
            </div>
        </div>
    </nav>

    <!-- Success Message Alert -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-sm font-medium flex items-center gap-2 shadow-sm">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Calendar Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-0">
        <div class="bg-white p-8 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 relative">
            <div id='calendar'></div>
        </div>
    </main>

    <!-- Full Detailed Leave Application Modal -->
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center px-4">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showModal = false" x-transition.opacity></div>
        
        <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl relative z-10 p-8 border border-white" x-show="showModal" x-transition>
            <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-50 text-markaz-blue rounded-lg"><i data-lucide="calendar-plus" class="w-5 h-5"></i></div>
                    <h3 class="text-xl font-bold text-slate-800">Apply Leave</h3>
                </div>
                <button @click="showModal = false" class="p-2 bg-slate-50 text-slate-400 hover:text-red-500 rounded-full transition-all"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <!-- Form includes enctype for file upload -->
            <form action="{{ route('leaves.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Leave Type <span class="text-red-500">*</span></label>
                        <select name="leave_type" x-model="leaveType" required class="w-full border border-slate-300 rounded-xl px-4 py-3 bg-white shadow-sm focus:ring-2 focus:ring-markaz-blue outline-none">
                            <option value="">Select Type</option>
                            <option value="Casual">Casual Leave (CL)</option>
                            <option value="Medical">Medical Leave (ML)</option>
                            <option value="Earned">Earned Leave (EL)</option>
                        </select>
                    </div>

                    <!-- Enhanced Half Day Checkbox -->
                    <div class="col-span-2 flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-200 hover:bg-slate-100 transition-colors">
                        <input type="checkbox" id="half_day" x-model="isHalfDay" @change="calculateDuration" class="w-5 h-5 text-markaz-blue rounded border-slate-300 focus:ring-markaz-blue cursor-pointer">
                        <label for="half_day" class="text-sm font-bold text-slate-700 cursor-pointer select-none">Include Half Day (-0.5 Days)</label>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">From Date <span class="text-red-500">*</span></label>
                        <input type="date" name="from_date" x-model="fromDate" @change="calculateDuration" required class="w-full border border-slate-300 rounded-xl px-4 py-3 bg-white shadow-sm outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">To Date <span class="text-red-500">*</span></label>
                        <input type="date" name="to_date" x-model="toDate" @change="calculateDuration" required class="w-full border border-slate-300 rounded-xl px-4 py-3 bg-white shadow-sm outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Duration (Days) <span class="text-red-500">*</span></label>
                        <input type="text" name="duration" x-model="duration" readonly class="w-full border border-slate-200 rounded-xl px-4 py-3 bg-slate-100 text-slate-700 font-bold shadow-inner outline-none cursor-not-allowed">
                    </div>

                    <!-- Session (Shows when Half Day is checked) -->
                    <div x-show="isHalfDay" x-transition>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Session <span class="text-red-500">*</span></label>
                        <select name="session" class="w-full border border-slate-300 rounded-xl px-4 py-3 bg-white shadow-sm outline-none" :required="isHalfDay">
                            <option value="Morning">Morning Session</option>
                            <option value="Afternoon">Afternoon Session</option>
                        </select>
                    </div>

                    <!-- Which day is half day? (Shows only for 1.5, 2.5 days etc) -->
                    <div class="col-span-2" x-show="isHalfDay && hasMultipleDays" x-transition>
                        <div class="p-4 bg-blue-50 border border-blue-100 rounded-xl">
                            <label class="block text-sm font-bold text-markaz-blue mb-1.5">Which date is the Half Day? <span class="text-red-500">*</span></label>
                            <select name="half_day_date" class="w-full border border-blue-200 rounded-xl px-4 py-2.5 bg-white shadow-sm outline-none text-slate-700 text-sm">
                                <option :value="fromDate" x-text="'First Day (' + fromDate + ')'"></option>
                                <option :value="toDate" x-text="'Last Day (' + toDate + ')'"></option>
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5 mt-1">Reason / Description <span class="text-red-500">*</span></label>
                    <textarea name="reason" rows="2" required placeholder="Enter reason for leave..." class="w-full border border-slate-300 rounded-xl px-4 py-3 bg-white shadow-sm outline-none resize-none"></textarea>
                </div>

                <!-- Medical Certificate Upload (Shows only when Medical is selected and days >= 1) -->
                <div x-show="isAttachmentRequired" x-transition class="p-4 bg-orange-50 border border-orange-200 rounded-2xl mt-2">
                    <label class="block text-sm font-bold text-orange-800 mb-1">Medical Certificate Required <span class="text-red-500">*</span></label>
                    <p class="text-xs text-orange-600 mb-3">Please upload proof for medical leave of 1 day or more.</p>
                    <input type="file" name="attachment" :required="isAttachmentRequired" class="w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-white file:text-orange-700 file:shadow-sm">
                </div>

                <div class="pt-5 mt-2 flex justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="showModal = false" class="px-6 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-markaz-blue hover:bg-markaz-hover rounded-xl shadow-lg transition-all flex items-center gap-2">
                        Submit Leave <i data-lucide="send" class="w-4 h-4"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script (Detailed Logic) -->
    <script>
        function leaveForm() {
            return {
                showModal: false,
                leaveType: '',
                duration: 1,
                fromDate: '',
                toDate: '',
                isHalfDay: false,
                
                get isAttachmentRequired() {
                    return this.leaveType === 'Medical' && parseFloat(this.duration) >= 1;
                },
                
                get hasMultipleDays() {
                    if(this.fromDate && this.toDate) {
                        return this.fromDate !== this.toDate;
                    }
                    return false;
                },
                
                openModal(dateStr) {
                    this.fromDate = dateStr;
                    this.toDate = dateStr;
                    this.isHalfDay = false;
                    this.calculateDuration();
                    this.leaveType = '';
                    this.showModal = true;
                },
                
                calculateDuration() {
                    if (this.fromDate && this.toDate) {
                        let start = new Date(this.fromDate);
                        let end = new Date(this.toDate);
                        
                        if (end >= start) {
                            let diffTime = Math.abs(end - start);
                            let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                            
                            // Subtract 0.5 if half day is included
                            this.duration = this.isHalfDay ? (diffDays - 0.5) : diffDays;
                        } else {
                            this.duration = 0;
                        }
                    }
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek' },
                height: 700,
                contentHeight: 'auto',
                events: "{{ route('leaves.events') }}",
                dateClick: function(info) {
                    window.dispatchEvent(new CustomEvent('open-leave-modal', { detail: info.dateStr }));
                }
            });
            calendar.render();
            lucide.createIcons();
        });
    </script>
</body>
</html>