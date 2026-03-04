<x-app-layout>
    <div class="bg-gradient-to-br from-blue-50 to-purple-50 dark:from-gray-900 dark:to-gray-800 min-h-screen">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- BREADCRUMB -->
                <nav class="mb-8 flex items-center space-x-2 text-sm" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                        Dashboard
                    </a>
                    <span class="text-gray-400">/</span>
                    <span class="text-gray-900 dark:text-gray-100 font-semibold">Super Admin</span>
                    <span class="text-gray-400">/</span>
                    <span class="text-blue-600 dark:text-blue-400 font-semibold">Laporan Statistik</span>
                </nav>

                <!-- HEADER -->
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2 inline-flex items-center gap-3">
                        <svg class="w-10 h-10 text-blue-600" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z\"/></svg>
                        Dashboard Statistik
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        Pantau statistik dan laporan tiket secara lengkap
                    </p>
                </div>

                <!-- QUICK LINKS -->
                <div class="mb-8 flex flex-wrap gap-3">
                    <a href="{{ route('tickets.index', ['status' => 'open']) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="8"/></svg>
                        Open / In Progress
                    </a>
                    <a href="{{ route('tickets.index', ['status' => 'resolved']) }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Tiket Selesai
                    </a>
                    <a href="{{ route('admin.superadmin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>
                        Kelola User
                    </a>
                </div>

                <!-- MAIN CONTENT -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <!-- STATUS SUMMARY CARD -->
                    <div class="lg:col-span-1 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                            📈 Ringkasan Status
                        </h2>
                        <div class="space-y-4">
                            @foreach(['open','in_progress','resolved','closed'] as $status)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded">
                                    <div class="flex items-center gap-2">
                                        @if($status === 'open')
                                            <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="8"/></svg>
                                            <span class="font-semibold text-gray-900 dark:text-white">Open</span>
                                        @elseif($status === 'in_progress')
                                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="8"/></svg>
                                            <span class="font-semibold text-gray-900 dark:text-white">In Progress</span>
                                        @elseif($status === 'resolved')
                                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="8"/></svg>
                                            <span class="font-semibold text-gray-900 dark:text-white">Resolved</span>
                                        @else
                                            <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="8"/></svg>
                                            <span class="font-semibold text-gray-900 dark:text-white">Closed</span>
                                        @endif
                                    </div>
                                    <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                        {{ $statusCounts[$status] ?? 0 }}
                                    </span>
                                </div>
                            @endforeach
                            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-gray-900 dark:text-white">Total Tiket</span>
                                    <span class="text-3xl font-bold text-purple-600 dark:text-purple-400">
                                        {{ collect($statusCounts)->sum() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CHART CARD -->
                    <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 inline-flex items-center gap-2">
                            <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Tiket Per Hari (30 hari terakhir)
                        </h2>
                        <div class="relative" style="height: 300px;">
                            <canvas id="weekdayChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- INFO CARDS -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-lg shadow-lg">
                        <div class="text-4xl font-bold mb-2">
                            {{ \App\Models\Ticket::where('status', 'open')->count() }}
                        </div>
                        <p class="text-blue-100">Tiket yang Menunggu</p>
                        <p class="text-sm text-blue-200 mt-1">Perlu segera ditindaklanjuti</p>
                    </div>

                    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white p-6 rounded-lg shadow-lg">
                        <div class="text-4xl font-bold mb-2">
                            {{ \App\Models\Ticket::where('status', 'in_progress')->count() }}
                        </div>
                        <p class="text-yellow-100">Sedang Dikerjakan</p>
                        <p class="text-sm text-yellow-200 mt-1">Dalam proses penyelesaian</p>
                    </div>

                    <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-6 rounded-lg shadow-lg">
                        <div class="text-4xl font-bold mb-2">
                            {{ \App\Models\Ticket::where('status', 'resolved')->count() }} / {{ \App\Models\Ticket::where('status', 'closed')->count() }}
                        </div>
                        <p class="text-green-100">Selesai / Ditutup</p>
                        <p class="text-sm text-green-200 mt-1">Tiket yang telah diselesaikan</p>
                    </div>
                </div>

                <!-- BACK TO DASHBOARD -->
                <div class="flex gap-3">
                    <a href="{{ route('dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 text-sm font-medium">
                        ← Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- chart script --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('weekdayChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($days),
                datasets: [{
                    label: 'Tickets',
                    data: @json($data),
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>

