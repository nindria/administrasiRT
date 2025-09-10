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
import { ref } from 'vue';

const props = defineProps<{
    kartukeluarga: {
        no_kk: string;
        nik: string;
        jumlah_anggota: string;
        foto_ktp_kepala_keluarga: string;
    };
    wargas: Array<{ nik: string; full_name: string; status: string }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Edit',
        href: '/kartukeluarga/edit',
    },
];

const form = useForm({
    nik: props.kartukeluarga.nik,
    jumlah_anggota: props.kartukeluarga.jumlah_anggota,
    foto_ktp_kepala_keluarga: null as File | null,
});

const imagePreview = ref<string | null>(props.kartukeluarga.foto_ktp_kepala_keluarga || null);

function handleImageChange(event: Event) {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];
    if (file) {
        form.foto_ktp_kepala_keluarga = file;
        imagePreview.value = URL.createObjectURL(file);
    }
}

function submit() {
    form.put(route('kartukeluarga.update', props.kartukeluarga.no_kk), {
        onSuccess: () => {
            form.reset();
            imagePreview.value = null;
        },
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="Kartu Keluarga" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <h1 class="text-2xl font-bold">Edit Data KK</h1>
            <form @submit.prevent="submit">
                <div class="grid gap-4">
                    <BaseInput label="Nomor KK" name="no_kk" :value="kartukeluarga.no_kk" :disabled="true" type="text" maxlength="16" />

                    <BaseSelect
                        label="Kepala Keluarga"
                        name="nik"
                        v-model:value="form.nik"
                        :message="form.errors.nik"
                        :options="
                            wargas
                                .filter((warga) => warga.status === 'Kepala Keluarga')
                                .map((warga) => ({ value: warga.nik, label: `${warga.nik} - ${warga.full_name}` }))
                        "
                        placeholder="Pilih Kepala Keluarga"
                    />

                    <BaseInput
                        label="Jumlah Anggota"
                        name="jumlah_anggota"
                        v-model:value="form.jumlah_anggota"
                        :message="form.errors.jumlah_anggota"
                        type="number"
                        min="1"
                        placeholder="Masukkan Jumlah Anggota"
                    />

                    <div class="grid">
                        <Label class="mb-2" for="foto_ktp_kepala_keluarga">Foto KTP Kepala Keluarga</Label>
                        <Input
                            id="foto_ktp_kepala_keluarga"
                            name="foto_ktp_kepala_keluarga"
                            type="file"
                            accept="image/*"
                            @input="form.foto_ktp_kepala_keluarga = ($event.target as HTMLInputElement)?.files?.[0] || null"
                            @change="handleImageChange"
                            class="mt-1 block w-full"
                        />
                        <p class="mt-1 text-sm text-gray-500">Format yang didukung: JPG, JPEG, PNG. Ukuran maksimal: 2MB</p>
                        <div v-if="imagePreview" class="mt-2">
                            <img :src="imagePreview" alt="Preview" class="h-32 w-32 rounded border object-cover" />
                        </div>
                        <InputError :message="form.errors.foto_ktp_kepala_keluarga" />
                    </div>

                    <div class="flex justify-end gap-2">
                        <Link href="/kartukeluarga"
                            ><Button
                                class="inline-flex w-24 bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 text-white hover:bg-gradient-to-br focus:ring-4 focus:ring-blue-300 focus:outline-none dark:focus:ring-blue-800"
                                type="button"
                            >
                                <ChevronLeft class="h-4 w-4" />
                                Kembali
                            </Button></Link
                        >
                        <Button class="w-24" type="submit" :disabled="form.processing">Perbarui</Button>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
