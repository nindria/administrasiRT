<script setup lang="ts">
import Badge from '@/components/ui/badge/Badge.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Verifikasi Data Warga',
        href: '/verifikasiwarga',
    },
];

interface Warga {
    id: number;
    nik: string;
    full_name: string;
    verification_status: 'pending' | 'verified' | 'rejected';
}

const props = defineProps<{
    wargas: Warga[];
}>();

function updateStatus(nik: string, newStatus: 'pending' | 'verified' | 'rejected') {
    if (confirm('Apakah Anda yakin ingin mengubah status verifikasi warga ini?')) {
        router.put(route('verifikasiwarga.update', { verifikasiwarga: nik }), {
            verification_status: newStatus,
        });
    }
}
</script>

<template>
    <Head title="Verifikasi Warga" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <h1 class="text-2xl font-bold">Daftar Verifikasi Warga</h1>
            <div class="relative flex overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-left text-sm text-gray-500 rtl:text-right dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs text-gray-700 uppercase dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">NIK</th>
                            <th scope="col" class="px-6 py-3">Nama</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="w in props.wargas" :key="w.nik" class="border-b bg-white dark:border-gray-700 dark:bg-gray-800">
                            <td class="px-6 py-4">
                                <Badge variant="outline">
                                    {{ w.nik }}
                                </Badge>
                            </td>
                            <td class="px-6 py-4">{{ w.full_name }}</td>
                            <td class="px-6 py-4">
                                <Badge
                                    :variant="
                                        w.verification_status === 'verified'
                                            ? 'success'
                                            : w.verification_status === 'rejected'
                                              ? 'destructive'
                                              : 'warning'
                                    "
                                >
                                    {{ w.verification_status }}
                                </Badge>
                            </td>
                            <td class="flex space-x-2 px-6 py-4">
                                <Button variant="default" size="sm" class="bg-green-600 hover:bg-green-700" @click="updateStatus(w.nik, 'verified')">
                                    Verify
                                </Button>
                                <Button variant="destructive" size="sm" @click="updateStatus(w.nik, 'rejected')"> Reject </Button>
                                <Button
                                    variant="outline"
                                    size="sm"
                                    class="bg-yellow-500 text-white hover:bg-yellow-600 hover:text-white"
                                    @click="updateStatus(w.nik, 'pending')"
                                >
                                    Pending
                                </Button>
                            </td>
                        </tr>
                        <!-- fallback kalau data kosong -->
                        <tr v-if="props.wargas.length === 0">
                            <td colspan="4" class="py-4 text-center text-gray-500">Tidak ada data warga pending untuk diverifikasi.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
