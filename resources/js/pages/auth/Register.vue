<script setup lang="ts">
import InputError from '@/components/InputError.vue';
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
    <AuthBase title="Create an account" description="Enter your details below to create your account">
        <Head title="Register" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-3">
                    <Label for="name" class="text-slate-700 dark:text-slate-300">Full Name</Label>
                    <Input 
                        id="name" 
                        type="text" 
                        required 
                        autofocus 
                        :tabindex="1" 
                        autocomplete="name" 
                        v-model="form.name" 
                        placeholder="John Doe" 
                        class="h-11 rounded-lg border-slate-300 bg-white/50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-slate-600 dark:bg-slate-800/50"
                    />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-3">
                    <Label for="email" class="text-slate-700 dark:text-slate-300">Email address</Label>
                    <Input 
                        id="email" 
                        type="email" 
                        required 
                        :tabindex="2" 
                        autocomplete="email" 
                        v-model="form.email" 
                        placeholder="email@example.com" 
                        class="h-11 rounded-lg border-slate-300 bg-white/50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-slate-600 dark:bg-slate-800/50"
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-3">
                    <Label for="password" class="text-slate-700 dark:text-slate-300">Password</Label>
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="3"
                        autocomplete="new-password"
                        v-model="form.password"
                        placeholder="Create a secure password"
                        class="h-11 rounded-lg border-slate-300 bg-white/50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-slate-600 dark:bg-slate-800/50"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-3">
                    <Label for="password_confirmation" class="text-slate-700 dark:text-slate-300">Confirm password</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        v-model="form.password_confirmation"
                        placeholder="Confirm your password"
                        class="h-11 rounded-lg border-slate-300 bg-white/50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-slate-600 dark:bg-slate-800/50"
                    />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <Button 
                    type="submit" 
                    class="mt-4 w-full h-11 bg-primary hover:bg-primary/90 text-primary-foreground font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center" 
                    tabindex="5" 
                    :disabled="form.processing"
                >
                    <LoaderCircle v-if="form.processing" class="h-5 w-5 animate-spin mr-2" />
                    <span>Create Account</span>
                </Button>
            </div>

            <div class="text-center text-sm text-slate-600 dark:text-slate-400 mt-2">
                Already have an account?
                <TextLink :href="route('login')" class="text-primary hover:text-primary/80 font-medium ml-1" :tabindex="6">Sign in</TextLink>
            </div>
        </form>
    </AuthBase>
</template>
