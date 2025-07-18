<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Plus, Eye, Pencil, Trash2 } from 'lucide-vue-next';
import Badge from '@/components/ui/badge/Badge.vue';
import { Button } from '@/components/ui/button';

interface Warga {
    id: number;
    full_name: string;
    family_card_number: string;
    no_rumah: {
        name: string;
    };
    status: string;
    children_count: number;
}

interface Props {
    datawarga: Warga[];
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Data Warga',
        href: '/datawarga',
    },
];

const { datawarga } = defineProps<Props>();
const form = useForm({});

const confirmDelete = (id: number) => {
    if (confirm('Apakah Anda yakin ingin menghapus data warga ini?')) {
        form.delete(route('datawarga.destroy', id));
    }
};
</script>

<template>
    <Head title="Data Warga" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <div class="pb-4">
                <Link href="/datawarga/create" type="button"
                    class="inline-flex items-center text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                    <Plus :size="18" :stroke-width="2.5" class="mr-2" />
                    Tambah Data Warga
                </Link>
            </div>
            
            <div class="flex relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Nama Lengkap</th>
                            <th scope="col" class="px-6 py-3">No. KK</th>
                            <th scope="col" class="px-6 py-3">No. Rumah</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Jumlah Anak</th>
                            <th scope="col" class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="warga in datawarga" :key="warga.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4">{{ warga.full_name }}</td>
                            <td class="px-6 py-4">{{ warga.family_card_number }}</td>
                            <td class="px-6 py-4">
                                <Badge variant="outline">
                                    {{ warga.no_rumah.name }}
                                </Badge>
                            </td>
                            <td class="px-6 py-4">
                                <Badge>
                                    {{ warga.status }}
                                </Badge>
                            </td>
                            <td class="px-6 py-4">{{ warga.children_count }}</td>
                            <td class="px-6 py-4 flex space-x-2">
                                <Link :href="route('datawarga.show', warga.id)" as="button">
                                    <Button variant="outline" size="sm">
                                        <Eye class="h-4 w-4" />
                                    </Button>
                                </Link>
                                <Link :href="route('datawarga.edit', warga.id)" as="button">
                                    <Button variant="outline" size="sm">
                                        <Pencil class="h-4 w-4" />
                                    </Button>
                                </Link>
                                <Button 
                                    variant="destructive" 
                                    size="sm"
                                    @click="confirmDelete(warga.id)">
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