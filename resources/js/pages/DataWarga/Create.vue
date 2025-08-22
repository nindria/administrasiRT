<script setup lang="ts">
import BaseInput from '@/components/form/BaseInput.vue';
import BaseSelect from '@/components/form/BaseSelect.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ChevronLeft } from 'lucide-vue-next';
import { ref, computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Create', href: '/datawarga/create' },
];

// Data warga bisa banyak
const wargaForm = useForm({
    warga: [
        {
            nik: '',
            full_name: '',
            tempat_lahir: '',
            tanggal_lahir: '',
            status: '',
        },
    ],
});

// Index untuk warga yang lagi diinput
const activeIndex = ref(0);

// Untuk form tambahan KK & Rumah hanya jika kepala keluarga
const kkForm = useForm({
    no_kk: '',
    nik: '',
    jumlah_anggota: '1',
    foto_ktp_kepala_keluarga: '',
});

const rumahForm = useForm({
    nik: '',
    perumahan: 'GBJ2',
    jalan: '',
    blok: '',
    nomor: '',
});

// Tambah warga baru
function addMoreWarga() {
    wargaForm.warga.push({
        nik: '',
        full_name: '',
        tempat_lahir: '',
        tanggal_lahir: '',
        status: '',
    });
    activeIndex.value = wargaForm.warga.length - 1;
}

// Hapus warga
function removeWarga(index: number) {
    if (wargaForm.warga.length > 1) {
        wargaForm.warga.splice(index, 1);
        if (activeIndex.value >= wargaForm.warga.length) {
            activeIndex.value = wargaForm.warga.length - 1;
        }
    }
}

const isKepalaKeluarga = computed(() =>
    wargaForm.warga.some((w) => w.status === 'kepala keluarga')
);

async function submit() {
    await wargaForm.post(route('verifikasi.store'), {
    preserveScroll: true,
    onSuccess: () => wargaForm.reset(),
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
                    <!-- Loop Data Warga -->
                    <div v-for="(warga, index) in wargaForm.warga" :key="index" class="mb-4 rounded-lg border p-4">
                        <div class="flex justify-between items-center mb-2">
                            <h2 class="text-xl font-semibold">Data Warga {{ index + 1 }}</h2>
                            <Button v-if="wargaForm.warga.length > 1" type="button"
                                class="bg-red-500 text-white hover:bg-red-600" @click="removeWarga(index)">
                                Hapus
                            </Button>
                        </div>
                        <div class="grid gap-4">
                            <BaseInput label="NIK" :name="`nik_${index}`" v-model:value="warga.nik" type="text"
                                maxlength="16" inputmode="numeric"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="Masukkan NIK" />

                            <BaseInput label="Nama Lengkap" :name="`full_name_${index}`" v-model:value="warga.full_name"
                                placeholder="Masukkan nama lengkap" />

                            <BaseInput label="Tempat Lahir" :name="`tempat_lahir_${index}`"
                                v-model:value="warga.tempat_lahir" placeholder="Masukkan tempat lahir" />

                            <BaseInput label="Tanggal Lahir" :name="`tanggal_lahir_${index}`"
                                v-model:value="warga.tanggal_lahir" type="date" onclick="this.showPicker()" />

                            <BaseSelect label="Status" :name="`status_${index}`" v-model:value="warga.status" :options="[
                                { value: 'kepala keluarga', label: 'Kepala Keluarga' },
                                { value: 'istri', label: 'Istri' },
                                { value: 'anak', label: 'Anak' },
                            ]" placeholder="Pilih status" />

                            <div v-if="isKepalaKeluarga" class="mb-4 rounded-lg border p-4">
                                <h2 class="mb-4 text-xl font-semibold">Data Kartu Keluarga</h2>
                                <div class="grid gap-4">
                                    <BaseInput label="Nomor KK" :name="`no_kk_${index}`" v-model:value="kkForm.no_kk"
                                        :message="kkForm.errors.no_kk" type="text" maxlength="16"
                                        placeholder="Masukkan Nomor KK" />

                                    <BaseInput label="Jumlah Anggota" :name="`jumlah_anggota_${index}`"
                                        v-model:value="kkForm.jumlah_anggota" :message="kkForm.errors.jumlah_anggota"
                                        type="number" min="1" placeholder="Masukkan Jumlah Anggota" />

                                    <BaseInput label="Foto KTP Kepala Keluarga"
                                        :name="`foto_ktp_kepala_keluarga_${index}`"
                                        v-model:value="kkForm.foto_ktp_kepala_keluarga"
                                        :message="kkForm.errors.foto_ktp_kepala_keluarga" type="text"
                                        placeholder="Masukkan URL Foto KTP" />
                                </div>
                            </div>
                            <div class="mb-4 rounded-lg border p-4" v-if="isKepalaKeluarga">
                                <h2 class="mb-4 text-xl font-semibold">Data Rumah</h2>
                                <div class="grid gap-4">
                                    <BaseInput label="Nama Perumahan" :name="`perumahan_${index}`"
                                        v-model:value="rumahForm.perumahan" placeholder="Ketik nama perumahanmu"
                                        :message="rumahForm.errors.perumahan" disabled />
                                    <BaseInput label="Nama Jalan" :name="`jalan_${index}`"
                                        v-model:value="rumahForm.jalan" placeholder="Ketik nama jalanmu"
                                        :message="rumahForm.errors.jalan" />
                                    <BaseInput label="Blok" :name="`blok_${index}`" v-model:value="rumahForm.blok"
                                        placeholder="Ketik blok rumahmu" :message="rumahForm.errors.blok" />
                                    <BaseInput label="Nomor" :name="`nomor_${index}`" v-model:value="rumahForm.nomor"
                                        placeholder="Ketik nomor rumahmu" :message="rumahForm.errors.nomor" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Add More -->
                    <Button type="button" class="bg-green-500 text-white hover:bg-green-600" @click="addMoreWarga">
                        + Tambah Warga
                    </Button>

                    <div class="flex justify-end gap-2 mt-4">
                        <Link href="/datawarga">
                        <Button
                            class="inline-flex w-24 bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 text-white hover:bg-gradient-to-br focus:ring-4 focus:ring-blue-300 focus:outline-none dark:focus:ring-blue-800"
                            type="button">
                            <ChevronLeft class="h-4 w-4" />
                            Back
                        </Button>
                        </Link>
                        <Button class="w-24" type="submit" :disabled="wargaForm.processing">Submit</Button>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
