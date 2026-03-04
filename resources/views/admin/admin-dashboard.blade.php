<x-app-layout>
    <div class="bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- BREADCRUMB -->
                <nav class="mb-8 flex items-center space-x-2 text-sm" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                        Dashboard
                    </a>
                    <span class="text-gray-400">/</span>
                    <span class="text-gray-900 dark:text-gray-100 font-semibold">Admin Panel</span>
                    <span class="text-gray-400">/</span>
                    <span class="text-blue-600 dark:text-blue-400 font-semibold">Kelola Tiket</span>
                </nav>

                <!-- HEADER -->
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-4xl font-bold text-gray-900 dark:text-white">
                                <svg class="w-10 h-10 inline-block mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                Kelola Status Tiket
                            </h1>
                            <p class="mt-2 text-gray-600 dark:text-gray-400">
                                Ubah dan kelola status tiket yang masuk dari user
                            </p>
                        </div>
                    </div>
                </div>

                <!-- ACTION BUTTONS -->
                <div class="mb-8 flex gap-3 flex-wrap">
                    <a href="{{ route('admin.export.form') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center gap-2 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8m0 8H3m9 0h9"/></svg>
                        Download Laporan
                    </a>
                </div>

                <!-- STATS OVERVIEW -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                    @php
                        $allTickets = App\Models\Ticket::all();
                        $openCount = $allTickets->where('status', 'open')->count();
                        $progressCount = $allTickets->where('status', 'in_progress')->count();
                        $resolvedCount = $allTickets->where('status', 'resolved')->count();
                        $closedCount = $allTickets->where('status', 'closed')->count();
                    @endphp
                    
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-blue-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">Belum Dikerjakan</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $openCount }}</p>
                            </div>
                            <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="8"/></svg>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-yellow-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">Sedang Dikerjakan</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $progressCount }}</p>
                            </div>
                            <svg class="w-8 h-8 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="8"/></svg>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-green-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">Selesai</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $resolvedCount }}</p>
                            </div>
                            <svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="8"/></svg>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-gray-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">Ditutup</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $closedCount }}</p>
                            </div>
                            <svg class="w-8 h-8 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="8"/></svg>
                        </div>
                    </div>
                </div>

                <!-- TICKETS TABLE -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Daftar Tiket</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700 border-b">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Tiket</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">User</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Prioritas</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Status</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Dibuat</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($tickets as $ticket)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <td class="px-6 py-4">
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white">{{ Str::limit($ticket->title, 30) }}</p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ Str::limit($ticket->description, 50) }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $ticket->user->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            @if($ticket->priority === 'high')
                                                <span class="px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 rounded-full text-xs font-semibold inline-flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/></svg> High</span>
                                            @elseif($ticket->priority === 'medium')
                                                <span class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 rounded-full text-xs font-semibold inline-flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg> Medium</span>
                                            @else
                                                <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 rounded-full text-xs font-semibold inline-flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Low</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <form action="{{ route('tickets.update', $ticket) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
                                                @method('PATCH')
                                                <select name="status" onchange="this.form.submit()" class="px-3 py-1 rounded text-sm font-semibold cursor-pointer
                                                    @if($ticket->status === 'open') bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300
                                                    @elseif($ticket->status === 'in_progress') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300
                                                    @elseif($ticket->status === 'resolved') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300
                                                    @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                                                    @endif
                                                ">
                                                    <option value="open" @selected($ticket->status === 'open')>🔴 Open</option>
                                                    <option value="in_progress" @selected($ticket->status === 'in_progress')>🟡 In Progress</option>
                                                    <option value="resolved" @selected($ticket->status === 'resolved')>🟢 Resolved</option>
                                                    <option value="closed" @selected($ticket->status === 'closed')>⚫ Closed</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $ticket->created_at->format('M d, H:i') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <a href="{{ route('tickets.show', $ticket) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 font-medium">
                                                Lihat →
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            Belum ada tiket
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- FOOTER NAVIGATION -->
                <div class="mt-8 flex gap-3">
                    <a href="{{ route('dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 text-sm font-medium">
                        ← Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
