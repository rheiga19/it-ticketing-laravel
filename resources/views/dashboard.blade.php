<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(auth()->user()->hasRole('user'))
                <!-- USER DASHBOARD -->
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                        Selamat datang, {{ auth()->user()->name }}!
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">Berikut adalah ringkasan tiket Anda</p>
                </div>

                <!-- NOTIFICATIONS -->
                @php
                    $notifications = auth()->user()->notifications()->whereNull('read_at')->get();
                @endphp
                
                @if($notifications->isNotEmpty())
                    <div class="mb-6 space-y-3">
                        @foreach($notifications as $notification)
                            @if($notification->type === 'App\Notifications\TicketResolvedNotification')
                                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 flex items-start gap-4">
                                    <div class="flex-shrink-0">
                                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="flex-grow">
                                        <h3 class="font-semibold text-green-800 dark:text-green-300 mb-1">Tiket Diselesaikan!</h3>
                                        <p class="text-sm text-green-700 dark:text-green-400 mb-2">{{ $notification->data['message'] }}</p>
                                        <p class="text-xs text-green-600 dark:text-green-500 mb-3"><strong>Tiket:</strong> {{ $notification->data['ticket_number'] ?? '' }} - {{ $notification->data['title'] }}</p>
                                        <a href="{{ route('tickets.show', $notification->data['ticket_id']) }}" class="text-xs font-medium text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300">
                                            Lihat Tiket →
                                        </a>
                                    </div>
                                    <form method="POST" action="{{ route('notifications.mark-read', $notification->id) }}" class="flex-shrink-0">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif

                <!-- TICKET STATS -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    @php
                        $userTickets = auth()->user()->tickets;
                        $openCount = $userTickets->where('status', 'open')->count();
                        $progressCount = $userTickets->where('status', 'in_progress')->count();
                        $resolvedCount = $userTickets->where('status', 'resolved')->count();
                        $closedCount = $userTickets->where('status', 'closed')->count();
                    @endphp
                    
                    <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-6 rounded">
                        <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $openCount }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Open Tickets</div>
                    </div>
                    
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 p-6 rounded">
                        <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $progressCount }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">In Progress</div>
                    </div>
                    
                    <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 p-6 rounded">
                        <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $resolvedCount }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Resolved</div>
                    </div>
                    
                    <div class="bg-gray-50 dark:bg-gray-700 border-l-4 border-gray-500 p-6 rounded">
                        <div class="text-3xl font-bold text-gray-600 dark:text-gray-400">{{ $closedCount }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Closed</div>
                    </div>
                </div>

                <!-- ACTION BUTTONS -->
                <div class="mb-6 flex gap-3 flex-wrap">
                    <a href="{{ route('tickets.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center gap-2 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Buat Tiket Baru
                    </a>
                    <a href="{{ route('tickets.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center gap-2 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Lihat Semua Tiket
                    </a>
                    <a href="{{ route('export.my-tickets') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center gap-2 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8m0 8H3m9 0h9"/></svg>
                        Export Tiket
                    </a>
                </div>

                <!-- RECENT TICKETS -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Tiket Terbaru</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Tiket ID</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Judul</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Status</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Priority</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Diupdate</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Admin</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse(auth()->user()->tickets()->latest('updated_at')->limit(5)->get() as $ticket)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $ticket->ticket_number }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ Str::limit($ticket->title, 30) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="px-2 py-1 rounded text-xs font-semibold
                                                @if($ticket->status === 'open') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @elseif($ticket->status === 'in_progress') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @elseif($ticket->status === 'resolved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                                @endif
                                            ">
                                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="px-2 py-1 rounded text-xs font-semibold
                                                @if($ticket->priority === 'high') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                @elseif($ticket->priority === 'medium') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                                @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @endif
                                            ">
                                                {{ ucfirst($ticket->priority) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                            <div class="flex flex-col">
                                                <span>{{ $ticket->updated_at->format('M d') }}</span>
                                                <span class="text-xs text-gray-500 dark:text-gray-500">{{ $ticket->updated_at->format('H:i') }}</span>
                                                @if($ticket->updated_at->ne($ticket->created_at))
                                                    <span class="text-xs text-green-600 dark:text-green-400 font-semibold">
                                                        ✓ Diubah
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $ticket->assignedAdmin?->name ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <a href="{{ route('tickets.show', $ticket) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                                Lihat →
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            Anda belum membuat tiket. <a href="{{ route('tickets.create') }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400">Buat sekarang</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            @elseif(auth()->user()->hasRole('admin'))
                <!-- ADMIN DASHBOARD -->
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                        Admin Panel
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">Kelola status tiket yang masuk</p>
                </div>

                <div class="flex gap-3 mb-6 flex-wrap">
                    <a href="{{ route('admin.dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center gap-2 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Kelola Tiket
                    </a>
                    <a href="{{ route('tickets.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center gap-2 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Lihat Semua Tiket
                    </a>\n                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-600 dark:text-gray-400">
                        Akses panel admin untuk mengelola status tiket dan mengubah progress masalah yang dilaporkan user.
                    </p>
                </div>

            @elseif(auth()->user()->hasRole('superadmin'))
                <!-- SUPERADMIN DASHBOARD -->
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                        Super Admin Dashboard
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">Lihat statistik lengkap dan kelola sistem</p>
                </div>

                <div class="flex gap-3 mb-6 flex-wrap">
                    <a href="{{ route('admin.superadmin.dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center gap-2 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        Dashboard Lengkap
                    </a>
                    <a href="{{ route('admin.superadmin.users.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded inline-flex items-center gap-2 transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>
                        Kelola User
                    </a>
                    <a href="{{ route('tickets.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center gap-2 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Lihat Semua Tiket
                    </a>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-600 dark:text-gray-400">
                        Akses dashboard lengkap untuk melihat statistik, grafik tiket, dan kelola pengguna sistem.
                    </p>
                </div>

                <!-- SUPERADMIN TICKET STATS -->
                @php
                    $allTickets = \App\Models\Ticket::all();
                    $openCount = $allTickets->where('status', 'open')->count();
                    $progressCount = $allTickets->where('status', 'in_progress')->count();
                    $resolvedCount = $allTickets->where('status', 'resolved')->count();
                    $closedCount = $allTickets->where('status', 'closed')->count();
                    $totalCount = $allTickets->count();
                    $solvedPercentage = $totalCount > 0 ? round(($resolvedCount / $totalCount) * 100, 1) : 0;
                    $progressPercentage = $totalCount > 0 ? round(($progressCount / $totalCount) * 100, 1) : 0;
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mt-6">
                    <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-6 rounded">
                        <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $openCount }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Open Tickets</div>
                    </div>
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 p-6 rounded">
                        <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $progressCount }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">In Progress</div>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 p-6 rounded">
                        <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $resolvedCount }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Resolved</div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 border-l-4 border-gray-500 p-6 rounded">
                        <div class="text-3xl font-bold text-gray-600 dark:text-gray-400">{{ $closedCount }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Closed</div>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900/20 border-l-4 border-green-600 p-6 rounded">
                        <div class="text-3xl font-bold text-green-800 dark:text-green-300">{{ $solvedPercentage }}%</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Solved %</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
