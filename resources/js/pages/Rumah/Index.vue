<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { Plus, Eye, Pencil, Trash2 } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Rumah',
        href: '/rumah',
    },
];


defineProps({
  rumahs: Object
})

const confirmDelete = (id: number) => {
  if (confirm('Apakah Anda yakin ingin menghapus data rumah ini?')) {
    router.delete(route('rumah.destroy', id))
  }
};

</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <h1 class="text-2xl font-bold mb-4">Daftar Rumah</h1>
                <div class="pb-4">
                    <Link href="/rumah/create" type="button"
                        class="inline-flex items-center text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                    <Plus :size="18" :stroke-width="2.5" class="mr-2" />
                    Tambah Data Warga
                    </Link>
                </div>
            <div class="flex relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
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
                        <tr v-for="rumah in rumahs?.data" :key="rumah.id_rumah" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
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
                            <td class="px-6 py-4 flex space-x-2">
                                <Link :href="`rumah/${rumah.id}/edit`" >
                                    <Button variant="outline" size="sm">
                                        <Eye class="h-4 w-4" />
                                    </Button>
                                </Link>
                                <Link :href="route('rumah.edit', rumah.id_rumah)" >
                                    <Button variant="outline" size="sm">
                                        <Pencil class="h-4 w-4" />
                                    </Button>
                                </Link>
                                <Button 
                                    variant="destructive" 
                                    size="sm"
                                    @click="confirmDelete(rumah.id_rumah)">
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
