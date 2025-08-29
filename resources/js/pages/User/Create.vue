<script setup lang="ts">
import BaseInput from '@/components/form/BaseInput.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ChevronLeft } from 'lucide-vue-next';
import Label from '@/components/ui/label/Label.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'User Create',
        href: '/users',
    },
];

defineProps<{
    "roles": { id: number; name: string }[]
}>()

const form = useForm({
    "name": "",
    "email": "",
    "password": "",
    "roles": []
});
</script>

<template>

    <Head title="User" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">

            <h1 class="text-2xl font-bold">Create User</h1>

            <form @submit.prevent="form.post('/users')" class="grid gap-4">

                <BaseInput label="Your name" name="name" v-model:value="form.name" placeholder="Name"
                    :message="form.errors.name" />
                <BaseInput label="Your Email" name="email" v-model:value="form.email" placeholder="Email"
                    :message="form.errors.email" />
                <BaseInput label="Password" name="password" v-model:value="form.password" placeholder="Password"
                    :message="form.errors.password" />
                <Label class="mb-2">Roles:</Label>
                <div class="grid grid-cols-2 gap-2">
                    <Label v-for="role in roles" :key="role.id" class="flex items-center space-x-2">
                        <input type="checkbox"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                            :value="role.id" v-model="form.roles" />
                        <span class="text-gray-800 capitalize">{{ role.name }}</span>
                        <!-- error message -->
                    </Label>
                </div>
                <div class="mt-4 flex justify-end gap-2">
                    <Link href="/users"
                        class="inline-flex items-center w-24 bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 text-white hover:bg-gradient-to-br focus:ring-4 focus:ring-blue-300 focus:outline-none dark:focus:ring-blue-800 rounded-lg px-4 py-2">
                    <ChevronLeft class="h-4 w-4 mr-1" />
                    Back
                    </Link>
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Submit
                    </button>

                </div>
            </form>



        </div>
    </AppLayout>
</template>
