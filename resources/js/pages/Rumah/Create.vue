<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ChevronLeft } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create',
        href: '/norumah/create',
    },
];

const form = useForm<{
    id_rumah: string;
    perumahan: string;
    jalan: string;
    blok: string;
    nomor: string;
}>({
    id_rumah: '',
    perumahan: '',
    jalan: '',
    blok: '',
    nomor: '',
});

function submit() {
    form.post(route('rumah.store'), {
        onSuccess: () => form.reset(),
    });
}
</script>

<template>
    <Head title="No Rumah" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <h1 class="text-2xl font-bold">Tambah Data Rumah</h1>
            <form @submit.prevent="submit">
                <div class="grid gap-4">
                    <div class="grid gap-2">
                        <Label for="name">ID Rumah</Label>
                        <Input id="name" type="text" class="mt-1 block w-full" v-model="form.id_rumah" placeholder="Ketik id rumahmu" />
                        <InputError class="mt-2" :message="form.errors.id_rumah" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="name">Nama Perumahan</Label>
                        <Input id="name" type="text" class="mt-1 block w-full" v-model="form.perumahan" placeholder="Ketik nama perumahanmu" />
                        <InputError class="mt-2" :message="form.errors.perumahan" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="name">Nama Jalan</Label>
                        <Input id="name" type="text" class="mt-1 block w-full" v-model="form.jalan" placeholder="Ketik nama jalanmu" />
                        <InputError class="mt-2" :message="form.errors.jalan" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="name">Blok</Label>
                        <Input id="name" type="text" class="mt-1 block w-full" v-model="form.blok" placeholder="Ketik blok rumahmu" />
                        <InputError class="mt-2" :message="form.errors.blok" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="name">Nomor</Label>
                        <Input id="name" type="text" class="mt-1 block w-full" v-model="form.nomor" placeholder="Ketik nomor rumahmu" />
                        <InputError class="mt-2" :message="form.errors.nomor" />
                    </div>

                    <div class="flex justify-end gap-2">
                        <Link href="/rumah"
                            ><Button
                                class="inline-flex w-24 bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 text-white hover:bg-gradient-to-br focus:ring-4 focus:ring-blue-300 focus:outline-none dark:focus:ring-blue-800"
                                type="button"
                            >
                                <ChevronLeft class="h-4 w-4" />
                                Back
                            </Button></Link
                        >
                        <Button class="w-24" type="submit" :disabled="form.processing">Submit</Button>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
