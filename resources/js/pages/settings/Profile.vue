<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

import DeleteUser from '@/components/DeleteUser.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
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
});

const submit = () => {
    form.patch(route('profile.update'), {
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
                            <Label for="status">Status</Label>
                            <select id="status" class="mt-1 block w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background" v-model="form.wargaData.status">
                                <option value="">Pilih Status</option>
                                <option value="Kepala Keluarga">Kepala Keluarga</option>
                                <option value="Istri">Istri</option>
                                <option value="Anak">Anak</option>
                            </select>
                            <InputError class="mt-2" :message="form.errors['wargaData.status']" />
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
