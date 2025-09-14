<script setup lang="ts">
import BaseInput from '@/components/form/BaseInput.vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import Label from '@/components/ui/label/Label.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ChevronLeft } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tambah Data User',
        href: '/users',
    },
];

const props = defineProps<{
    roles: { id: number; name: string }[];
}>();

const form = useForm({
    name: '',
    email: '',
    password: '',
    roles: [],
});
</script>

<template>
    <Head title="Tambah Data User" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <h1 class="text-2xl font-bold">Tambah Data User</h1>

            <form @submit.prevent="form.post('/users')" class="grid gap-4">
                <BaseInput label="Nama Anda" name="name" v-model:value="form.name" placeholder="Nama" :message="form.errors.name" />
                <BaseInput label="Email Anda" name="email" v-model:value="form.email" placeholder="Email" :message="form.errors.email" />
                <div>
                    <Label for="password" class="mb-2">Kata Sandi</Label>
                    <PasswordInput id="password" v-model="form.password" placeholder="Kata Sandi" autocomplete="new-password" />
                    <InputError :message="form.errors.password" />
                </div>
                <div>
                    <Label class="mb-2">Peran:</Label>
                    <div class="grid grid-cols-2 gap-2">
                        <Label v-for="role in props.roles" :key="role.id" class="flex items-center space-x-2">
                            <input
                                type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600"
                                :value="role.id"
                                v-model="form.roles"
                            />
                            <span class="text-gray-800 capitalize">{{ role.name }}</span>
                        </Label>
                    </div>
                    <div v-if="form.errors.roles" class="mt-1 text-sm text-red-600">
                        {{ form.errors.roles }}
                    </div>
                </div>
                <div class="mt-4 flex justify-end gap-2">
                    <Link
                        href="/users"
                        class="inline-flex w-24 items-center rounded-lg bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 px-4 py-2 text-white hover:bg-gradient-to-br focus:ring-4 focus:ring-blue-300 focus:outline-none dark:focus:ring-blue-800"
                    >
                        <ChevronLeft class="mr-1 h-4 w-4" />
                        Kembali
                    </Link>
                    <button
                        type="submit"
                        class="w-full rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 focus:outline-none sm:w-auto dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    >
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
