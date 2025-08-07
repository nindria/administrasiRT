<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Event',
        href: '/event',
    },
];

interface Events {
    id: number;
    title: string;
    description: string;
    image: string;
}

const props = defineProps({
    event: Array
});

const event = props.event as Events[];

</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <p>
                            Ini Dashboard event
                        </p>
                        <Link href="/event/create" type="button"
                            class=" inline-flex items-center text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">

                        <Plus :size="18" :stroke-width="2.5" class="mr-2" />Event
                        </Link>
                    </div>
                    <div class="flex relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        ID
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Title
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Description
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Image
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="Events in event" :key="Events.id"
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                                    <th scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ Events.id }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ Events.title }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ Events.description }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <img v-if="Events.image" :src="`/storage/${Events.image}`" alt="event image"
                                            class="w-20 h-auto rounded" />
                                    </td>

                                    <td class="px-6 py-4 flex space-x-2">
                                        <Link :href="`event/${Events.id}/edit`">
                                        <Button variant="outline" size="sm">
                                            <Pencil class="h-4 w-4" />
                                        </Button>

                                        </Link>
                                        <!-- <Button variant="destructive" size="sm" @click="deleteItem(Events.id)">
                                    <Trash2 class="h-4 w-4" />
                                </Button> -->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>


        </div>
    </AppLayout>
</template>
