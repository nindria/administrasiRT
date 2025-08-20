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

const form = useForm({
    // Data Warga
    nik: '',
    full_name: '',
    tempat_lahir: '',
    tanggal_lahir: '',
    status: '',

    // Data Kartu Keluarga
    no_kk: '',
    jumlah_anggota: '1',
    foto_ktp_kepala_keluarga: '',

    // Data Rumah
    perumahan: '',
    jalan: '',
    blok: '',
    nomor: '',
});

// Watch perubahan status untuk menampilkan/menyembunyikan form KK
watch(() => form.status, (newValue) => {
    isKepalaKeluarga.value = newValue === 'kepala keluarga';
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
                    <!-- Form Data Warga -->
                    <div class="border p-4 rounded-lg mb-4">
                        <h2 class="text-xl font-semibold mb-4">Data Warga</h2>
                        <div class="grid gap-4">
                            <BaseInput
                                label="NIK"
                                name="nik"
                                v-model:value="form.nik"
                                :message="form.errors.nik"
                                type="text"
                                inputmode="numeric"
                                oninput="if(this.value.length > 16) this.value = this.value.slice(0, 16)"
                                placeholder="Masukkan NIK"
                            />

                            <BaseInput label="Nama Lengkap" name="full_name" v-model:value="form.full_name"
                                :message="form.errors.full_name" placeholder="Masukkan nama lengkap" />

                            <BaseInput label="Tempat Lahir" name="tempat_lahir" v-model:value="form.tempat_lahir"
                                :message="form.errors.tempat_lahir" placeholder="Masukkan tempat lahir" />

                            <BaseInput label="Tanggal Lahir" name="tanggal_lahir" v-model:value="form.tanggal_lahir"
                                :message="form.errors.tanggal_lahir" type="date" onclick="this.showPicker()" />

                            <BaseSelect label="Status" name="status" v-model:value="form.status"
                                :message="form.errors.status" :options="[
                                    { value: 'kepala keluarga', label: 'Kepala Keluarga' },
                                    { value: 'istri', label: 'Istri' },
                                    { value: 'anak', label: 'Anak' },
                                ]" placeholder="Pilih status" />
                        </div>
                    </div>

                    <!-- Form Kartu Keluarga (hanya muncul jika status = kepala keluarga) -->
                    <div v-if="isKepalaKeluarga" class="border p-4 rounded-lg mb-4">
                        <h2 class="text-xl font-semibold mb-4">Data Kartu Keluarga</h2>
                        <div class="grid gap-4">
                            <BaseInput label="Nomor KK" name="no_kk" v-model:value="form.no_kk"
                                :message="form.errors.no_kk" type="text" maxlength="16"
                                placeholder="Masukkan Nomor KK" />

                            <BaseInput label="Jumlah Anggota" name="jumlah_anggota" v-model:value="form.jumlah_anggota"
                                :message="form.errors.jumlah_anggota" type="number" min="1"
                                placeholder="Masukkan Jumlah Anggota" />

                            <BaseInput label="Foto KTP Kepala Keluarga" name="foto_ktp_kepala_keluarga"
                                v-model:value="form.foto_ktp_kepala_keluarga"
                                :message="form.errors.foto_ktp_kepala_keluarga" type="text"
                                placeholder="Masukkan URL Foto KTP" />
                        </div>
                    </div>

                    <!-- Form Data Rumah -->
                    <div class="border p-4 rounded-lg mb-4">
                        <h2 class="text-xl font-semibold mb-4">Data Rumah</h2>
                        <div class="grid gap-4">
                            <BaseInput label="Nama Perumahan" name="perumahan" v-model:value="form.perumahan"
                                placeholder="Ketik nama perumahanmu" :message="form.errors.perumahan" />
                            <BaseInput label="Nama Jalan" name="jalan" v-model:value="form.jalan"
                                placeholder="Ketik nama jalanmu" :message="form.errors.jalan" />
                            <BaseInput label="Blok" name="blok" v-model:value="form.blok"
                                placeholder="Ketik blok rumahmu" :message="form.errors.blok" />
                            <BaseInput label="Nomor" name="nomor" v-model:value="form.nomor"
                                placeholder="Ketik nomor rumahmu" :message="form.errors.nomor" />
                        </div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <Link href="/datawarga"><Button
                            class="inline-flex w-24 bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 text-white hover:bg-gradient-to-br focus:ring-4 focus:ring-blue-300 focus:outline-none dark:focus:ring-blue-800"
                            type="button">
                            <ChevronLeft class="h-4 w-4" />
                            Back
                        </Button></Link>
                        <Button class="w-24" type="submit" :disabled="form.processing">Submit</Button>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
