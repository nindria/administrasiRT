<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="flex min-h-svh items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 p-6 md:p-10 dark:from-slate-900 dark:to-indigo-950"
    >
        <!-- Decorative elements -->
        <div class="pointer-events-none absolute top-0 left-0 h-64 w-full bg-primary/20 opacity-50 blur-3xl"></div>
        <div
            class="pointer-events-none absolute right-0 bottom-0 h-96 w-96 rounded-full bg-gradient-to-tr from-blue-400/10 to-indigo-500/10 opacity-60 blur-3xl"
        ></div>

        <!-- Auth card -->
        <div class="relative z-10 w-full max-w-md">
            <div class="overflow-hidden rounded-2xl bg-white shadow-xl dark:bg-slate-800">
                <!-- Card header with logo -->
                <div class="flex flex-col items-center bg-primary p-8">
                    <Link :href="route('home')" class="flex flex-col items-center gap-2 font-medium">
                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-white/20 p-3 backdrop-blur-sm">
                            <AppLogoIcon class="size-10 fill-current text-white" />
                        </div>
                        <span class="mt-2 text-xl font-bold text-white">SIRUWA 07</span>
                    </Link>
                </div>

                <!-- Card content -->
                <div class="p-8">
                    <div class="mb-6 space-y-3 text-center">
                        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">{{ title }}</h1>
                        <p class="text-center text-sm text-slate-500 dark:text-slate-400">{{ description }}</p>
                    </div>
                    <slot />
                </div>
            </div>

            <!-- Footer note -->
            <div class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
                © {{ new Date().getFullYear() }} SIRUWA 07. Hak cipta dilindungi.
            </div>
        </div>
    </div>
</template>
