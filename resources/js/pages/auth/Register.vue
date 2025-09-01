<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

const form = useForm({
    name: '',
    email: '',
    nik: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthBase title="Buat akun" description="Masukkan detail Anda di bawah ini untuk membuat akun">
        <Head title="Daftar" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-3">
                    <Label for="name" class="text-slate-700 dark:text-slate-300">Nama Lengkap</Label>
                    <Input
                        id="name"
                        type="text"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="name"
                        v-model="form.name"
                        placeholder="Nama Lengkap Anda"
                        class="h-11 rounded-lg border-slate-300 bg-white/50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-slate-600 dark:bg-slate-800/50"
                    />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-3">
                    <Label for="email" class="text-slate-700 dark:text-slate-300">Alamat Email</Label>
                    <Input
                        id="email"
                        type="email"
                        required
                        :tabindex="2"
                        autocomplete="email"
                        v-model="form.email"
                        placeholder="email@contoh.com"
                        class="h-11 rounded-lg border-slate-300 bg-white/50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-slate-600 dark:bg-slate-800/50"
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-3">
                    <Label for="nik" class="text-slate-700 dark:text-slate-300">NIK</Label>
                    <Input
                        id="nik"
                        type="text"
                        required
                        :tabindex="3"
                        autocomplete="off"
                        v-model="form.nik"
                        placeholder="Nomor Induk Kependudukan (16 digit)"
                        maxlength="16"
                        pattern="[0-9]{16}"
                        class="h-11 rounded-lg border-slate-300 bg-white/50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-slate-600 dark:bg-slate-800/50"
                    />
                    <InputError :message="form.errors.nik" />
                </div>

                <div class="grid gap-3">
                    <Label for="password" class="text-slate-700 dark:text-slate-300">Kata Sandi</Label>
                    <PasswordInput
                        id="password"
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        v-model="form.password"
                        placeholder="Buat kata sandi yang aman"
                        class="h-11 rounded-lg border-slate-300 bg-white/50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-slate-600 dark:bg-slate-800/50"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-3">
                    <Label for="password_confirmation" class="text-slate-700 dark:text-slate-300">Konfirmasi Kata Sandi</Label>
                    <PasswordInput
                        id="password_confirmation"
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        v-model="form.password_confirmation"
                        placeholder="Konfirmasi kata sandi Anda"
                        class="h-11 rounded-lg border-slate-300 bg-white/50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-slate-600 dark:bg-slate-800/50"
                    />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <Button
                    type="submit"
                    class="mt-4 flex h-11 w-full items-center justify-center rounded-lg bg-primary font-medium text-primary-foreground shadow-md transition-all duration-200 hover:bg-primary/90 hover:shadow-lg"
                    tabindex="6"
                    :disabled="form.processing"
                >
                    <LoaderCircle v-if="form.processing" class="mr-2 h-5 w-5 animate-spin" />
                    <span>Buat Akun</span>
                </Button>
            </div>

            <div class="mt-2 text-center text-sm text-slate-600 dark:text-slate-400">
                Already have an account?
                <TextLink :href="route('login')" class="ml-1 font-medium text-primary hover:text-primary/80" :tabindex="7">Sign in</TextLink>
            </div>
        </form>
    </AuthBase>
</template>
