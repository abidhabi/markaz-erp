@extends('layouts.app') <!-- Adjust based on your layout -->

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Access Rights Management</h1>
            <p class="text-sm text-slate-500 mt-1">Configure module and menu access for different roles</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-bold flex items-center gap-2 shadow-sm">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <form action="{{ route('settings.access-rights.update') }}" method="POST">
            @csrf
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-6 py-4 text-sm font-bold text-slate-700 w-1/3">Modules & Permissions</th>
                            <!-- Generate Column for Each Role -->
                            @foreach($roles as $role)
                                <th class="px-6 py-4 text-center text-sm font-bold text-slate-700">
                                    {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($permissions as $module => $modulePermissions)
                            <!-- Module Header -->
                            <tr class="bg-slate-50/50">
                                <td colspan="{{ count($roles) + 1 }}" class="px-6 py-3 text-sm font-bold text-markaz-blue bg-blue-50/30">
                                    <i data-lucide="folder" class="w-4 h-4 inline-block mr-1"></i> {{ $module }} Module
                                </td>
                            </tr>
                            
                            <!-- Permissions inside Module -->
                            @foreach($modulePermissions as $permission)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-3 text-sm font-medium text-slate-600 pl-10">
                                        {{ ucfirst(str_replace(['.', '-'], ' ', $permission->name)) }}
                                    </td>
                                    
                                    <!-- Checkboxes for each role -->
                                    @foreach($roles as $role)
                                        <td class="px-6 py-3 text-center">
                                            <input type="checkbox" 
                                                name="permissions[{{ $role->id }}][{{ $permission->id }}]" 
                                                {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                                class="w-5 h-5 text-markaz-blue bg-slate-100 border-slate-300 rounded focus:ring-markaz-blue cursor-pointer">
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-6 border-t border-slate-100 bg-slate-50 flex justify-end">
                <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-markaz-blue hover:bg-markaz-hover rounded-xl shadow-md transition-all flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i> Save Access Rights
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endsection