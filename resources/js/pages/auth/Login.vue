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
                            class="text-sm text-primary hover:text-primary/80 font-medium"
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

                <div class="flex items-center gap-2 mt-1">
                    <Checkbox id="remember" v-model="form.remember" :tabindex="4" class="text-primary focus:ring-primary/30" />
                    <Label for="remember" class="text-sm font-medium leading-none text-slate-600 dark:text-slate-400 peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                        Remember me
                    </Label>
                </div>

                <Button 
                    type="submit" 
                    class="mt-4 w-full h-11 bg-primary hover:bg-primary/90 text-primary-foreground font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center" 
                    :tabindex="5" 
                    :disabled="form.processing"
                >
                    <LoaderCircle v-if="form.processing" class="h-5 w-5 animate-spin mr-2" />
                    <span>Sign In</span>
                </Button>
            </div>

            <div class="text-center text-sm text-slate-600 dark:text-slate-400 mt-2">
                Don't have an account?
                <TextLink :href="route('register')" :tabindex="6" class="text-primary hover:text-primary/80 font-medium ml-1">Create an account</TextLink>
            </div>
        </form>
    </AuthBase>
</template>
