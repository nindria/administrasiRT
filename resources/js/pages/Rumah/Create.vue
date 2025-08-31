<script setup lang="ts">
import BaseInput from '@/components/form/BaseInput.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ChevronLeft } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Buat Rumah',
        href: '/norumah/create',
    },
];

const form = useForm<{
    id_rumah: string;
    nik: string;
    perumahan: string;
    jalan: string;
    blok: string;
    nomor: string;
}>({
    id_rumah: '',
    nik: '',
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
    <Head title="Buat Rumah" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <h1 class="text-2xl font-bold">Tambah Data Rumah</h1>
            <form @submit.prevent="submit">
                <div class="grid gap-4">
                    <BaseInput
                        label="NIK Kepala Keluarga"
                        name="nik"
                        v-model:value="form.nik"
                        placeholder="Ketik NIK kepala keluarga"
                        :message="form.errors.nik"
                    />
                    <BaseInput
                        label="Nama Perumahan"
                        name="perumahan"
                        v-model:value="form.perumahan"
                        placeholder="Ketik nama perumahanmu"
                        :message="form.errors.perumahan"
                    />
                    <BaseInput
                        label="Nama Jalan"
                        name="jalan"
                        v-model:value="form.jalan"
                        placeholder="Ketik nama jalanmu"
                        :message="form.errors.jalan"
                    />
                    <BaseInput label="Blok" name="blok" v-model:value="form.blok" placeholder="Ketik blok rumahmu" :message="form.errors.blok" />
                    <BaseInput label="Nomor" name="nomor" v-model:value="form.nomor" placeholder="Ketik nomor rumahmu" :message="form.errors.nomor" />

                    <div class="flex justify-end gap-2">
                        <Link href="/rumah"
                            ><Button
                                class="inline-flex w-24 bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 text-white hover:bg-gradient-to-br focus:ring-4 focus:ring-blue-300 focus:outline-none dark:focus:ring-blue-800"
                                type="button"
                            >
                                <ChevronLeft class="h-4 w-4" />
Kembali
                            </Button></Link
                        >
                        <Button class="w-24" type="submit" :disabled="form.processing">Simpan</Button>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
