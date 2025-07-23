<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Plus, Pencil, Trash2 } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'No Rumah',
        href: '/norumah',
    },
];
interface rumah {
    id: number;
    name: string;
}

const props = defineProps({
    norumah: Array
})

const norumah = props.norumah as rumah[];

const form = useForm({});
function deleteItem(id: number) {
    if (confirm('Are you sure you want to delete this item?')) {
        form.delete(route('norumah.destroy', id), {
            preserveScroll: true,
            onSuccess: () => {
                // Filter keluar item yang dihapus
                const index = norumah.findIndex(item => item.id === id);
                if (index !== -1) {
                    norumah.splice(index, 1);
                }
            }
        });
    }
}
</script>

<template>

    <Head title="No Rumah" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class=" flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <div class="pb-4">
                <Link href="/norumah/create" type="button"
                    class=" inline-flex items-center text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">

                <Plus :size="18" :stroke-width="2.5" class="mr-2" />No Rumah
                </Link>
            </div>
            <div class="flex relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                ID
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Nomor Rumah
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Action
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="rumah in norumah" :key="rumah.id"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ rumah.id }}
                            </th>
                            <td class="px-6 py-4">
                                {{ rumah.name }}
                            </td>

                            <td class="px-6 py-4 flex space-x-2">
                                <Link :href="`norumah/${rumah.id}/edit`">
                                <Button variant="outline" size="sm">
                                    <Pencil class="h-4 w-4" />
                                </Button>

                                </Link>
                                <Button variant="destructive" size="sm" @click="deleteItem(rumah.id)">
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
