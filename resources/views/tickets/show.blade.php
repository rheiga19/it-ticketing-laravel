<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ticket Details') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                        {{ $ticket->title }}
                        <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">({{ $ticket->ticket_number }})</span>
                    </h1>
            <a href="{{ route('tickets.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">
                ← Back to Tickets
            </a>
        </div>

        @if(session('status'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow mb-6">
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 font-semibold">Status</p>
                    <p class="text-lg px-2 py-1 rounded inline-block
                        @if($ticket->status === 'open') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                        @elseif($ticket->status === 'in_progress') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                        @elseif($ticket->status === 'resolved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                        @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                        @endif
                    ">
                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 font-semibold">Priority</p>
                    <p class="text-lg px-2 py-1 rounded inline-block
                        @if($ticket->priority === 'high') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                        @elseif($ticket->priority === 'medium') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                        @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                        @endif
                    ">
                        {{ ucfirst($ticket->priority) }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 font-semibold">Created By</p>
                    <p class="text-gray-900 dark:text-gray-100">{{ $ticket->user->name }} ({{ $ticket->user->email }})</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 font-semibold">Assigned To</p>
                    <p class="text-gray-900 dark:text-gray-100">
                        {{ $ticket->assignedAdmin?->name ?? 'Unassigned' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 font-semibold">Created At</p>
                    <p class="text-gray-900 dark:text-gray-100">{{ $ticket->created_at->format('F d, Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 font-semibold">Last Updated</p>
                    <p class="text-gray-900 dark:text-gray-100">{{ $ticket->updated_at->format('F d, Y H:i') }}</p>
                    @if($ticket->updated_at->ne($ticket->created_at))
                        <p class="text-xs text-green-600 dark:text-green-400">
                            ({{ $ticket->updated_at->diffForHumans() }})
                        </p>
                    @endif
                </div>
                @if($ticket->resolved_at)
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 font-semibold">Resolved At</p>
                    <p class="text-gray-900 dark:text-gray-100">{{ $ticket->resolved_at->format('F d, Y H:i') }}</p>
                </div>
                @endif
            </div>

            <hr class="border-gray-300 dark:border-gray-600 mb-6">

            <div class="mb-6">
                <p class="text-sm text-gray-600 dark:text-gray-400 font-semibold mb-2">Description</p>
                <p class="text-gray-800 dark:text-gray-200 whitespace-pre-wrap">{{ $ticket->description }}</p>
            </div>

            @if($ticket->notes)
                <hr class="border-gray-300 dark:border-gray-600 mb-6">
                <div class="mb-6">
                    <p class="text-sm text-gray-600 dark:text-gray-400 font-semibold mb-2">Admin Notes</p>
                    <p class="text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 p-4 rounded whitespace-pre-wrap">{{ $ticket->notes }}</p>
                </div>
            @endif

            @if($ticket->attachments->count())
                <hr class="border-gray-300 dark:border-gray-600 mb-6">
                <div class="mb-6">
                    <p class="text-sm text-gray-600 dark:text-gray-400 font-semibold mb-2">Attachments</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($ticket->attachments as $attach)
                            <a href="{{ asset('storage/'.$attach->path) }}" target="_blank">
                                <img src="{{ asset('storage/'.$attach->path) }}" alt="attachment" class="w-full h-32 object-cover rounded">
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('superadmin') || (auth()->user()->hasRole('user') && $ticket->user_id === auth()->id() && $ticket->status === 'open'))
                <hr class="border-gray-300 dark:border-gray-600 mb-6">
                <div class="flex gap-4">
                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('superadmin') || (auth()->user()->hasRole('user') && $ticket->user_id === auth()->id() && $ticket->status === 'open'))
                        <a href="{{ route('tickets.edit', $ticket) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit
                        </a>
                    @endif
                </div>
            @endif
        </div>

        <!-- COMMENTS SECTION -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6 flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                Pesan ({{ $ticket->comments->count() }})
            </h2>

            <!-- ADD COMMENT FORM -->
            @php
                $canComment = auth()->user()->id === $ticket->user_id || 
                            auth()->user()->id === $ticket->assigned_to || 
                            auth()->user()->hasRole(['admin', 'superadmin']);
            @endphp
            
            @if($canComment)
                <form method="POST" action="{{ route('ticket-comments.store', $ticket) }}" class="mb-6">
                    @csrf
                    <div class="mb-4">
                        <label for="message" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Tambah Pesan
                        </label>
                        <textarea id="message" name="message" rows="4" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Tulis pesan Anda..."></textarea>
                        @error('message')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center gap-2 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Kirim Pesan
                    </button>
                </form>
            @endif

            <!-- COMMENTS LIST -->
            <div class="space-y-4">
                @forelse($ticket->comments as $comment)
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $comment->user->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $comment->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            
                            @php
                                $canDeleteComment = auth()->user()->id === $comment->user_id || 
                                                  auth()->user()->id === $ticket->user_id ||
                                                  auth()->user()->id === $ticket->assigned_to ||
                                                  auth()->user()->hasRole(['admin', 'superadmin']);
                            @endphp

                            @if($canDeleteComment)
                                <form method="POST" action="{{ route('ticket-comments.destroy', $comment) }}" onsubmit="return confirm('Hapus pesan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                        
                        <p class="text-gray-800 dark:text-gray-200 whitespace-pre-wrap">{{ $comment->message }}</p>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-center py-8">
                        Belum ada pesan. Jadilah yang pertama menambahkan pesan!
                    </p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
