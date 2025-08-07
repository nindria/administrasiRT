<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';

const form = useForm({
    title: '',
    description: '',
    image: null as File | null,
});

const imagePreview = ref<string | null>(null);

function handleImageChange(event: Event) {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];
    if (file) {
        form.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
}

function submit() {
    const data = new FormData();
    if (form.image) {
        data.append('image', form.image);
    }

    data.append('title', form.title);
    data.append('description', form.description);

    router.post(route('event.store'), data, {
        forceFormData: true,
        onSuccess: () => {
            form.reset();
            imagePreview.value = null;
        },
    });
}
</script>

<template>
    <Head title="Create Event" />
    <AppLayout>
        <div class="p-6">
            <h1 class="text-2xl font-semibold mb-4">Create Event</h1>
            <div class="pb-4">
                <Link href="/norumah" type="button"
                    class="inline-flex items-center text-white bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                <ChevronLeft class="w-4 h-4 mr-2"/>
                Back
                </Link>
            </div>
            <form @submit.prevent="submit" class="space-y-4 max-w-md">
                <div>
                    <label class="block mb-1 font-medium">Title</label>
                    <Input v-model="form.title" type="text" required />
                </div>

                <div>
                    <label class="block mb-1 font-medium">Description</label>
                    <Input v-model="form.description" required />
                </div>

                <div>
                    <label class="block mb-1 font-medium">Image</label>
                    <Input type="file" accept="image/*" @change="handleImageChange" />
                    <div v-if="imagePreview" class="mt-2">
                        <img :src="imagePreview" alt="Preview" class="w-32 h-auto rounded border" />
                    </div>
                </div>

                <Button type="submit" class="mt-4">Submit</Button>
            </form>
        </div>
    </AppLayout>
</template>
