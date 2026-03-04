<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Ticket') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-12">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6">Edit Ticket: {{ $ticket->title }}</h1>

        @if(isset($errors) && $errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('tickets.update', $ticket) }}" enctype="multipart/form-data" class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            @csrf
            @method('PATCH')

            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('superadmin'))
                <!-- ADMIN CONTROLS SECTION -->
                <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 rounded">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Kelola Tiket</h2>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="status" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                                📊 Ubah Status
                            </label>
                            <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded font-semibold">
                                <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>🔴 Open</option>
                                <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>🟡 In Progress</option>
                                <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>🟢 Resolved</option>
                                <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>⚫ Closed</option>
                            </select>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Status saat ini: <strong>{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</strong></p>
                        </div>

                        <div>
                            <label for="assigned_to" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                                👤 Assign ke Admin
                            </label>
                            <select id="assigned_to" name="assigned_to" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                                <option value="">— Belum Assign —</option>
                                @foreach($admins as $admin)
                                    <option value="{{ $admin->id }}" {{ $ticket->assigned_to === $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                            📝 Catatan untuk User
                        </label>
                        <textarea id="notes" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded" 
                            placeholder="Jelaskan progress atau masalah yang ditemukan...">{{ old('notes', $ticket->notes) }}</textarea>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Catatan ini akan terlihat oleh user yang membuat tiket</p>
                    </div>
                </div>
            @else
                <!-- USER EDIT SECTION (only for open tickets) -->
                <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Edit Tiket Anda</h2>
                    
                    <div class="mb-4">
                        <label for="title" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Judul</label>
                        <input type="text" id="title" name="title" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded" 
                            value="{{ old('title', $ticket->title) }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Deskripsi</label>
                        <textarea id="description" name="description" rows="4" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">{{ old('description', $ticket->description) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label for="priority" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Priority</label>
                        <select id="priority" name="priority" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded" required>
                            <option value="low" {{ $ticket->priority === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ $ticket->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ $ticket->priority === 'high' ? 'selected' : '' }}>High</option>
                        </select>
                    </div>
                </div>
            @endif

            <div class="mb-4">
                <label for="attachments" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">📎 Tambah Lampiran (maks 10 total)</label>
                <div id="dropZone" class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded p-6 text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-gray-700 transition">
                    <input type="file" name="attachments[]" id="attachments" multiple accept="image/*" class="hidden" />
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20a4 4 0 004 4h24a4 4 0 004-4V20m-6-2l-5-5m0 0l-5 5m5-5v10" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        <span class="font-medium text-blue-600 dark:text-blue-400">Klik untuk upload</span> atau drag and drop
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-500">PNG, JPG, GIF up to 2MB per file (max 10 files)</p>
                </div>
            </div>
                    </svg>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        <span class="font-medium text-blue-600 dark:text-blue-400">Click to upload</span> or drag and drop
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-500">PNG, JPG, GIF up to 2MB each (max 10 files)</p>
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Update Ticket
                </button>
                <a href="{{ route('tickets.show', $ticket) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Cancel
                </a>
                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('superadmin'))
                    <form method="POST" action="{{ route('tickets.destroy', $ticket) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this ticket?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Delete
                        </button>
                    </form>
                @endif
            </div>
        </form>
    </div>
</x-app-layout>

@push('scripts')
<script>
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('attachments');

    // Click to upload
    dropZone.addEventListener('click', () => fileInput.click());

    // Drag and drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.classList.add('border-blue-500', 'bg-blue-50');
        dropZone.classList.remove('border-gray-300', 'hover:bg-blue-50');
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-blue-500', 'bg-blue-50');
        dropZone.classList.add('border-gray-300');
    }

    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
    }
</script>
@endpush
