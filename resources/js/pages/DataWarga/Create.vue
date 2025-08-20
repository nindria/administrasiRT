<script setup lang="ts">
import BaseInput from '@/components/form/BaseInput.vue';
import BaseSelect from '@/components/form/BaseSelect.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ChevronLeft } from 'lucide-vue-next';

defineProps<{
    noRumahs: Array<{ id_rumah: string; name: string }>;
    kks: Array<{ no_kk: string; nama_kepala_keluarga: string }>;
    wargas: Array<{ nik: string; full_name: string; tempat_lahir: string; tanggal_lahir: string; status: string; no_kk: string; id_rumah: string }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create',
        href: '/datawarga/create',
    },
];

const form = useForm<{
    nik: string;
    id_rumah: string;
    full_name: string;
    tempat_lahir: string;
    tanggal_lahir: string;
    status: string;
}>({
    nik: '',
    id_rumah: '',
    full_name: '',
    tempat_lahir: '',
    tanggal_lahir: '',
    status: '',
});

function submit() {
    form.post(route('datawarga.store'), {
        onSuccess: () => form.reset(),
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="Data Warga" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <h1 class="text-2xl font-bold">Tambah Data Warga</h1>
            <form @submit.prevent="submit">
                <div class="grid gap-4">
                    <BaseInput
                        label="NIK"
                        name="nik"
                        v-model:value="form.nik"
                        :message="form.errors.nik"
                        type="text"
                        maxlength="16"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        inputmode="numeric"
                        placeholder="Masukkan NIK"
                    />

                    <BaseInput
                        label="Nama Lengkap"
                        name="full_name"
                        v-model:value="form.full_name"
                        :message="form.errors.full_name"
                        placeholder="Masukkan nama lengkap"
                    />

                    <BaseInput
                        label="Tempat Lahir"
                        name="tempat_lahir"
                        v-model:value="form.tempat_lahir"
                        :message="form.errors.tempat_lahir"
                        placeholder="Masukkan tempat lahir"
                    />

                    <BaseInput
                        label="Tanggal Lahir"
                        name="tanggal_lahir"
                        v-model:value="form.tanggal_lahir"
                        :message="form.errors.tanggal_lahir"
                        type="date"
                        onclick="this.showPicker()"
                    />

                    <BaseSelect
                        label="Status"
                        name="status"
                        v-model:value="form.status"
                        :message="form.errors.status"
                        :options="[
                            { value: 'kepala keluarga', label: 'Kepala Keluarga' },
                            { value: 'istri', label: 'Istri' },
                            { value: 'anak', label: 'Anak' },
                        ]"
                        placeholder="Pilih status"
                    />

                    <!-- <BaseInput
                        label="No KK"
                        name="no_kk"
                        v-model:value="form.no_kk"
                        :message="form.errors.no_kk"
                        type="text"
                        @blur="getDataByNik"
                        disabled
                    />

                    <BaseInput
                        label="No Rumah"
                        name="id_rumah"
                        v-model:value="form.id_rumah"
                        :message="form.errors.id_rumah"
                        type="text"
                        @blur="getDataByNik"
                        disabled
                    /> -->

                    <!-- <BaseSelect
                        label="Nomor KK"
                        name="no_kk"
                        v-model:value="form.no_kk"
                        :message="form.errors.no_kk"
                        :options="kks.map((kk) => ({ value: kk.no_kk, label: `${kk.no_kk} - ${kk.nama_kepala_keluarga}` }))"
                        placeholder="Pilih nomor KK"
                    /> -->

                    <!-- <BaseSelect
                        label="No Rumah"
                        name="id_rumah"
                        v-model:value="form.id_rumah"
                        :message="form.errors.id_rumah"
                        :options="noRumahs.map((rumah) => ({ value: rumah.id_rumah, label: `${rumah.perumahan}${rumah.jalan}${rumah.blok}${rumah.nomor}` }))"
                        placeholder="Pilih nomor rumah"
                    /> -->

                    <div class="flex justify-end gap-2">
                        <Link href="/datawarga"
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
