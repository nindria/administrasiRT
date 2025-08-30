<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AuthBase title="Log in to your account" description="Enter your email and password below to log in">
        <Head title="Log in" />

        <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-3">
                    <Label for="email" class="text-slate-700 dark:text-slate-300">Email address</Label>
                    <Input
                        id="email"
                        type="email"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="email"
                        v-model="form.email"
                        placeholder="email@example.com"
                        class="h-11 rounded-lg border-slate-300 bg-white/50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-slate-600 dark:bg-slate-800/50"
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-3">
                    <div class="flex items-center justify-between">
                        <Label for="password" class="text-slate-700 dark:text-slate-300">Password</Label>
                        <TextLink
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-sm font-medium text-primary hover:text-primary/80"
                            :tabindex="3"
                        >
                            Forgot password?
                        </TextLink>
                    </div>
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="2"
                        autocomplete="current-password"
                        v-model="form.password"
                        placeholder="Password"
                        class="h-11 rounded-lg border-slate-300 bg-white/50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-slate-600 dark:bg-slate-800/50"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="mt-1 flex items-center gap-2">
                    <Checkbox id="remember" v-model="form.remember" :tabindex="4" class="text-primary focus:ring-primary/30" />
                    <Label
                        for="remember"
                        class="text-sm leading-none font-medium text-slate-600 peer-disabled:cursor-not-allowed peer-disabled:opacity-70 dark:text-slate-400"
                    >
                        Remember me
                    </Label>
                </div>

                <Button
                    type="submit"
                    class="mt-4 flex h-11 w-full items-center justify-center rounded-lg bg-primary font-medium text-primary-foreground shadow-md transition-all duration-200 hover:bg-primary/90 hover:shadow-lg"
                    :tabindex="5"
                    :disabled="form.processing"
                >
                    <LoaderCircle v-if="form.processing" class="mr-2 h-5 w-5 animate-spin" />
                    <span>Sign In</span>
                </Button>
            </div>

            <div class="mt-2 text-center text-sm text-slate-600 dark:text-slate-400">
                Don't have an account?
                <TextLink :href="route('register')" :tabindex="6" class="ml-1 font-medium text-primary hover:text-primary/80"
                    >Create an account</TextLink
                >
            </div>
        </form>
    </AuthBase>
</template>
