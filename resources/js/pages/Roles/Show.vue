<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type Role } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { ChevronLeft } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'User',
        href: '/users',
    },
];

defineProps<{
    role: Role;
    rolePermissions: string[];
}>();
</script>

<template>
    <Head :title="`Role - ${role.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-xl bg-white p-6 shadow-md">
            <h1 class="text-2xl font-bold">Detail Role</h1>

            <div class="grid gap-4">
                <div>
                    <span class="font-semibold text-gray-700">Nama:</span>
                    <span class="ml-2">{{ role.name }}</span>
                </div>
                <div>
                    <span class="font-semibold text-gray-700">Permission:</span>
                    <span v-for="permission in rolePermissions" :key="permission" class="ml-2">
                        {{ permission }}
                    </span>
                </div>
                <div>
                    <span class="font-semibold text-gray-700">Dibuat pada:</span>
                    <span class="ml-2">{{ role.created_at }}</span>
                </div>
                <div>
                    <span class="font-semibold text-gray-700">Terakhir diupdate:</span>
                    <span class="ml-2">{{ role.updated_at }}</span>
                </div>
            </div>

            <div class="flex gap-2">
                <Link
                    href="/roles"
                    class="inline-flex w-24 items-center rounded-lg bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 px-4 py-2 text-white hover:bg-gradient-to-br focus:ring-4 focus:ring-blue-300 focus:outline-none dark:focus:ring-blue-800"
                >
                    <ChevronLeft class="mr-1 h-4 w-4" />
                    Back
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
