<!-- resources/js/Pages/VerifikasiWarga/Index.vue -->
<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

interface Warga {
    id: number;
    full_name: string;
    family_card_number: string;
    verification_status: string;
}

const props = defineProps<{
    pendingWargas: Warga[];
}>();

const selectedItems = ref<number[]>([]);

const toggleSelectAll = (event: Event) => {
    const target = event.target as HTMLInputElement;
    selectedItems.value = target.checked
        ? props.pendingWargas.map(item => item.id)
        : [];
};
</script>

<template>
    <Head title="Verifikasi Data Warga" />
    
    <AppLayout>
        <div class="flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <div class="pb-4 flex justify-between items-center">
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        @change="toggleSelectAll" 
                        class="mr-2 h-4 w-4"
                    />
                    <span>Pilih Semua</span>
                </div>
                
                <Link 
                    v-if="selectedItems.length > 0"
                    :href="route('verifikasiwarga.bulk', { 
                        ids: selectedItems, 
                        status: 'verified' 
                    })"
                    method="post"
                    as="button"
                    class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600"
                >
                    Verifikasi Massal
                </Link>
            </div>

            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Nama</th>
                            <th scope="col" class="px-6 py-3">Nomor KK</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr 
                            v-for="warga in pendingWargas" 
                            :key="warga.id"
                            class="bg-white border-b hover:bg-gray-50"
                        >
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <input 
                                        type="checkbox" 
                                        v-model="selectedItems"
                                        :value="warga.id"
                                        class="mr-2 h-4 w-4"
                                    />
                                    {{ warga.full_name }}
                                </div>
                            </td>
                            <td class="px-6 py-4">{{ warga.family_card_number }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <Link 
                                    :href="route('verifikasiwarga.show', warga.id)"
                                    class="font-medium text-blue-600 hover:underline"
                                >
                                    Detail
                                </Link>
                            </td>
                        </tr>
                        <tr v-if="pendingWargas.length === 0">
                            <td colspan="4" class="px-6 py-4 text-center">
                                Tidak ada data warga yang perlu diverifikasi
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>