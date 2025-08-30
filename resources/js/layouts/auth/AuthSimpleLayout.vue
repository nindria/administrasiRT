<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="min-h-svh flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-slate-900 dark:to-indigo-950 p-6 md:p-10">
        <!-- Decorative elements -->
        <div class="absolute top-0 left-0 w-full h-64 bg-primary/20 blur-3xl opacity-50 pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-gradient-to-tr from-blue-400/10 to-indigo-500/10 rounded-full blur-3xl opacity-60 pointer-events-none"></div>
        
        <!-- Auth card -->
        <div class="w-full max-w-md relative z-10">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl overflow-hidden">
                <!-- Card header with logo -->
                <div class="p-8 bg-primary flex flex-col items-center">
                    <Link :href="route('home')" class="flex flex-col items-center gap-2 font-medium">
                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-white/20 backdrop-blur-sm p-3">
                            <AppLogoIcon class="size-10 fill-current text-white" />
                        </div>
                        <span class="text-white font-bold text-xl mt-2">SIRUWA 07</span>
                    </Link>
                </div>
                
                <!-- Card content -->
                <div class="p-8">
                    <div class="space-y-3 text-center mb-6">
                        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">{{ title }}</h1>
                        <p class="text-center text-sm text-slate-500 dark:text-slate-400">{{ description }}</p>
                    </div>
                    <slot />
                </div>
            </div>
            
            <!-- Footer note -->
            <div class="text-center mt-6 text-sm text-slate-500 dark:text-slate-400">
                © {{ new Date().getFullYear() }} SIRUWA 07. All rights reserved.
            </div>
        </div>
    </div>
</template>
