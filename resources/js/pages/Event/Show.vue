<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Event {
    id: number;
    title: string;
    description: string;
    image: string | null;
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    event: Event;
}>();

const formattedDate = computed(() => {
    const date = new Date(props.event.created_at);
    return date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
});
</script>

<template>
    <div class="min-h-screen bg-background">
        <Head :title="event.title" />

        <!-- Header / Nav -->
        <header class="border-b border-gray-200 bg-white dark:border-slate-700 dark:bg-slate-900">
            <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-4 lg:px-8">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-sm bg-[url('avatar.png')]"></div>
                    <div class="text-lg font-bold text-gray-900 dark:text-white">SIRUWA 07</div>
                </div>

                <div class="flex items-center gap-4">
                    <Link :href="route('home')" class="rounded-lg bg-blue-600 px-4 py-2 text-sm text-white transition-colors hover:bg-blue-700"
                        >Kembali ke Beranda</Link
                    >
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-lg bg-card shadow-md">
                <!-- Event Image -->
                <div class="relative h-80 overflow-hidden bg-gradient-to-br from-primary/10 to-secondary/20">
                    <img
                        v-if="event.image"
                        :src="`${event.image}`"
                        :alt="event.title"
                        class="h-full w-full object-cover transition-transform duration-300 hover:scale-105"
                    />
                    <div v-else class="flex h-full items-center justify-center">
                        <div class="rounded-full bg-primary/20 p-8">
                            <div class="text-8xl text-primary opacity-80">📅</div>
                        </div>
                    </div>
                    <!-- Gradient overlay for better text readability -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                </div>

                <!-- Event Content -->
                <div class="p-8">
                    <div class="mb-6">
                        <div class="mb-3 flex items-center gap-2">
                            <div class="h-1 w-12 rounded-full bg-primary"></div>
                            <span class="text-sm font-medium tracking-wide text-primary uppercase">Acara</span>
                        </div>
                        <h2 class="mb-3 text-4xl leading-tight font-bold text-foreground">{{ event.title }}</h2>
                        <div class="flex items-center gap-2 text-muted-foreground">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                ></path>
                            </svg>
                            <span class="text-sm font-medium">{{ formattedDate }}</span>
                        </div>
                    </div>

                    <div class="prose prose-lg mb-8 max-w-none leading-relaxed text-foreground/90">
                        <p class="text-lg">{{ event.description }}</p>
                    </div>

                    <!-- Additional Event Details -->
                    <div class="rounded-xl border border-border/50 bg-gradient-to-r from-muted/50 to-muted/30 p-6 backdrop-blur-sm">
                        <h3 class="mb-4 flex items-center gap-2 text-xl font-semibold text-foreground">
                            <div class="rounded-lg bg-primary/10 p-2">
                                <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    ></path>
                                </svg>
                            </div>
                            Informasi Acara
                        </h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div class="flex items-center gap-3 rounded-lg bg-card/50 p-4">
                                <div class="rounded-full bg-primary/10 p-2">
                                    <svg class="h-4 w-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                        ></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-muted-foreground">Tanggal</span>
                                    <p class="font-semibold text-foreground">{{ formattedDate }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="border-t border-border bg-card">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-muted-foreground">© 2025 Event Management System. Hak cipta dilindungi.</p>
            </div>
        </footer>
    </div>
</template>
