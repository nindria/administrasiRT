<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Banner',
        href: '/banners',
    },
];

defineProps<{
    banners: Array<{
        id: number;
        title: string;
        description: string;
        image: string;
        is_active: boolean;
        created_at: string;
    }>;
}>();
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Banner Management" />

        <div class="container mx-auto py-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold">Banner Management</h1>
                <Link :href="route('banners.create')">
                    <Button>Add New Banner</Button>
                </Link>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="grid grid-cols-1 gap-4 p-6">
                    <div v-for="banner in banners" :key="banner.id" class="border rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-20 h-12">
                                    <img 
                                        v-if="banner.image" 
                                        :src="`/storage/${banner.image}`" 
                                        :alt="banner.title"
                                        class="w-full h-full object-cover rounded"
                                    />
                                    <div v-else class="w-full h-full bg-gray-200 rounded flex items-center justify-center">
                                        <span class="text-gray-500 text-sm">No image</span>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-semibold">{{ banner.title }}</h3>
                                    <p class="text-sm text-gray-600">{{ banner.description }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span 
                                    class="px-2 py-1 text-xs rounded-full"
                                    :class="banner.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                                >
                                    {{ banner.is_active ? 'Active' : 'Inactive' }}
                                </span>
                                <span class="text-sm text-gray-500">{{ new Date(banner.created_at).toLocaleDateString() }}</span>
                                <Link :href="route('banners.edit', banner.id)">
                                    <Button variant="outline" size="sm">Edit</Button>
                                </Link>
                                <Link :href="route('banners.destroy', banner.id)" method="delete" as="button">
                                    <Button variant="destructive" size="sm">Delete</Button>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
