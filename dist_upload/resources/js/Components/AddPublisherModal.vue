<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm transition-all">
    <div class="bg-white text-gray-900 rounded-2xl shadow-2xl w-full max-w-md p-6 relative border border-gray-200 animate-fadeIn">
      <button @click="$emit('close')" class="absolute top-4 right-4 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-full w-10 h-10 flex items-center justify-center shadow focus:outline-none focus:ring-2 focus:ring-blue-400 z-10" aria-label="Close">
        <i class="fas fa-times"></i>
      </button>
      <h2 class="text-xl font-bold text-blue-700 mb-6 flex items-center gap-2 uppercase tracking-wide">
        <i class="fas fa-building"></i>
        Add Publisher
      </h2>
      <form @submit.prevent="submit" autocomplete="off">
        <div v-if="form.errors && form.errors.name" class="bg-red-50 text-red-700 border border-red-300 rounded-lg px-4 py-3 mb-4 font-semibold">
          {{ form.errors.name }}
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 font-bold mb-1">Name</label>
          <input v-model="form.name" type="text" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 text-gray-900 placeholder-gray-400 font-semibold transition-all duration-150" required placeholder="Publisher name" />
        </div>
        <div class="flex justify-end gap-3 mt-8">
          <button type="button" class="px-6 py-2 rounded-lg bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-semibold transition shadow" @click="$emit('close')">
            Cancel
          </button>
          <button type="submit" class="px-6 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold shadow-lg transition flex items-center gap-2" :disabled="form.processing">
            <i class="fas fa-plus"></i>
            <span v-if="!form.processing">Save</span>
            <span v-else>Saving...</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
const props = defineProps({ show: Boolean });
const emit = defineEmits(['close','saved']);
const form = useForm({ name: '' });
function submit() {
  form.post('/library/publishers', {
    onSuccess: () => {
      form.reset();
      emit('saved');
      emit('close');
    },
  });
}
</script>

<style scoped>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(40px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn {
  animation: fadeIn 0.3s cubic-bezier(0.4,0,0.2,1);
}
input {
  font-size: 1rem;
}
</style> 