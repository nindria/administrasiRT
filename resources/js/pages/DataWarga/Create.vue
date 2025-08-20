<script setup lang="ts">
import BaseInput from '@/components/form/BaseInput.vue';
import BaseSelect from '@/components/form/BaseSelect.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ChevronLeft, Trash2 } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create',
        href: '/datawarga/create',
    },
];

// Struktur form: pisahkan data_rumah & warga
const form = useForm({
    data_rumah: {
        perumahan: '',
        jalan: '',
        blok: '',
        nomor: '',
    },
    warga: [
        {
            nik: '',
            full_name: '',
            tempat_lahir: '',
            tanggal_lahir: '',
            status: '',
            no_kk: '',
            jumlah_anggota: '1',
            foto_ktp_kepala_keluarga: '',
        },
    ],
});

function addWarga() {
    form.warga.push({
        nik: '',
        full_name: '',
        tempat_lahir: '',
        tanggal_lahir: '',
        status: '',
        no_kk: '',
        jumlah_anggota: '1',
        foto_ktp_kepala_keluarga: '',
    });
}

function removeWarga(index: number) {
    form.warga.splice(index, 1);
}

function submit() {
    form.post(route('datawarga.store'));
}
</script>

<template>

    <Head title="Data Warga" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 overflow-x-auto rounded-xl bg-white p-4">
            <h1 class="text-2xl font-bold">Tambah Data Warga</h1>
            <form @submit.prevent="submit">
                <div class="grid gap-4">
                    <!-- Data Rumah (hanya 1x, tidak looping) -->
                    <div>
                        <h2 class="mb-4 text-xl font-semibold">Data Rumah</h2>
                        <BaseInput label="Nama Perumahan" name="perumahan" v-model:value="form.data_rumah.perumahan"
                            placeholder="Ketik nama perumahanmu" />
                        <BaseInput label="Nama Jalan" name="jalan" v-model:value="form.data_rumah.jalan"
                            placeholder="Ketik nama jalanmu" />
                        <BaseInput label="Blok" name="blok" v-model:value="form.data_rumah.blok"
                            placeholder="Ketik blok rumahmu" />
                        <BaseInput label="Nomor" name="nomor" v-model:value="form.data_rumah.nomor"
                            placeholder="Ketik nomor rumahmu" />
                    </div>

                    <!-- Data Warga (looping) -->
                    <div v-for="(warga, index) in form.warga" :key="index"
                        class="mb-4 rounded-lg border p-4 bg-white shadow-sm">
                        <h2 class="mb-4 text-xl font-semibold">Data Warga </h2>
                        <BaseInput label="NIK" name="nik" v-model:value="warga.nik" placeholder="Masukkan NIK"/>
                        <BaseInput label="Nama Lengkap" name="full_name" v-model:value="warga.full_name"
                            placeholder="Masukkan nama lengkap" />
                        <BaseInput label="Tempat Lahir" name="tempat_lahir" v-model:value="warga.tempat_lahir"
                            placeholder="Masukkan tempat lahir" />
                        <BaseInput label="Tanggal Lahir" name="tanggal_lahir" v-model:value="warga.tanggal_lahir"
                            type="date" />
                        <BaseSelect label="Status" name="status" v-model:value="warga.status" :options="[
                            { value: 'kepala keluarga', label: 'Kepala Keluarga' },
                            { value: 'istri', label: 'Istri' },
                            { value: 'anak', label: 'Anak' },
                        ]" placeholder="Pilih status" />


                        <div v-if="warga.status === 'kepala keluarga'">
                            <h2 class="mb-4 pt-7 text-xl font-semibold">Data Kartu Keluarga </h2>
                            <BaseInput label="Nomor KK" name="no_kk" v-model:value="warga.no_kk"
                                placeholder="Masukkan Nomor KK" />
                            <BaseInput label="Jumlah Anggota" name="jumlah_anggota" v-model:value="warga.jumlah_anggota"
                                type="number" min="1" placeholder="Masukkan Jumlah Anggota" />
                            <BaseInput label="Foto KTP Kepala Keluarga" name="foto_ktp_kepala_keluarga"
                                v-model:value="warga.foto_ktp_kepala_keluarga" placeholder="Masukkan URL Foto KTP" />
                        </div>

                        <!-- Tombol hapus anggota -->
                        <div class="mt-4 flex justify-end">
                            <button v-if="form.warga.length > 1" type="button"
                                class="flex items-center rounded bg-red-500 px-3 py-1 text-white hover:bg-red-600"
                                @click="removeWarga(index)">
                                <Trash2 class="mr-1 h-4 w-4" /> Hapus Anggota
                            </button>
                        </div>
                    </div>

                    <!-- Tambah warga -->
                    <Button type="button" @click="addWarga">Add More</Button>

                    <!-- Tombol submit -->
                    <div class="flex justify-end gap-2">
                        <Link href="/datawarga">
                        <Button type="button" variant="secondary">
                            <ChevronLeft class="h-4 w-4" />
                            Back
                        </Button>
                        </Link>
                        <Button type="submit" :disabled="form.processing">Submit</Button>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
