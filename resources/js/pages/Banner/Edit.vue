<script setup lang="ts">
import BaseInput from '@/components/form/BaseInput.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ChevronLeft } from 'lucide-vue-next';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Edit',
        href: '/banners/edit',
    },
];

const props = defineProps<{
    banner: {
        id: number;
        title: string;
        description: string;
        image: string;
        is_active: boolean;
    };
}>();

const form = useForm({
    title: props.banner.title,
    description: props.banner.description,
    image: null as File | null,
    is_active: props.banner.is_active,
    _method: 'put',
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
    form.post(route('banners.update', props.banner.id), {
        onSuccess: () => {
            form.reset();
            imagePreview.value = null;
        },
    });
}
</script>

<template>
    <Head title="Edit Data Spanduk" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <h1 class="text-2xl font-bold">Edit Data Spanduk</h1>
            <form @submit.prevent="submit">
                <div class="grid gap-4">
                    <BaseInput
                        label="Title"
                        name="title"
                        v-model:value="form.title"
                        placeholder="Masukkan judul spanduk"
                        :message="form.errors.title"
                    />
                    <BaseInput
                        label="Description"
                        name="description"
                        v-model:value="form.description"
                        placeholder="Masukkan deskripsi spanduk"
                        :message="form.errors.description"
                    />
                    <div class="grid">
                        <Label class="mb-2" for="image">Gambar Spanduk</Label>
                        <Input
                            id="image"
                            name="image"
                            type="file"
                            accept="image/*"
                            @input="form.image = ($event.target as HTMLInputElement)?.files?.[0] || null"
                            @change="handleImageChange"
                            class="mt-1 block w-full"
                        />
                        <p class="mt-1 text-sm text-gray-500">Supported formats: JPG, JPEG, PNG. Max size: 2MB</p>
                        <div v-if="imagePreview" class="mt-2">
                            <img :src="imagePreview" alt="Preview" class="h-auto w-32 rounded border" />
                        </div>
                        <div v-if="!imagePreview && banner.image" class="mt-2">
                            <p class="text-sm text-gray-500">Current image:</p>
                            <img :src="`${banner.image}`" alt="Current image" class="h-auto w-32 rounded border" />
                        </div>
                        <InputError :message="form.errors.image" />
                    </div>
                    <div class="flex items-center space-x-2">
                        <input
                            id="is_active"
                            type="checkbox"
                            v-model="form.is_active"
                            class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500"
                        />
                        <label for="is_active" class="cursor-pointer text-sm font-medium text-gray-700">Tetapkan sebagai spanduk aktif</label>
                    </div>
                    <div v-if="form.errors.is_active" class="text-sm text-red-500">{{ form.errors.is_active }}</div>

                    <div class="flex justify-end gap-2">
                        <Link :href="route('banners.index')">
                            <Button
                                class="inline-flex w-24 bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 text-white hover:bg-gradient-to-br focus:ring-4 focus:ring-blue-300 focus:outline-none dark:focus:ring-blue-800"
                                type="button"
                            >
                                <ChevronLeft class="h-4 w-4" />
                                Back
                            </Button>
                        </Link>
                        <Button class="w-24" type="submit" :disabled="form.processing">Update</Button>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
