<script setup lang="ts">
import Badge from '@/components/ui/badge/Badge.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Pencil, Plus, Trash2 } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Rumah',
        href: '/rumah',
    },
];

defineProps({
    rumahs: Object,
});

const confirmDelete = (id: number) => {
    if (confirm('Apakah Anda yakin ingin menghapus data rumah ini?')) {
        router.delete(route('rumah.destroy', id));
    }
};
</script>

<template>
    <Head title="Rumah" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <h1 class="text-2xl font-bold">Daftar Rumah</h1>
            <div class="">
                <Link
                    href="/rumah/create"
                    type="button"
                    class="me-2 mb-2 inline-flex items-center rounded-lg bg-gradient-to-r from-green-400 via-green-500 to-green-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-gradient-to-br focus:ring-4 focus:ring-green-300 focus:outline-none dark:focus:ring-green-800"
                >
                    <Plus :size="18" :stroke-width="2.5" class="mr-2" />
                    Tambah Data Rumah
                </Link>
            </div>
            <div class="relative flex overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-left text-sm text-gray-500 rtl:text-right dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs text-gray-700 uppercase dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">ID Rumah</th>
                            <th scope="col" class="px-6 py-3">Perumahan</th>
                            <th scope="col" class="px-6 py-3">Jalan</th>
                            <th scope="col" class="px-6 py-3">Blok</th>
                            <th scope="col" class="px-6 py-3">Nomor</th>
                            <th scope="col" class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="rumah in rumahs?.data" :key="rumah.id_rumah" class="border-b bg-white dark:border-gray-700 dark:bg-gray-800">
                            <td class="px-6 py-4">
                                <Badge variant="outline">
                                    {{ rumah.id_rumah }}
                                </Badge>
                            </td>
                            <td class="px-6 py-4">{{ rumah.perumahan }}</td>

                            <td class="px-6 py-4">
                                <Badge>
                                    {{ rumah.jalan }}
                                </Badge>
                            </td>
                            <td class="px-6 py-4">{{ rumah.blok }}</td>
                            <td class="px-6 py-4">{{ rumah.nomor }}</td>
                            <td class="flex space-x-2 px-6 py-4">
                                <Link :href="route('rumah.edit', rumah.id_rumah)">
                                    <Button variant="outline" size="sm">
                                        <Pencil class="h-4 w-4" />
                                    </Button>
                                </Link>
                                <Button variant="destructive" size="sm" @click="confirmDelete(rumah.id_rumah)">
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
