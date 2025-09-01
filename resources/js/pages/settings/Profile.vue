<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

import DeleteUser from '@/components/DeleteUser.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import BaseSelect from '@/components/form/BaseSelect.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem, type User } from '@/types';

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
    wargaData?: {
        nik: string;
        full_name: string;
        tempat_lahir?: string;
        tanggal_lahir?: string;
        status?: string;
    };
    kartuKeluargaData?: {
        no_kk: string;
        jumlah_anggota: number;
        foto_ktp_kepala_keluarga?: string;
    };
    rumahData?: {
        id_rumah: string;
        perumahan: string;
        jalan: string;
        blok: string;
        nomor: string;
    };
}

const props = defineProps<Props>();

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Pengaturan Profil',
        href: '/settings/profile',
    },
];

const page = usePage();
const user = page.props.auth.user as User;

const form = useForm({
    name: user.name,
    email: user.email,
    wargaData: {
        nik: props.wargaData?.nik || '',
        full_name: props.wargaData?.full_name || '',
        tempat_lahir: props.wargaData?.tempat_lahir || '',
        tanggal_lahir: props.wargaData?.tanggal_lahir || '',
        status: props.wargaData?.status || '',
    },
    kartuKeluargaData: {
        no_kk: props.kartuKeluargaData?.no_kk || '',
        jumlah_anggota: props.kartuKeluargaData?.jumlah_anggota || 1,
        foto_ktp_kepala_keluarga: null as File | null,
    },
    rumahData: {
        id_rumah: props.rumahData?.id_rumah || '',
        perumahan: props.rumahData?.perumahan || 'GBJ2',
        jalan: props.rumahData?.jalan || '',
        blok: props.rumahData?.blok || '',
        nomor: props.rumahData?.nomor || '',
    },
});

const imagePreview = ref<string | null>(props.kartuKeluargaData?.foto_ktp_kepala_keluarga || null);

function handleImageChange(event: Event) {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];
    if (file) {
        form.kartuKeluargaData.foto_ktp_kepala_keluarga = file;
        imagePreview.value = URL.createObjectURL(file);
    }
}

const submit = () => {
    // Prepare data to send, only include non-empty sections
    const dataToSend: any = {
        name: form.name,
        email: form.email,
    };

    // Only include wargaData if NIK and full_name are filled
    if (form.wargaData.nik && form.wargaData.full_name) {
        dataToSend.wargaData = form.wargaData;
    }

    // Only include kartuKeluargaData if no_kk is filled
    if (form.kartuKeluargaData.no_kk) {
        dataToSend.kartuKeluargaData = form.kartuKeluargaData;
    }

    // Only include rumahData if jalan is filled
    if (form.rumahData.jalan) {
        dataToSend.rumahData = form.rumahData;
    }

    form.transform(() => dataToSend).patch(route('profile.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Pengaturan Profil" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Informasi Profil" description="Perbarui nama dan alamat email Anda" />

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid gap-2">
                        <Label for="name">Nama</Label>
                        <Input id="name" class="mt-1 block w-full" v-model="form.name" required autocomplete="name" placeholder="Nama lengkap" />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">Alamat Email</Label>
                        <Input
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            required
                            autocomplete="username"
                            placeholder="Alamat email"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <!-- Warga Data Section -->
                    <div v-if="user.roles?.some(role => role.name.toLowerCase() === 'warga')" class="space-y-6">
                        <HeadingSmall title="Data Warga" description="Perbarui informasi data warga Anda" />

                        <div class="grid gap-2">
                            <Label for="nik">NIK</Label>
                            <Input id="nik" class="mt-1 block w-full" v-model="form.wargaData.nik" required placeholder="Nomor Induk Kependudukan" />
                            <InputError class="mt-2" :message="form.errors['wargaData.nik']" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="full_name">Nama Lengkap</Label>
                            <Input id="full_name" class="mt-1 block w-full" v-model="form.wargaData.full_name" required placeholder="Nama lengkap sesuai KTP" />
                            <InputError class="mt-2" :message="form.errors['wargaData.full_name']" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="tempat_lahir">Tempat Lahir</Label>
                            <Input id="tempat_lahir" class="mt-1 block w-full" v-model="form.wargaData.tempat_lahir" placeholder="Tempat lahir" />
                            <InputError class="mt-2" :message="form.errors['wargaData.tempat_lahir']" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="tanggal_lahir">Tanggal Lahir</Label>
                            <Input id="tanggal_lahir" type="date" class="mt-1 block w-full" v-model="form.wargaData.tanggal_lahir" />
                            <InputError class="mt-2" :message="form.errors['wargaData.tanggal_lahir']" />
                        </div>

                        <div class="grid gap-2">
                            <BaseSelect
                                label="Status"
                                name="status"
                                placeholder="Pilih Status"
                                :value="form.wargaData.status"
                                @update:value="form.wargaData.status = $event"
                                :options="[
                                    { value: 'Kepala Keluarga', label: 'Kepala Keluarga' },
                                    { value: 'Istri', label: 'Istri' },
                                    { value: 'Anak', label: 'Anak' }
                                ]"
                                :message="form.errors['wargaData.status']"
                            />
                        </div>

                        <!-- Kartu Keluarga Section -->
                        <div v-if="form.wargaData.status === 'Kepala Keluarga'" class="space-y-6 border-t pt-6">
                            <HeadingSmall title="Data Kartu Keluarga" description="Informasi kartu keluarga untuk kepala keluarga" />

                            <div class="grid gap-2">
                                <Label for="no_kk">Nomor KK</Label>
                                <Input id="no_kk" class="mt-1 block w-full" v-model="form.kartuKeluargaData.no_kk" maxlength="16" placeholder="Nomor Kartu Keluarga (16 digit)" />
                                <InputError class="mt-2" :message="form.errors['kartuKeluargaData.no_kk']" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="jumlah_anggota">Jumlah Anggota Keluarga</Label>
                                <Input id="jumlah_anggota" type="number" min="1" class="mt-1 block w-full" v-model="form.kartuKeluargaData.jumlah_anggota" placeholder="Jumlah anggota keluarga" />
                                <InputError class="mt-2" :message="form.errors['kartuKeluargaData.jumlah_anggota']" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="foto_ktp_kepala_keluarga">Foto KTP Kepala Keluarga</Label>
                                <Input 
                                    id="foto_ktp_kepala_keluarga" 
                                    type="file" 
                                    accept="image/*" 
                                    class="mt-1 block w-full" 
                                    @input="form.kartuKeluargaData.foto_ktp_kepala_keluarga = ($event.target as HTMLInputElement)?.files?.[0] || null"
                                    @change="handleImageChange"
                                />
                                <p class="text-sm text-muted-foreground">Format yang didukung: JPG, JPEG, PNG. Ukuran maksimal: 2MB</p>
                                <div v-if="imagePreview" class="mt-2">
                                    <img :src="imagePreview" alt="Preview" class="h-32 w-32 rounded border object-cover" />
                                </div>
                                <InputError class="mt-2" :message="form.errors['kartuKeluargaData.foto_ktp_kepala_keluarga']" />
                            </div>
                        </div>

                        <!-- Rumah Section -->
                        <div v-if="form.wargaData.status === 'Kepala Keluarga'" class="space-y-6 border-t pt-6">
                            <HeadingSmall title="Data Rumah" description="Informasi alamat rumah" />

                            <div class="grid gap-2">
                                <Label for="perumahan">Nama Perumahan</Label>
                                <Input id="perumahan" class="mt-1 block w-full" v-model="form.rumahData.perumahan" placeholder="Nama perumahan" />
                                <InputError class="mt-2" :message="form.errors['rumahData.perumahan']" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="jalan">Nama Jalan</Label>
                                <Input id="jalan" class="mt-1 block w-full" v-model="form.rumahData.jalan" required placeholder="Nama jalan" />
                                <InputError class="mt-2" :message="form.errors['rumahData.jalan']" />
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="grid gap-2">
                                    <Label for="blok">Blok</Label>
                                    <Input id="blok" class="mt-1 block w-full" v-model="form.rumahData.blok" placeholder="Blok rumah" />
                                    <InputError class="mt-2" :message="form.errors['rumahData.blok']" />
                                </div>

                                <div class="grid gap-2">
                                    <Label for="nomor">Nomor</Label>
                                    <Input id="nomor" class="mt-1 block w-full" v-model="form.rumahData.nomor" placeholder="Nomor rumah" />
                                    <InputError class="mt-2" :message="form.errors['rumahData.nomor']" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="mustVerifyEmail && !user.email_verified_at">
                        <p class="-mt-4 text-sm text-muted-foreground">
                            Alamat email Anda belum diverifikasi.
                            <Link
                                :href="route('verification.send')"
                                method="post"
                                as="button"
                                class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                            >
                                Klik di sini untuk mengirim ulang email verifikasi.
                            </Link>
                        </p>

                        <div v-if="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">
                            Tautan verifikasi baru telah dikirim ke alamat email Anda.
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="form.processing">Simpan</Button>

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">Tersimpan.</p>
                        </Transition>
                    </div>
                </form>
            </div>

            <DeleteUser />
        </SettingsLayout>
    </AppLayout>
</template>
