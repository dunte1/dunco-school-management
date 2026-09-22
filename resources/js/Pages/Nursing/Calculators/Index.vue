<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, reactive } from 'vue';

const activeCalc = ref('bmi');
const loading = ref(false);
const result = ref<Record<string, unknown> | null>(null);

const bmi = reactive({ weight: '', height: '' });
const percentage = reactive({ value: '', total: '' });
const weightConv = reactive({ weight: '', from: 'kg', to: 'lbs' });
const fluidBalance = reactive({ input: '', output: '' });
const ivFlow = reactive({ volume: '', time: '', drop_factor: '20' });
const timeConv = reactive({ hours: '' });

const calculators = [
    { id: 'bmi', name: 'BMI Calculator', icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z' },
    { id: 'percentage', name: 'Percentage', icon: 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z' },
    { id: 'weight_conversion', name: 'Weight Conversion', icon: 'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3' },
    { id: 'fluid_balance', name: 'Fluid Balance', icon: 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z' },
    { id: 'iv_flow', name: 'IV Flow Rate', icon: 'M13 10V3L4 14h7v7l9-11h-7z' },
    { id: 'time_conversion', name: 'Time Conversion', icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z' },
];

const calculate = async () => {
    loading.value = true;
    result.value = null;

    let values: Record<string, string> = {};
    switch (activeCalc.value) {
        case 'bmi': values = bmi; break;
        case 'percentage': values = percentage; break;
        case 'weight_conversion': values = weightConv; break;
        case 'fluid_balance': values = fluidBalance; break;
        case 'iv_flow': values = ivFlow; break;
        case 'time_conversion': values = timeConv; break;
    }

    try {
        const response = await fetch(route('nursing.calculators.calculate'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ type: activeCalc.value, values }),
        });
        const data = await response.json();
        result.value = data.result;
    } catch {
        result.value = { error: 'Calculation failed. Please try again.' };
    } finally {
        loading.value = false;
    }
};
</script>

<template>
    <Head title="Educational Calculators" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800">Educational Calculators</h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <p class="text-sm text-yellow-800">
                        <strong>Educational Use Only:</strong> These calculators are for learning purposes. Always follow institutional protocols and verify calculations independently. Never provide personalized prescriptions.
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Calculator List -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                            <div class="space-y-2">
                                <button v-for="calc in calculators" :key="calc.id" @click="activeCalc = calc.id; result = null" :class="[activeCalc === calc.id ? 'bg-brand-blue text-white' : 'text-gray-700 hover:bg-gray-100', 'w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition']">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="calc.icon" />
                                    </svg>
                                    {{ calc.name }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Calculator Form -->
                    <div class="lg:col-span-3">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-6">{{ calculators.find(c => c.id === activeCalc)?.name }}</h3>

                            <!-- BMI -->
                            <div v-if="activeCalc === 'bmi'" class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Weight (kg)</label>
                                        <input v-model="bmi.weight" type="number" step="0.1" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Height (cm)</label>
                                        <input v-model="bmi.height" type="number" step="0.1" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" />
                                    </div>
                                </div>
                            </div>

                            <!-- Percentage -->
                            <div v-if="activeCalc === 'percentage'" class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Value</label>
                                        <input v-model="percentage.value" type="number" step="0.01" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Total</label>
                                        <input v-model="percentage.total" type="number" step="0.01" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" />
                                    </div>
                                </div>
                            </div>

                            <!-- Weight Conversion -->
                            <div v-if="activeCalc === 'weight_conversion'" class="space-y-4">
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Weight</label>
                                        <input v-model="weightConv.weight" type="number" step="0.01" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">From</label>
                                        <select v-model="weightConv.from" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm">
                                            <option value="kg">kg</option>
                                            <option value="lbs">lbs</option>
                                            <option value="g">g</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">To</label>
                                        <select v-model="weightConv.to" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm">
                                            <option value="kg">kg</option>
                                            <option value="lbs">lbs</option>
                                            <option value="g">g</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Fluid Balance -->
                            <div v-if="activeCalc === 'fluid_balance'" class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Input (mL)</label>
                                        <input v-model="fluidBalance.input" type="number" step="0.1" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Output (mL)</label>
                                        <input v-model="fluidBalance.output" type="number" step="0.1" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" />
                                    </div>
                                </div>
                            </div>

                            <!-- IV Flow Rate -->
                            <div v-if="activeCalc === 'iv_flow'" class="space-y-4">
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Volume (mL)</label>
                                        <input v-model="ivFlow.volume" type="number" step="0.1" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Time (hours)</label>
                                        <input v-model="ivFlow.time" type="number" step="0.1" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Drop Factor</label>
                                        <select v-model="ivFlow.drop_factor" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm">
                                            <option value="10">10 gtt/mL</option>
                                            <option value="15">15 gtt/mL</option>
                                            <option value="20">20 gtt/mL</option>
                                            <option value="60">60 gtt/mL</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Time Conversion -->
                            <div v-if="activeCalc === 'time_conversion'" class="space-y-4">
                                <div class="max-w-xs">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Hours</label>
                                    <input v-model="timeConv.hours" type="number" step="0.5" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" />
                                </div>
                            </div>

                            <button @click="calculate" :disabled="loading" class="mt-6 px-6 py-2 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-brand-blue-dark transition disabled:opacity-50">
                                {{ loading ? 'Calculating...' : 'Calculate' }}
                            </button>

                            <!-- Result -->
                            <div v-if="result" class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <h4 class="font-semibold text-gray-800 mb-2">Result</h4>
                                <div v-if="result.error" class="text-red-600">{{ result.error }}</div>
                                <div v-else>
                                    <p class="text-2xl font-bold text-brand-blue mb-2">{{ result.value }}{{ result.category ? ' - ' + result.category : '' }}</p>
                                    <div v-if="result.steps" class="space-y-1">
                                        <p v-for="(step, i) in result.steps as string[]" :key="i" class="text-sm text-gray-600">{{ step }}</p>
                                    </div>
                                    <p v-if="result.disclaimer" class="mt-3 text-xs text-gray-500 italic">{{ result.disclaimer }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
