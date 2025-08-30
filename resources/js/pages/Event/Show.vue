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
        day: 'numeric'
    });
});
</script>

<template>
    <div class="min-h-screen bg-background">
        <Head :title="event.title" />

        <!-- Header -->
        <header class="bg-card shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <Link :href="route('home')" class="text-primary hover:text-primary/80 font-semibold">
                        ← Back to Home
                    </Link>
                    <h1 class="text-2xl font-bold text-foreground">Event Details</h1>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-card rounded-lg shadow-md overflow-hidden">
                <!-- Event Image -->
                <div class="h-64 bg-secondary flex items-center justify-center">
                    <img v-if="event.image" :src="`/storage/${event.image}`" :alt="event.title" class="w-full h-full object-cover" />
                    <div v-else class="text-primary text-6xl">📅</div>
                </div>

                <!-- Event Content -->
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-3xl font-bold text-foreground">{{ event.title }}</h2>
                        <span class="text-sm text-muted-foreground">{{ formattedDate }}</span>
                    </div>

                    <div class="prose prose-lg text-foreground mb-6">
                        <p>{{ event.description }}</p>
                    </div>

                    <!-- Additional Event Details (can be expanded later) -->
                    <div class="bg-muted rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-foreground mb-2">Event Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-muted-foreground">
                            <div>
                                <span class="font-medium">Date:</span> {{ formattedDate }}
                            </div>
                            <div>
                                <span class="font-medium">Status:</span> Upcoming
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 flex items-center gap-4">
                        <Link :href="route('home')" class="bg-primary text-primary-foreground px-6 py-2 rounded-lg font-semibold hover:bg-primary/90 transition">
                            Back to Home
                        </Link>
                        <button class="bg-green-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-700 transition dark:bg-green-700 dark:hover:bg-green-800">
                            Register for Event
                        </button>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-card border-t border-border mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <p class="text-center text-muted-foreground text-sm">
                    © 2025 Event Management System. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</template>
