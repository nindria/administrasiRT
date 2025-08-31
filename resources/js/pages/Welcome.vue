<!-- resources/js/Pages/Landing/Index.vue -->
<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

interface Event {
    id: number;
    title: string;
    description: string;
    image: string | null;
    public_id?: string;
}

interface Banner {
    id: number;
    title: string;
    description: string;
    image: string;
    is_active: boolean;
    public_id?: string;
}

const props = defineProps<{
    events: Event[];
    banner?: Banner;
}>();
</script>
<template>
    <div class="font-inter text-foreground antialiased">
        <!-- Header / Nav -->
        <header class="border-b border-gray-200 bg-white dark:border-slate-700 dark:bg-slate-900">
            <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-4 lg:px-8">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-sm bg-blue-600"></div>
                    <div class="text-lg font-bold text-gray-900 dark:text-white">SIRUWA 07</div>
                </div>

                <div class="flex items-center gap-4">
                    <Link :href="route('login')" class="rounded-lg bg-blue-600 px-4 py-2 text-sm text-white transition-colors hover:bg-blue-700"
                        >Masuk</Link
                    >
                </div>
            </div>
        </header>

        <!-- Banner Section -->
        <section class="relative">
            <!-- Background image + overlay -->
            <div class="relative h-[500px] overflow-hidden">
                <img v-if="props.banner?.image" :src="props.banner.image" alt="banner" class="h-full w-full object-cover brightness-90" />
                <img v-else src="/images/hero.jpeg" alt="default banner" class="h-full w-full object-cover brightness-90" />
                <!-- Overlay for text readability -->
                <div
                    aria-hidden
                    class="pointer-events-none absolute inset-0 bg-gradient-to-tr from-blue-800/40 via-indigo-700/20 to-transparent mix-blend-screen"
                ></div>

                <!-- Banner content -->
                <div class="absolute inset-0 mx-auto flex h-full max-w-7xl items-center px-6 lg:px-8">
                    <div class="max-w-2xl text-white">
                        <h1 class="text-4xl leading-tight font-bold drop-shadow-lg md:text-5xl">
                            {{ props.banner?.title || 'SELAMAT DATANG DI SIRUWA 07!' }}
                        </h1>
                        <p class="mt-4 text-lg text-white/90">
                            {{ props.banner?.description || 'Sistem terintegrasi untuk Siruwa 07,tempat untuk mengelola data dan informasi RT' }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Events Section -->
        <section class="bg-gray-50 py-16 dark:bg-slate-800">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <h2 class="mb-10 text-center text-3xl font-bold text-gray-900 dark:text-white">Event yang Akan Datang</h2>

                <div v-if="props.events && props.events.length > 0" class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    <Link
                        v-for="event in props.events"
                        :key="event.id"
                        :href="route('events.show', event.id)"
                        class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-md transition-shadow duration-300 hover:shadow-xl dark:border-slate-600 dark:bg-slate-700"
                    >
                        <div class="h-56 overflow-hidden bg-gray-100 dark:bg-slate-600">
                            <img
                                v-if="event.image"
                                :src="event.image"
                                :alt="event.title"
                                class="h-full w-full object-cover transition-transform duration-500 hover:scale-105"
                            />
                            <div v-else class="flex h-full w-full items-center justify-center text-5xl text-blue-600">📅</div>
                        </div>
                        <div class="p-6">
                            <h3 class="mb-3 text-xl font-semibold text-blue-600 dark:text-blue-400">{{ event.title }}</h3>
                            <p class="text-gray-600 dark:text-gray-300">{{ event.description }}</p>
                        </div>
                    </Link>
                </div>

                <div v-else class="py-10 text-center">
                    <div class="mb-4 text-5xl">📅</div>
                    <p class="text-xl text-gray-600 dark:text-gray-300">Tidak ada event yang akan datang.</p>
                </div>
            </div>
        </section>

        <!-- Simple Footer -->
        <footer
            class="border-t border-gray-200 bg-gray-100 py-6 text-center text-sm text-gray-600 dark:border-slate-700 dark:bg-slate-800 dark:text-gray-400"
        >
            © {{ new Date().getFullYear() }} SIRUWA 07. Hak cipta dilindungi.
        </footer>
    </div>
</template>
