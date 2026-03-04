<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tickets') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-12">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Tickets</h1>
            @if(auth()->user()->hasRole('user') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('superadmin'))
                <a href="{{ route('tickets.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Create New Ticket
                </a>
            @endif
        </div>

        @if(session('status'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('status') }}
            </div>
        @endif
        @if(request()->has('status'))
            <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 rounded">
                Showing tickets with status: <strong>{{ request('status') }}</strong>
            </div>
        @endif

        @forelse($tickets as $ticket)
            <div class="mb-4 p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded shadow">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <a href="{{ route('tickets.show', $ticket) }}" class="text-xl font-bold text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">
                            {{ $ticket->title }}
                        </a>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $ticket->ticket_number }}</div>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ Str::limit($ticket->description, 100) }}</p>
                        <div class="mt-3 flex gap-2">
                            <span class="text-sm px-2 py-1 rounded
                                @if($ticket->status === 'open') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                @elseif($ticket->status === 'in_progress') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                @elseif($ticket->status === 'resolved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                @endif
                            ">
                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                            </span>
                            <span class="text-sm px-2 py-1 rounded
                                @if($ticket->priority === 'high') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                @elseif($ticket->priority === 'medium') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @endif
                            ">
                                {{ ucfirst($ticket->priority) }} Priority
                            </span>
                        </div>
                    </div>
                    <div class="text-right ml-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $ticket->created_at->format('M d, Y H:i') }}
                        </p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">
                            Created by {{ $ticket->user->name }}
                        </p>
                        @if($ticket->updated_at->ne($ticket->created_at))
                            <p class="text-xs text-green-600 dark:text-green-400 font-semibold mt-1">
                                ✓ Updated: {{ $ticket->updated_at->format('M d, H:i') }}
                            </p>
                        @endif
                        @if($ticket->assignedAdmin)
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                Assigned to {{ $ticket->assignedAdmin->name }}
                            </p>
                        @endif
                        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('superadmin'))
                            <a href="{{ route('tickets.edit', $ticket) }}" class="text-blue-600 hover:text-blue-800 text-sm mt-2 inline-block">
                                Edit
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="p-4 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 rounded">
                No tickets found.
            </div>
        @endforelse
    </div>
    </div>
</x-app-layout>
