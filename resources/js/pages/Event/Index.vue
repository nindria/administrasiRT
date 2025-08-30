<script setup lang="ts">
import Badge from '@/components/ui/badge/Badge.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Pencil, Plus, Trash2 } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Event',
        href: '/events',
    },
];

interface Events {
    id: number;
    title: string;
    description: string;
    image: string;
}

const props = defineProps({
    event: Array,
});

const event = props.event as Events[];

const confirmDelete = (id: number) => {
    if (confirm('Apakah Anda yakin ingin menghapus event ini?')) {
        router.delete(route('events.destroy', id), {
            onSuccess: () => {
                // Data akan otomatis terupdate karena Inertia akan reload halaman
            },
            onError: () => {
                alert('Gagal menghapus event. Silakan coba lagi.');
            }
        });
    }
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <h1 class="text-2xl font-bold">Daftar Event</h1>
            <div class="">
                <Link
                    href="/events/create"
                    type="button"
                    class="me-2 mb-2 inline-flex items-center rounded-lg bg-gradient-to-r from-green-400 via-green-500 to-green-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-gradient-to-br focus:ring-4 focus:ring-green-300 focus:outline-none dark:focus:ring-green-800"
                >
                    <Plus :size="18" :stroke-width="2.5" class="mr-2" />
                    Tambah Event
                </Link>
            </div>
            <div class="relative flex overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-left text-sm text-gray-500 rtl:text-right dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs text-gray-700 uppercase dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">ID</th>
                            <th scope="col" class="px-6 py-3">Title</th>
                            <th scope="col" class="px-6 py-3">Description</th>
                            <th scope="col" class="px-6 py-3">Image</th>
                            <th scope="col" class="px-6 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="Events in event" :key="Events.id" class="border-b bg-white dark:border-gray-700 dark:bg-gray-800">
                            <td class="px-6 py-4">
                                <Badge variant="outline">
                                    {{ Events.id }}
                                </Badge>
                            </td>
                            <td class="px-6 py-4">
                                {{ Events.title }}
                            </td>
                            <td class="px-6 py-4">
                                <Badge>
                                    {{ Events.description }}
                                </Badge>
                            </td>
                            <td class="px-6 py-4">
                                <img v-if="Events.image" :src="`${Events.image}`" alt="event image" class="h-auto w-16 rounded object-cover" />
                            </td>
                            <td class="flex space-x-2 px-6 py-4">
                                <Link :href="`/events/${Events.id}/edit`">
                                    <Button variant="outline" size="sm">
                                        <Pencil class="h-4 w-4" />
                                    </Button>
                                </Link>
                                <Button variant="destructive" size="sm" @click="confirmDelete(Events.id)">
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
