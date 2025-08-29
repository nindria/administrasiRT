<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type User } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { ChevronLeft } from 'lucide-vue-next';

defineProps<{
  user: User
}>();

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'User',
    href: '/users',
  },
  
];
</script>

<template>
  <Head :title="`User - ${user.name}`" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6 overflow-x-auto bg-white shadow-md">
      
      <h1 class="text-2xl font-bold">Detail User</h1>

      <div class="grid gap-4">
        <div>
          <span class="font-semibold text-gray-700">ID:</span>
          <span class="ml-2">{{ user.id }}</span>
        </div>
        <div>
          <span class="font-semibold text-gray-700">Nama:</span>
          <span class="ml-2">{{ user.name }}</span>
        </div>
        <div>
          <span class="font-semibold text-gray-700">Email:</span>
          <span class="ml-2">{{ user.email }}</span>
        </div>
        <div>
          <span class="font-semibold text-gray-700">Dibuat pada:</span>
          <span class="ml-2">{{ user.created_at }}</span>
        </div>
        <div>
          <span class="font-semibold text-gray-700">Terakhir diupdate:</span>
          <span class="ml-2">{{ user.updated_at }}</span>
        </div>
      </div>

      <div class="flex gap-2">
        <Link href="/users"
          class="inline-flex items-center w-24 bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 text-white hover:bg-gradient-to-br focus:ring-4 focus:ring-blue-300 focus:outline-none dark:focus:ring-blue-800 rounded-lg px-4 py-2">
          <ChevronLeft class="h-4 w-4 mr-1" />
          Back
        </Link>
      </div>

    </div>
  </AppLayout>
</template>
