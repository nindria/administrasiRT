<script setup lang="ts">
import BaseInput from '@/components/form/BaseInput.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ChevronLeft } from 'lucide-vue-next';

defineProps<{
    wargas: Array<{ nik: string; full_name: string; status: string }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create',
        href: '/kartukeluarga/create',
    },
];

const form = useForm<{
    no_kk: string;
    nik: string;
    jumlah_anggota: string;
    foto_ktp_kepala_keluarga: string;
}>({
    no_kk: '',
    nik: '',
    jumlah_anggota: '',
    foto_ktp_kepala_keluarga: '',
});

function submit() {
    form.post(route('kartukeluarga.store'), {
        onSuccess: () => form.reset(),
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="Kartu Keluarga" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <h1 class="text-2xl font-bold">Tambah Kartu Keluarga</h1>
            <form @submit.prevent="submit">
                <div class="grid gap-4">
                    <BaseInput
                        label="Nomor KK"
                        name="no_kk"
                        v-model:value="form.no_kk"
                        :message="form.errors.no_kk"
                        type="text"
                        maxlength="16"
                        placeholder="Masukkan Nomor KK"
                    />

                    <BaseInput
                        label="NIK Kepala Keluarga"
                        name="nik"
                        v-model:value="form.nik"
                        :message="form.errors.nik"
                        type="text"
                        maxlength="16"
                        placeholder="Masukkan NIK Kepala Keluarga"
                    />

                    <!-- <BaseSelect
                        label="Kepala Keluarga"
                        name="nik"
                        v-model:value="form.nik"
                        :message="form.errors.nik"
                        :options="
                            wargas
                                .filter((warga) => warga.status === 'kepala keluarga')
                                .map((warga) => ({ value: warga.nik, label: `${warga.nik} - ${warga.full_name}` }))
                        "
                        placeholder="Pilih Kepala Keluarga"
                    /> -->

                    <BaseInput
                        label="Jumlah Anggota"
                        name="jumlah_anggota"
                        v-model:value="form.jumlah_anggota"
                        :message="form.errors.jumlah_anggota"
                        type="number"
                        min="1"
                        placeholder="Masukkan Jumlah Anggota"
                    />

                    <BaseInput
                        label="Foto KTP Kepala Keluarga"
                        name="foto_ktp_kepala_keluarga"
                        v-model:value="form.foto_ktp_kepala_keluarga"
                        :message="form.errors.foto_ktp_kepala_keluarga"
                        type="text"
                        placeholder="Masukkan URL Foto KTP"
                    />

                    <div class="flex justify-end gap-2">
                        <Link href="/kartukeluarga"
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
