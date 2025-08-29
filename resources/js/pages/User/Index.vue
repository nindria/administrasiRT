<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type User } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Plus, Pencil, Eye } from 'lucide-vue-next';
import { can } from '@/lib/can';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'User',
        href: '/users',
    },
];

defineProps<{
    users: User[]
}>();

</script>

<template>

    <Head title="User" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">

            <h1 class="text-2xl font-bold">Daftar User</h1>
            <div class="">
                <Link v-if="can('users.create')" href="/users/create" type="button"
                    class="me-2 mb-2 inline-flex items-center rounded-lg bg-gradient-to-r from-green-400 via-green-500 to-green-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-gradient-to-br focus:ring-4 focus:ring-green-300 focus:outline-none dark:focus:ring-green-800">
                <Plus :size="18" :stroke-width="2.5" class="mr-2" />
                Tambah user
                </Link>
            </div>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                ID
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Nama
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Email
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Roles
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users" :key="user.id"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ user.id }}
                            </th>
                            <td class="px-6 py-4">
                                {{ user.name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ user.email }}
                            </td>
                            <td class="px-6 py-4">
                                <span v-for="role in user.roles" :key="role.id">
                                    {{ role.name }}
                                </span>
                            </td>

                            <td class="flex space-x-2 px-6 py-4">
                                <Link :href="route('users.show', user.id)">
                                <Eye class="h-4 w-4" />
                                </Link>
                                <Link :href="route('users.edit', user.id.toString())"
                                    class="inline-flex items-center p-2 text-blue-600 hover:text-blue-800">
                                <Pencil class="h-4 w-4" />
                                </Link>
                            </td>


                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </AppLayout>
</template>
