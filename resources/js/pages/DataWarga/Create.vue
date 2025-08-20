<script setup lang="ts">
import BaseInput from '@/components/form/BaseInput.vue';
import BaseSelect from '@/components/form/BaseSelect.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ChevronLeft } from 'lucide-vue-next';
import { ref, watch } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create',
        href: '/datawarga/create',
    },
];

// Status kepala keluarga untuk menampilkan form KK
const isKepalaKeluarga = ref(false);

// Form untuk data warga (semua status)
const wargaForm = useForm({
    nik: '',
    full_name: '',
    tempat_lahir: '',
    tanggal_lahir: '',
    status: '',
});

// Form untuk data kartu keluarga (hanya kepala keluarga)
const kkForm = useForm({
    no_kk: '',
    nik: '', // akan diisi dari wargaForm.nik setelah warga disimpan
    jumlah_anggota: '1',
    foto_ktp_kepala_keluarga: '',
});

// Form untuk data rumah (hanya kepala keluarga)
const rumahForm = useForm({
    nik: '', // akan diisi dari wargaForm.nik setelah warga disimpan
    perumahan: 'GBJ2',
    jalan: '',
    blok: '',
    nomor: '',
});

// Watch perubahan status untuk menampilkan/menyembunyikan form KK
watch(
    () => wargaForm.status,
    (newValue) => {
        isKepalaKeluarga.value = newValue === 'kepala keluarga';
    },
);

async function submit() {
    // Simpan data warga terlebih dahulu
    await wargaForm.post(route('datawarga.store'), {
        preserveScroll: true,
        onSuccess: async () => {
            // Jika status kepala keluarga, kirim data KK dan Rumah
            if (isKepalaKeluarga.value) {
                // Set NIK dari data warga yang baru disimpan
                kkForm.nik = wargaForm.nik;
                rumahForm.nik = wargaForm.nik;

                // Kirim data KK
                try {
                    // Kirim data Rumah
                    await rumahForm.post(route('rumah.store'), {
                        preserveScroll: true,
                        onSuccess: async () => {
                            await kkForm.post(route('kartukeluarga.store'), {
                                preserveScroll: true,
                                onSuccess: () => kkForm.reset(),
                                onError: (errors) => {
                                    console.error('Error menyimpan data KK:', errors);

                                    alert('Error menyimpan data KK: ' + JSON.stringify(errors));
                                },
                            });

                            rumahForm.reset();
                        },
                        onError: (errors) => {
                            console.error('Error menyimpan data Rumah:', errors);
                        },
                    });

                    // Reset semua form setelah semua berhasil
                    wargaForm.reset();
                } catch (error) {
                    console.error('Terjadi kesalahan:', error);
                }
            } else {
                // Reset hanya form warga jika bukan kepala keluarga
                wargaForm.reset();
            }
        },
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
                    <!-- Form Data Warga -->
                    <div class="mb-4 rounded-lg border p-4">
                        <h2 class="mb-4 text-xl font-semibold">Data Warga</h2>
                        <div class="grid gap-4">
                            <BaseInput
                                label="NIK"
                                name="nik"
                                v-model:value="wargaForm.nik"
                                :message="wargaForm.errors.nik"
                                type="text"
                                maxlength="16"
                                inputmode="numeric"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                placeholder="Masukkan NIK"
                            />

                            <BaseInput
                                label="Nama Lengkap"
                                name="full_name"
                                v-model:value="wargaForm.full_name"
                                :message="wargaForm.errors.full_name"
                                placeholder="Masukkan nama lengkap"
                            />

                            <BaseInput
                                label="Tempat Lahir"
                                name="tempat_lahir"
                                v-model:value="wargaForm.tempat_lahir"
                                :message="wargaForm.errors.tempat_lahir"
                                placeholder="Masukkan tempat lahir"
                            />

                            <BaseInput
                                label="Tanggal Lahir"
                                name="tanggal_lahir"
                                v-model:value="wargaForm.tanggal_lahir"
                                :message="wargaForm.errors.tanggal_lahir"
                                type="date"
                                onclick="this.showPicker()"
                            />

                            <BaseSelect
                                label="Status"
                                name="status"
                                v-model:value="wargaForm.status"
                                :message="wargaForm.errors.status"
                                :options="[
                                    { value: 'kepala keluarga', label: 'Kepala Keluarga' },
                                    { value: 'istri', label: 'Istri' },
                                    { value: 'anak', label: 'Anak' },
                                ]"
                                placeholder="Pilih status"
                            />
                        </div>
                    </div>

                    <!-- Form Kartu Keluarga (hanya muncul jika status = kepala keluarga) -->
                    <div v-if="isKepalaKeluarga" class="mb-4 rounded-lg border p-4">
                        <h2 class="mb-4 text-xl font-semibold">Data Kartu Keluarga</h2>
                        <div class="grid gap-4">
                            <BaseInput
                                label="Nomor KK"
                                name="no_kk"
                                v-model:value="kkForm.no_kk"
                                :message="kkForm.errors.no_kk"
                                type="text"
                                maxlength="16"
                                placeholder="Masukkan Nomor KK"
                            />

                            <BaseInput
                                label="Jumlah Anggota"
                                name="jumlah_anggota"
                                v-model:value="kkForm.jumlah_anggota"
                                :message="kkForm.errors.jumlah_anggota"
                                type="number"
                                min="1"
                                placeholder="Masukkan Jumlah Anggota"
                            />

                            <BaseInput
                                label="Foto KTP Kepala Keluarga"
                                name="foto_ktp_kepala_keluarga"
                                v-model:value="kkForm.foto_ktp_kepala_keluarga"
                                :message="kkForm.errors.foto_ktp_kepala_keluarga"
                                type="text"
                                placeholder="Masukkan URL Foto KTP"
                            />
                        </div>
                    </div>

                    <!-- Form Data Rumah -->
                    <div class="mb-4 rounded-lg border p-4" v-if="isKepalaKeluarga">
                        <h2 class="mb-4 text-xl font-semibold">Data Rumah</h2>
                        <div class="grid gap-4">
                            <BaseInput
                                label="Nama Perumahan"
                                name="perumahan"
                                v-model:value="rumahForm.perumahan"
                                placeholder="Ketik nama perumahanmu"
                                :message="rumahForm.errors.perumahan"
                                disabled
                            />
                            <BaseInput
                                label="Nama Jalan"
                                name="jalan"
                                v-model:value="rumahForm.jalan"
                                placeholder="Ketik nama jalanmu"
                                :message="rumahForm.errors.jalan"
                            />
                            <BaseInput
                                label="Blok"
                                name="blok"
                                v-model:value="rumahForm.blok"
                                placeholder="Ketik blok rumahmu"
                                :message="rumahForm.errors.blok"
                            />
                            <BaseInput
                                label="Nomor"
                                name="nomor"
                                v-model:value="rumahForm.nomor"
                                placeholder="Ketik nomor rumahmu"
                                :message="rumahForm.errors.nomor"
                            />
                        </div>
                    </div>

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
                        <Button class="w-24" type="submit" :disabled="wargaForm.processing || kkForm.processing || rumahForm.processing"
                            >Submit</Button
                        >
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
