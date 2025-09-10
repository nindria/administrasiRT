<script setup lang="ts">
import Badge from '@/components/ui/badge/Badge.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { can } from '@/lib/can';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Eye, Pencil, Plus, Trash2 } from 'lucide-vue-next';

interface KartuKeluarga {
    foto_ktp_kepala_keluarga_url: any;
    no_kk: string;
    nik: string;
    jumlah_anggota: number;
    foto_ktp_kepala_keluarga?: string;
    data_warga?: {
        nik: string;
        full_name: string;
    };
}

interface Props {
    kartukeluarga: {
        data: KartuKeluarga[];
    };
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Kartu Keluarga',
        href: '/kartukeluarga',
    },
];

const { kartukeluarga } = defineProps<Props>();

const confirmDelete = (no_kk: string) => {
    if (confirm('Apakah Anda yakin ingin menghapus data kartu keluarga ini?')) {
        router.delete(route('kartukeluarga.destroy', no_kk));
    }
};
</script>

<template>
    <Head title="Kartu Keluarga" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <h1 class="text-2xl font-bold">Daftar Data KK</h1>
            <div class="">
                <Link
                    v-if="can('kartukeluarga.create')"
                    href="/kartukeluarga/create"
                    type="button"
                    class="me-2 mb-2 inline-flex items-center rounded-lg bg-gradient-to-r from-green-400 via-green-500 to-green-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-gradient-to-br focus:ring-4 focus:ring-green-300 focus:outline-none dark:focus:ring-green-800"
                >
                    <Plus :size="18" :stroke-width="2.5" class="mr-2" />
                    Tambah Data KK
                </Link>
            </div>
            <div class="relative flex overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-left text-sm text-gray-500 rtl:text-right dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs text-gray-700 uppercase dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">No KK</th>
                            <th scope="col" class="px-6 py-3">Foto KTP</th>
                            <th scope="col" class="px-6 py-3">NIK Kepala Keluarga</th>
                            <th scope="col" class="px-6 py-3">Nama Kepala Keluarga</th>
                            <th scope="col" class="px-6 py-3">Jumlah Anggota</th>
                            <th scope="col" class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="kartuKeluarga in kartukeluarga.data"
                            :key="kartuKeluarga.no_kk"
                            class="border-b bg-white dark:border-gray-700 dark:bg-gray-800"
                        >
                            <td class="px-6 py-4">
                                <Badge variant="outline">
                                    {{ kartuKeluarga.no_kk }}
                                </Badge>
                            </td>
                            <td class="px-6 py-4">
                                <img
                                    v-if="kartuKeluarga.foto_ktp_kepala_keluarga_url"
                                    :src="kartuKeluarga.foto_ktp_kepala_keluarga_url"
                                    alt="Foto KTP"
                                    class="h-auto w-16 rounded object-cover"
                                />
                                <div v-else class="flex h-12 w-16 items-center justify-center rounded bg-gray-200 text-gray-500">
                                    <span class="text-xs">No Image</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <Badge>
                                    {{ kartuKeluarga.data_warga?.nik }}
                                </Badge>
                            </td>
                            <td class="px-6 py-4">{{ kartuKeluarga.data_warga?.full_name || 'Tidak ada data' }}</td>
                            <td class="px-6 py-4">{{ kartuKeluarga.jumlah_anggota }}</td>
                            <td class="flex space-x-2 px-6 py-4">
                                <Link :href="route('kartukeluarga.show', kartuKeluarga.no_kk)">
                                    <Button variant="outline" size="sm">
                                        <Eye class="h-4 w-4" />
                                    </Button>
                                </Link>
                                <Link v-if="can('kartukeluarga.edit')" :href="route('kartukeluarga.edit', kartuKeluarga.no_kk)">
                                    <Button variant="outline" size="sm">
                                        <Pencil class="h-4 w-4" />
                                    </Button>
                                </Link>
                                <Button
                                    v-if="can('kartukeluarga.delete')"
                                    variant="destructive"
                                    size="sm"
                                    @click="confirmDelete(kartuKeluarga.no_kk)"
                                >
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
