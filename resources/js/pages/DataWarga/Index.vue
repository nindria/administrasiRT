<script setup lang="ts">
import Badge from '@/components/ui/badge/Badge.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Eye, Pencil, Plus, Trash2 } from 'lucide-vue-next';

interface Warga {
    nik: string;
    full_name: string;
    tempat_lahir: string;
    tanggal_lahir: string;
    status: string;
    blok: Array<string>;
    verification_status: 'pending' | 'verified' | 'rejected';
}

interface Props {
    wargas: {
        data: Warga[];
    };
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Data Warga',
        href: '/datawarga',
    },
];

const { wargas } = defineProps<Props>();

const form = useForm({});
// function formatStatus(residence_status: string): string {
//     return residence_status.replaceAll('_', ' ').replace(/\b\w/g, (l) => l.toUpperCase());
// }

const confirmDelete = (nik: string) => {
    if (confirm('Apakah Anda yakin ingin menghapus data warga ini?')) {
        form.delete(route('datawarga.destroy', nik));
    }
};
</script>

<template>
    <Head title="Data Warga" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <h1 class="text-2xl font-bold">Daftar Data Warga</h1>
            <div class="">
                <Link
                    href="/datawarga/create"
                    type="button"
                    class="me-2 mb-2 inline-flex items-center rounded-lg bg-gradient-to-r from-green-400 via-green-500 to-green-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-gradient-to-br focus:ring-4 focus:ring-green-300 focus:outline-none dark:focus:ring-green-800"
                >
                    <Plus :size="18" :stroke-width="2.5" class="mr-2" />
                    Tambah Data Warga
                </Link>
            </div>

            <div class="relative flex overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-left text-sm text-gray-500 rtl:text-right dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs text-gray-700 uppercase dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">NIK</th>
                            <th scope="col" class="px-6 py-3">Nama</th>
                            <th scope="col" class="px-6 py-3">Tempat Lahir</th>
                            <th scope="col" class="px-6 py-3">Tanggal Lahir</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Blok Rumah</th>
                            <th scope="col" class="px-6 py-3">Status Verifikasi</th>
                            <th scope="col" class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="warga in wargas.data" :key="warga.nik" class="border-b bg-white dark:border-gray-700 dark:bg-gray-800">
                            <td class="px-6 py-4">
                                <Badge variant="outline">
                                    {{ warga.nik }}
                                </Badge>
                            </td>
                            <td class="px-6 py-4">{{ warga.full_name }}</td>
                            <td class="px-6 py-4">{{ warga.tempat_lahir }}</td>
                            <td class="px-6 py-4">{{ warga.tanggal_lahir }}</td>

                            <td class="px-6 py-4">
                                <Badge>
                                    {{ warga.status }}
                                </Badge>
                            </td>
                            <td class="px-6 py-4">{{ warga.blok }}</td>
                            <td class="px-6 py-4">
                                <Badge
                                    :variant="
                                        warga.verification_status === 'verified'
                                            ? 'success'
                                            : warga.verification_status === 'rejected'
                                              ? 'destructive'
                                              : 'warning'
                                    "
                                >
                                    {{ warga.verification_status.charAt(0).toUpperCase() + warga.verification_status.slice(1) }}
                                </Badge>
                            </td>
                            <td class="flex space-x-2 px-6 py-4">
                                <Link :href="`datawarga/${warga.nik}/edit`">
                                    <Button variant="outline" size="sm">
                                        <Eye class="h-4 w-4" />
                                    </Button>
                                </Link>
                                <Link :href="route('datawarga.edit', warga.nik)">
                                    <Button variant="outline" size="sm">
                                        <Pencil class="h-4 w-4" />
                                    </Button>
                                </Link>
                                <Button variant="destructive" size="sm" @click="confirmDelete(warga.nik)">
                                    <Trash2 class="h-4 w-4" />
                                </Button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
