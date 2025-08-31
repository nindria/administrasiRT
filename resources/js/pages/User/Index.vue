<script setup lang="ts">
import Badge from '@/components/ui/badge/Badge.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { can } from '@/lib/can';
import { type BreadcrumbItem, type User } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Pencil, Plus, Trash2 } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Pengguna',
        href: '/users',
    },
];

defineProps<{
    users: User[];
}>();

const confirmDelete = (id: number) => {
    if (confirm('Apakah Anda yakin ingin menghapus user ini?')) {
        router.delete(route('users.destroy', id));
    }
};
</script>

<template>
    <Head title="Pengguna" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <h1 class="text-2xl font-bold">Daftar User</h1>
            <div class="">
                <Link
                    v-if="can('users.create')"
                    href="/users/create"
                    type="button"
                    class="me-2 mb-2 inline-flex items-center rounded-lg bg-gradient-to-r from-green-400 via-green-500 to-green-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-gradient-to-br focus:ring-4 focus:ring-green-300 focus:outline-none dark:focus:ring-green-800"
                >
                    <Plus :size="18" :stroke-width="2.5" class="mr-2" />
                    Tambah User
                </Link>
            </div>
            <div class="relative flex overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-left text-sm text-gray-500 rtl:text-right dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs text-gray-700 uppercase dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">ID</th>
                            <th scope="col" class="px-6 py-3">Nama</th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3">Peran</th>
                            <th scope="col" class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users" :key="user.id" class="border-b bg-white dark:border-gray-700 dark:bg-gray-800">
                            <td class="px-6 py-4">
                                <Badge variant="outline">
                                    {{ user.id }}
                                </Badge>
                            </td>
                            <td class="px-6 py-4">{{ user.name }}</td>
                            <td class="px-6 py-4">{{ user.email }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    <Badge v-for="role in user.roles" :key="role.id">
                                        {{ role.name }}
                                    </Badge>
                                </div>
                            </td>
                            <td class="flex space-x-2 px-6 py-4">
                                <Link :href="route('users.edit', user.id.toString())">
                                    <Button variant="outline" size="sm">
                                        <Pencil class="h-4 w-4" />
                                    </Button>
                                </Link>
                                <Button variant="destructive" size="sm" @click="confirmDelete(user.id)">
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
