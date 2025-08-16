<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { ChevronLeft  } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create',
        href: '/norumah/create',
    },
];

const form = useForm({
    name: "",
})

function submit() {
    form.post(route('norumah.store'), {
        onSuccess: () => form.reset()
    });
}
</script>

<template>

    <Head title="No Rumah" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class=" flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <div class="pb-4">
                <Link href="/norumah" type="button"
                    class="inline-flex items-center text-white bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                <ChevronLeft class="w-4 h-4 mr-2"/>
                Back
                </Link>
            </div>
            <form @submit.prevent="submit">
                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input id="name" type="text" class="mt-1 block w-full" v-model="form.name" placeholder="Name" />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>
                <div class="py-6 flex items-center gap-4">
                    <Button :disabled="form.processing">Submit</Button>
                </div>
            </form>

        </div>
    </AppLayout>
</template>
