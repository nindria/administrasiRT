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

        <!-- Header -->
        <header class="bg-card shadow-sm">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <Link :href="route('home')" class="font-semibold text-primary hover:text-primary/80"> ← Back to Home </Link>
                    <h1 class="text-2xl font-bold text-foreground">Event Details</h1>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-lg bg-card shadow-md">
                <!-- Event Image -->
                <div class="flex h-64 items-center justify-center bg-secondary">
                    <img v-if="event.image" :src="`/storage/${event.image}`" :alt="event.title" class="h-full w-full object-cover" />
                    <div v-else class="text-6xl text-primary">📅</div>
                </div>

                <!-- Event Content -->
                <div class="p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-3xl font-bold text-foreground">{{ event.title }}</h2>
                        <span class="text-sm text-muted-foreground">{{ formattedDate }}</span>
                    </div>

                    <div class="prose prose-lg mb-6 text-foreground">
                        <p>{{ event.description }}</p>
                    </div>

                    <!-- Additional Event Details (can be expanded later) -->
                    <div class="rounded-lg bg-muted p-4">
                        <h3 class="mb-2 text-lg font-semibold text-foreground">Event Information</h3>
                        <div class="grid grid-cols-1 gap-4 text-sm text-muted-foreground md:grid-cols-2">
                            <div><span class="font-medium">Date:</span> {{ formattedDate }}</div>
                            <div><span class="font-medium">Status:</span> Upcoming</div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 flex items-center gap-4">
                        <Link
                            :href="route('home')"
                            class="rounded-lg bg-primary px-6 py-2 font-semibold text-primary-foreground transition hover:bg-primary/90"
                        >
                            Back to Home
                        </Link>
                        <button
                            class="rounded-lg bg-green-600 px-6 py-2 font-semibold text-white transition hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800"
                        >
                            Register for Event
                        </button>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="mt-12 border-t border-border bg-card">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-muted-foreground">© 2025 Event Management System. Hak cipta dilindungi.</p>
            </div>
        </footer>
    </div>
</template>
