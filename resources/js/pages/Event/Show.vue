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
    <div class="min-h-screen bg-gray-50">
        <Head :title="event.title" />

        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <Link :href="route('home')" class="text-blue-600 hover:text-blue-800 font-semibold">
                        ← Back to Home
                    </Link>
                    <h1 class="text-2xl font-bold text-gray-900">Event Details</h1>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Event Image -->
                <div class="h-64 bg-blue-100 flex items-center justify-center">
                    <img v-if="event.image" :src="`/storage/${event.image}`" :alt="event.title" class="w-full h-full object-cover" />
                    <div v-else class="text-blue-600 text-6xl">📅</div>
                </div>

                <!-- Event Content -->
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-3xl font-bold text-gray-900">{{ event.title }}</h2>
                        <span class="text-sm text-gray-500">{{ formattedDate }}</span>
                    </div>

                    <div class="prose prose-lg text-gray-700 mb-6">
                        <p>{{ event.description }}</p>
                    </div>

                    <!-- Additional Event Details (can be expanded later) -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Event Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
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
                        <Link :href="route('home')" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                            Back to Events
                        </Link>
                        <button class="bg-green-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-700 transition">
                            Register for Event
                        </button>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <p class="text-center text-gray-500 text-sm">
                    © 2025 Event Management System. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</template>
