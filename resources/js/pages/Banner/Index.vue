<script setup lang="ts">
import Badge from '@/components/ui/badge/Badge.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Pencil, Plus, Trash2 } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Banner',
        href: '/banners',
    },
];

defineProps({
    banners: Object,
});

const confirmDelete = (id: number) => {
    if (confirm('Apakah Anda yakin ingin menghapus banner ini?')) {
        router.delete(route('banners.destroy', id));
    }
};
</script>

<template>
    <Head title="Banner Management" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <h1 class="text-2xl font-bold">Daftar Data Banner</h1>
            <div class="">
                <Link
                    :href="route('banners.create')"
                    type="button"
                    class="me-2 mb-2 inline-flex items-center rounded-lg bg-gradient-to-r from-green-400 via-green-500 to-green-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-gradient-to-br focus:ring-4 focus:ring-green-300 focus:outline-none dark:focus:ring-green-800"
                >
                    <Plus :size="18" :stroke-width="2.5" class="mr-2" />
                    Tambah Banner
                </Link>
            </div>
            <div class="relative flex overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-left text-sm text-gray-500 rtl:text-right dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs text-gray-700 uppercase dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">ID</th>
                            <th scope="col" class="px-6 py-3">Gambar</th>
                            <th scope="col" class="px-6 py-3">Title</th>
                            <th scope="col" class="px-6 py-3">Description</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="banner in banners" :key="banner.id" class="border-b bg-white dark:border-gray-700 dark:bg-gray-800">
                            <td class="px-6 py-4">
                                <Badge variant="outline">
                                    {{ banner.id }}
                                </Badge>
                            </td>
                            <td class="px-6 py-4">
                                <img v-if="banner.image" :src="banner.image" :alt="banner.title" class="h-auto w-16 rounded object-cover" />
                                <div v-else class="flex h-12 w-16 items-center justify-center rounded bg-gray-200 text-gray-500">
                                    <span class="text-xs">No Image</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">{{ banner.title }}</td>
                            <td class="px-6 py-4">
                                <Badge>
                                    {{ banner.description }}
                                </Badge>
                            </td>
                            <td class="px-6 py-4">
                                <Badge :variant="banner.is_active ? 'default' : 'outline'">
                                    {{ banner.is_active ? 'Active' : 'Inactive' }}
                                </Badge>
                            </td>
                            <td class="flex space-x-2 px-6 py-4">
                                <Link :href="route('banners.edit', banner.id)">
                                    <Button variant="outline" size="sm">
                                        <Pencil class="h-4 w-4" />
                                    </Button>
                                </Link>
                                <Button variant="destructive" size="sm" @click="confirmDelete(banner.id)">
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
