<x-app-layout>
    <div class="bg-gradient-to-br from-purple-50 to-blue-50 dark:from-gray-900 dark:to-gray-800 min-h-screen">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- BREADCRUMB -->
                <nav class="mb-8 flex items-center space-x-2 text-sm" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                        Dashboard
                    </a>
                    <span class="text-gray-400">/</span>
                    <a href="{{ route('admin.superadmin.dashboard') }}" class="text-gray-600 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400">
                        Super Admin
                    </a>
                    <span class="text-gray-400">/</span>
                    <span class="text-blue-600 dark:text-blue-400 font-semibold">Kelola User</span>
                </nav>

                <!-- HEADER -->
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                        👥 Kelola User
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        Ubah role dan kelola user yang terdaftar dalam sistem
                    </p>
                </div>

                <!-- STATUS MESSAGE -->
                @if(session('status'))
                    <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 rounded-lg border border-green-200 dark:border-green-800 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        {{ session('status') }}
                    </div>
                @endif

                <!-- USER STATS -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                    @php
                        $totalUsers = $users->count();
                        $superadminCount = $users->filter(fn($u) => $u->role?->name === 'superadmin')->count();
                        $adminCount = $users->filter(fn($u) => $u->role?->name === 'admin')->count();
                        $userCount = $users->filter(fn($u) => $u->role?->name === 'user')->count();
                    @endphp
                    
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-purple-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">Total User</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalUsers }}</p>
                            </div>
                            <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-red-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">Super Admin</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $superadminCount }}</p>
                            </div>
                            <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-orange-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">Admin</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $adminCount }}</p>
                            </div>
                            <svg class="w-8 h-8 text-orange-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-blue-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">User</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $userCount }}</p>
                            </div>
                            <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
                        </div>
                    </div>
                </div>

                <!-- USERS TABLE -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Daftar User</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700 border-b">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Nama</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Email</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Role Saat Ini</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Ubah Role</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($users as $user)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white font-bold">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900 dark:text-white">{{ $user->name }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center gap-1
                                            @if($user->role?->name === 'superadmin') bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300
                                            @elseif($user->role?->name === 'admin') bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300
                                            @else bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300
                                            @endif
                                        ">
                                            @if($user->role?->name === 'superadmin')
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>
                                                {{ ucfirst($user->role?->name) }}
                                            @elseif($user->role?->name === 'admin')
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
                                                {{ ucfirst($user->role?->name) }}
                                            @else
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
                                                {{ ucfirst($user->role?->name ?? 'user') }}
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <form method="POST" action="{{ route('admin.superadmin.users.updateRole', $user) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <div class="flex gap-2 items-center">
                                                <select name="role_id" class="px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded text-sm font-medium transition hover:border-blue-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-900" onchange="this.form.submit()">
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->id }}"@selected($user->role && $user->role->id === $role->id)>
                                                            {{ ucfirst($role->name) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                            ✓ Aktif
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        Tidak ada user di sistem
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- BOTTOM NAVIGATION -->
                <div class="mt-8 flex items-center gap-3">
                    <a href="{{ route('admin.superadmin.dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 text-sm font-medium">
                        ← Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- BOTTOM NAVIGATION -->
            <div class="mt-8 flex items-center gap-3">
                <a href="{{ route('admin.superadmin.dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 text-sm font-medium">
                    ← Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
