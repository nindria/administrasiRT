<script setup lang="ts">
import BaseInput from '@/components/form/BaseInput.vue';
import InputError from '@/components/InputError.vue';
import { Input } from '@/components/ui/input';
import Label from '@/components/ui/label/Label.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ChevronLeft, Eye, EyeOff } from 'lucide-vue-next';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'User Edit',
        href: '/users',
    },
];

const props = defineProps<{
    user: any;
    roles: { id: number; name: string }[];
    userRoles: number[];
}>();

const form = useForm({
    name: props.user?.name || '',
    email: props.user?.email || '',
    password: '',
    roles: props.userRoles || [],
});

const showPassword = ref(false);

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
};
</script>

<template>
    <Head title="User Edit" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <h1 class="text-2xl font-bold">Edit User</h1>

            <form @submit.prevent="form.put(`/users/${props.user?.id}`)" class="grid gap-4">
                <BaseInput label="Your name" name="name" v-model:value="form.name" placeholder="Name" :message="form.errors.name" />
                <BaseInput label="Your Email" name="email" v-model:value="form.email" placeholder="Email" :message="form.errors.email" />
                <div>
                    <Label for="password" class="mb-2">Password</Label>
                    <div class="relative">
                        <Input
                            id="password"
                            :type="showPassword ? 'text' : 'password'"
                            v-model="form.password"
                            placeholder="Password"
                            class="pr-10"
                        />
                        <button
                            type="button"
                            @click="togglePasswordVisibility"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600"
                        >
                            <Eye v-if="!showPassword" class="h-5 w-5" />
                            <EyeOff v-else class="h-5 w-5" />
                        </button>
                    </div>
                    <InputError :message="form.errors.password" />
                </div>

                <div>
                    <Label class="mb-2">Roles:</Label>
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
                        Back
                    </Link>
                    <button
                        type="submit"
                        class="w-full rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 focus:outline-none sm:w-auto dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    >
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
