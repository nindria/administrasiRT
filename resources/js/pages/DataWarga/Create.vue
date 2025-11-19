<script setup lang="ts">
import BaseInput from '@/components/form/BaseInput.vue';
import BaseSelect from '@/components/form/BaseSelect.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
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
    status_kependukan: string;
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
        status_kependukan: '',
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
        status_kependukan: '',
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
    foto_ktp_kepala_keluarga: null as File | null,
});

const kkImagePreview = ref<string | null>(null);

function handleKKImageChange(event: Event) {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];
    if (file) {
        kkForm.foto_ktp_kepala_keluarga = file;
        kkImagePreview.value = URL.createObjectURL(file);
    }
}

const jalanOptions = [
    { value: 'CDN', label: 'Cendana' },
    { value: 'J1', label: 'Jalan 1' },
    { value: 'J2', label: 'Jalan 2' },
    { value: 'J3', label: 'Jalan 3' },
];

const rumahForm = useForm({
    nik: '',
    perumahan: 'GBJ2',
    jalan: '',
    blok: '',
    nomor: '',
});

async function submit() {
    const payload: Record<string, any> = {
        wargas: [...wargaList.value],
    };

    if (isKepalaKeluarga.value) {
        payload.kk = {
            no_kk: kkForm.no_kk?.toString() ?? '',
            jumlah_anggota: kkForm.jumlah_anggota,
            foto_ktp_kepala_keluarga: kkForm.foto_ktp_kepala_keluarga,
        };

        const jalanValue = typeof (rumahForm as any).jalan === 'object' && (rumahForm as any).jalan !== null ? (rumahForm as any).jalan.value : rumahForm.jalan;

        payload.rumah = {
            perumahan: rumahForm.perumahan,
            jalan: jalanValue ?? '',
            blok: rumahForm.blok?.toString() ?? '',
            nomor: rumahForm.nomor?.toString() ?? '',
        };
    }

    const submitForm = useForm(payload);
    console.log(submitForm);

    await submitForm.post(route('datawarga.storeMultiple'), {
        preserveScroll: true,
        onSuccess: () => {
            // Reset all forms
            submitForm.reset();
            kkForm.reset();
            rumahForm.reset();
            kkImagePreview.value = null;
            wargaList.value = [
                {
                    nik: '',
                    full_name: '',
                    tempat_lahir: '',
                    tanggal_lahir: '',
                    status: '',
                    status_kependukan: '',
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

                            <BaseSelect
                                label="Status Kependukan"
                                :name="`status_kependukan_${index}`"
                                v-model:value="warga.status_kependukan"
                                :options="[
                                    { value: 'tetap', label: 'Tetap' },
                                    { value: 'tidak tetap', label: 'Tidak Tetap' },
                                ]"
                                placeholder="Pilih status kependukan"
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

                                    <div class="grid">
                                        <Label class="mb-2" :for="`foto_ktp_kepala_keluarga_${index}`">Foto KTP Kepala Keluarga</Label>
                                        <Input
                                            :id="`foto_ktp_kepala_keluarga_${index}`"
                                            :name="`foto_ktp_kepala_keluarga_${index}`"
                                            type="file"
                                            accept="image/*"
                                            @input="kkForm.foto_ktp_kepala_keluarga = ($event.target as HTMLInputElement)?.files?.[0] || null"
                                            @change="handleKKImageChange"
                                            class="mt-1 block w-full"
                                        />
                                        <p class="mt-1 text-sm text-gray-500">Format yang didukung: JPG, JPEG, PNG. Ukuran maksimal: 2MB</p>
                                        <div v-if="kkImagePreview" class="mt-2">
                                            <img :src="kkImagePreview" alt="Preview" class="h-32 w-32 rounded border object-cover" />
                                        </div>
                                        <InputError :message="kkForm.errors.foto_ktp_kepala_keluarga" />
                                    </div>
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
                                    <BaseSelect
                                        label="Nama Jalan"
                                        :name="`jalan_${index}`"
                                        v-model:value="rumahForm.jalan"
                                        :options="jalanOptions"
                                        placeholder="Pilih nama jalan"
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
                                Kembali
                            </Button>
                        </Link>
                        <Button class="w-24" type="submit"> Simpan </Button>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
