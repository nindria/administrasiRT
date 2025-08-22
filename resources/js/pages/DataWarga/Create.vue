<script setup lang="ts">
import BaseInput from '@/components/form/BaseInput.vue';
import BaseSelect from '@/components/form/BaseSelect.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ChevronLeft } from 'lucide-vue-next';
import { computed, ref } from 'vue';

interface Warga {
    nik: string;
    full_name: string;
    tempat_lahir: string;
    tanggal_lahir: string;
    status: string;
    verification_status: string;
}

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Create', href: '/datawarga/create' }];

const wargaList = ref([
    {
        nik: '',
        full_name: '',
        tempat_lahir: '',
        tanggal_lahir: '',
        status: '',
        verification_status: 'pending',
    },
]);

const activeIndex = ref(0);

function addMoreWarga() {
    wargaList.value.push({
        nik: '',
        full_name: '',
        tempat_lahir: '',
        tanggal_lahir: '',
        status: '',
        verification_status: 'pending',
    });
    activeIndex.value = wargaList.value.length - 1;
}

function removeWarga(index: number) {
    if (wargaList.value.length > 1) {
        wargaList.value.splice(index, 1);
        if (activeIndex.value >= wargaList.value.length) {
            activeIndex.value = wargaList.value.length - 1;
        }
    }
}

const isKepalaKeluarga = computed(() => wargaList.value.some((w: Warga) => w.status === 'kepala keluarga'));

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

async function submit() {
    // Create a new form instance with the current wargaList data
    const submitForm = useForm({
        wargas: [...wargaList.value],
    });

    await submitForm.post(route('datawarga.store'), {
        preserveScroll: true,
        onSuccess: async () => {
            // Simpan NIK kepala keluarga jika ada
            const kepalaKeluarga = wargaList.value.find((w: Warga) => w.status === 'kepala keluarga');

            if (isKepalaKeluarga.value && kepalaKeluarga) {
                // Set NIK dari data warga kepala keluarga
                kkForm.nik = kepalaKeluarga.nik;
                rumahForm.nik = kepalaKeluarga.nik;

                try {
                    // Kirim data KK terlebih dahulu
                    await kkForm.post(route('kartukeluarga.store'), {
                        preserveScroll: true,
                        onSuccess: async () => {
                            await rumahForm.post(route('rumah.store'), {
                                preserveScroll: true,
                                onSuccess: () => {
                                    rumahForm.reset();
                                },
                                onError: (errors) => {
                                    console.error('Error menyimpan data Rumah:', errors);
                                    alert('Error menyimpan data Rumah: ' + JSON.stringify(errors));
                                },
                            });

                            kkForm.reset();
                        },
                        onError: (errors) => {
                            console.error('Error menyimpan data KK:', errors);
                            alert('Error menyimpan data KK: ' + JSON.stringify(errors));
                            throw new Error('Error menyimpan data KK');
                        },
                    });
                } catch (error) {
                    console.error('Terjadi kesalahan:', error);
                }
            }

            // Reset form warga
            submitForm.reset();
            wargaList.value = [
                {
                    nik: '',
                    full_name: '',
                    tempat_lahir: '',
                    tanggal_lahir: '',
                    status: '',
                    verification_status: 'pending',
                },
            ];
        },
        onError: (errors) => {
            console.error('Submission errors:', errors);
        },
    });
}

// watch(
//     () => wargaList.value[activeIndex.value].status,
//     (newValue, oldValue) => {
//         if (newValue !== oldValue) {
//             isKepalaKeluarga.value = newValue === 'kepala keluarga';
//         }
//     },
// );
</script>

<template>
    <Head title="Tambah Data Warga" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <h1 class="text-2xl font-bold">Tambah Data Warga</h1>
            <form @submit.prevent="submit">
                <div class="grid gap-4">
                    <!-- Loop Data Warga -->
                    <div v-for="(warga, index) in wargaList" :key="index" class="mb-4 rounded-lg border p-4">
                        <div class="mb-2 flex items-center justify-between">
                            <h2 class="text-xl font-semibold">Data Warga {{ index + 1 }}</h2>
                            <Button
                                v-if="wargaList.length > 1"
                                type="button"
                                class="bg-red-500 text-white hover:bg-red-600"
                                @click="removeWarga(index)"
                            >
                                Hapus
                            </Button>
                        </div>
                        <div class="grid gap-4">
                            <BaseInput
                                label="NIK"
                                :name="`nik_${index}`"
                                v-model:value="warga.nik"
                                type="text"
                                maxlength="16"
                                inputmode="numeric"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                placeholder="Masukkan NIK"
                            />

                            <BaseInput
                                label="Nama Lengkap"
                                :name="`full_name_${index}`"
                                v-model:value="warga.full_name"
                                placeholder="Masukkan nama lengkap"
                            />

                            <BaseInput
                                label="Tempat Lahir"
                                :name="`tempat_lahir_${index}`"
                                v-model:value="warga.tempat_lahir"
                                placeholder="Masukkan tempat lahir"
                            />

                            <BaseInput
                                label="Tanggal Lahir"
                                :name="`tanggal_lahir_${index}`"
                                v-model:value="warga.tanggal_lahir"
                                type="date"
                                onclick="this.showPicker()"
                            />

                            <BaseSelect
                                label="Status"
                                :name="`status_${index}`"
                                v-model:value="warga.status"
                                :options="[
                                    { value: 'kepala keluarga', label: 'Kepala Keluarga' },
                                    { value: 'istri', label: 'Istri' },
                                    { value: 'anak', label: 'Anak' },
                                ]"
                                placeholder="Pilih status"
                            />

                            <div v-if="isKepalaKeluarga" class="mb-4">
                                <h2 class="mb-4 text-xl font-semibold">Data Kartu Keluarga</h2>
                                <div class="grid gap-4">
                                    <BaseInput
                                        label="Nomor KK"
                                        :name="`no_kk_${index}`"
                                        v-model:value="kkForm.no_kk"
                                        :message="kkForm.errors.no_kk"
                                        type="text"
                                        maxlength="16"
                                        placeholder="Masukkan Nomor KK"
                                    />

                                    <BaseInput
                                        label="Jumlah Anggota"
                                        :name="`jumlah_anggota_${index}`"
                                        v-model:value="kkForm.jumlah_anggota"
                                        :message="kkForm.errors.jumlah_anggota"
                                        type="number"
                                        min="1"
                                        placeholder="Masukkan Jumlah Anggota"
                                    />

                                    <BaseInput
                                        label="Foto KTP Kepala Keluarga"
                                        :name="`foto_ktp_kepala_keluarga_${index}`"
                                        v-model:value="kkForm.foto_ktp_kepala_keluarga"
                                        :message="kkForm.errors.foto_ktp_kepala_keluarga"
                                        type="text"
                                        placeholder="Masukkan URL Foto KTP"
                                    />
                                </div>
                            </div>
                            <div class="mb-4" v-if="isKepalaKeluarga">
                                <h2 class="mb-4 text-xl font-semibold">Data Rumah</h2>
                                <div class="grid gap-4">
                                    <BaseInput
                                        label="Nama Perumahan"
                                        :name="`perumahan_${index}`"
                                        v-model:value="rumahForm.perumahan"
                                        placeholder="Ketik nama perumahanmu"
                                        :message="rumahForm.errors.perumahan"
                                        disabled
                                    />
                                    <BaseInput
                                        label="Nama Jalan"
                                        :name="`jalan_${index}`"
                                        v-model:value="rumahForm.jalan"
                                        placeholder="Ketik nama jalanmu"
                                        :message="rumahForm.errors.jalan"
                                    />
                                    <BaseInput
                                        label="Blok"
                                        :name="`blok_${index}`"
                                        v-model:value="rumahForm.blok"
                                        placeholder="Ketik blok rumahmu"
                                        :message="rumahForm.errors.blok"
                                    />
                                    <BaseInput
                                        label="Nomor"
                                        :name="`nomor_${index}`"
                                        v-model:value="rumahForm.nomor"
                                        placeholder="Ketik nomor rumahmu"
                                        :message="rumahForm.errors.nomor"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Add More -->
                    <Button type="button" class="bg-green-500 text-white hover:bg-green-600" @click="addMoreWarga"> + Tambah Warga </Button>

                    <div class="mt-4 flex justify-end gap-2">
                        <Link href="/datawarga">
                            <Button
                                class="inline-flex w-24 bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 text-white hover:bg-gradient-to-br focus:ring-4 focus:ring-blue-300 focus:outline-none dark:focus:ring-blue-800"
                                type="button"
                            >
                                <ChevronLeft class="h-4 w-4" />
                                Back
                            </Button>
                        </Link>
                        <Button class="w-24" type="submit"> Submit </Button>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
