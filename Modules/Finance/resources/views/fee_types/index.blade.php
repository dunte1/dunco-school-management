@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4" x-data="{ showModal: false, form: { name: '', description: '' }, errors: {}, loading: false }">
    <h1 class="text-2xl font-bold mb-4">Fee Types</h1>
    <button @click="showModal = true" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-4 inline-block">Add Type</button>
    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Description</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody id="fee-type-list">
            @foreach($types as $type)
            <tr>
                <td class="border px-4 py-2">{{ $type->name }}</td>
                <td class="border px-4 py-2">{{ $type->description }}</td>
                <td class="border px-4 py-2">
                    <a href="{{ route('finance.fee-types.edit', $type) }}" class="text-blue-600 hover:underline">Edit</a>
                    <form action="{{ route('finance.fee-types.destroy', $type) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline ml-2" onclick="return confirm('Delete this type?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal -->
    <div x-show="showModal" style="display: none;" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-40">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
            <button @click="showModal = false" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">&times;</button>
            <h2 class="text-xl font-bold mb-4">Add Fee Type</h2>
            <form @submit.prevent="submitForm" x-ref="form">
                <div class="mb-4">
                    <label class="block mb-1">Name</label>
                    <input type="text" x-model="form.name" name="name" class="w-full border rounded px-3 py-2" required>
                    <template x-if="errors.name"><div class="text-red-600 text-sm mt-1" x-text="errors.name"></div></template>
                </div>
                <div class="mb-4">
                    <label class="block mb-1">Description</label>
                    <textarea x-model="form.description" name="description" class="w-full border rounded px-3 py-2"></textarea>
                    <template x-if="errors.description"><div class="text-red-600 text-sm mt-1" x-text="errors.description"></div></template>
                </div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" x-bind:disabled="loading">
                    <span x-show="!loading">Save</span>
                    <span x-show="loading">Saving...</span>
                </button>
                <button type="button" @click="showModal = false" class="ml-2 text-gray-600 hover:underline">Cancel</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('feeTypeModal', () => ({
        showModal: false,
        form: { name: '', description: '' },
        errors: {},
        loading: false,
        submitForm() {
            this.loading = true;
            this.errors = {};
            fetch('{{ route('finance.fee-types.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(this.form)
            })
            .then(async response => {
                this.loading = false;
                if (response.ok) {
                    this.showModal = false;
                    this.form = { name: '', description: '' };
                    // Reload the list (simple way: reload page, or use AJAX to update table)
                    window.location.reload();
                } else {
                    const data = await response.json();
                    this.errors = data.errors || {};
                }
            })
            .catch(() => { this.loading = false; });
        }
    }));
});
</script>
@endsection 