<!-- resources/js/Pages/VerifikasiWarga/Show.vue -->
<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

interface Warga {
    id: number;
    full_name: string;
    family_card_number: string;
    married_status: string;
    wife_name?: string;
    wife_birth_place?: string;
    wife_birth_date?: string;
    // tambahkan properti lain yang diperlukan
}

interface Props {
    warga: Warga;
    verificationStatuses: {
        pending: string;
        verified: string;
        rejected: string;
    };
}

defineProps<Props>();


const form = useForm({
    status: 'verified',
    rejection_reason: null as string | null
});
</script>

<template>
    <Head :title="`Verifikasi ${warga.full_name}`" />
    
    <AppLayout>
        <div class="py-6 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h1 class="text-2xl font-bold mb-6">Verifikasi Data Warga</h1>
                        
                        <!-- Data Warga -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div>
                                <h3 class="text-lg font-medium mb-4">Data Utama</h3>
                                <div class="space-y-3">
                                    <p><strong>Nama:</strong> {{ warga.full_name }}</p>
                                    <p><strong>No KK:</strong> {{ warga.family_card_number }}</p>
                                    <p><strong>Status Pernikahan:</strong> 
                                        {{ warga.married_status === 'menikah' ? 'Menikah' : 'Belum Menikah' }}
                                    </p>
                                </div>
                            </div>
                            
                            <div v-if="warga.married_status === 'menikah'">
                                <h3 class="text-lg font-medium mb-4">Data Istri</h3>
                                <div class="space-y-3">
                                    <p><strong>Nama Istri:</strong> {{ warga.wife_name }}</p>
                                    <p><strong>Tempat Lahir Istri:</strong> {{ warga.wife_birth_place }}</p>
                                    <p><strong>Tanggal Lahir Istri:</strong> {{ warga.wife_birth_date }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Form Verifikasi -->
                        <form @submit.prevent="form.post(route('verifikasiwarga.verify', warga.id))">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Status Verifikasi
                                </label>
                                <select
                                    v-model="form.status"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    @change="form.rejection_reason = null"
                                >
                                    <option 
                                        v-for="(label, value) in verificationStatuses" 
                                        :value="value"
                                        :key="value"
                                    >
                                        {{ label }}
                                    </option>
                                </select>
                            </div>

                            <div class="mb-4" v-if="form.status === 'rejected'">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Alasan Penolakan
                                </label>
                                <textarea
                                    v-model="form.rejection_reason"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    rows="3"
                                    required
                                ></textarea>
                            </div>

                            <div class="flex justify-end space-x-4">
                                <Link
                                    :href="route('verifikasiwarga.index')"
                                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400"
                                >
                                    Kembali
                                </Link>
                                <button
                                    type="submit"
                                    class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 disabled:opacity-50"
                                    :disabled="form.processing"
                                >
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>