<script setup lang="ts">
import BaseInput from '@/components/form/BaseInput.vue';
import InputError from '@/components/InputError.vue';
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
        title: 'Create',
        href: '/events/create',
    },
];

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
    form.post(route('events.store'), {
        onSuccess: () => {
            form.reset();
            imagePreview.value = null;
        },
    });
}
</script>

<template>
    <Head title="Buat Acara" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <h1 class="text-2xl font-bold">Tambah Data Acara</h1>
            <form @submit.prevent="submit">
                <div class="grid gap-4">
                    <BaseInput
                        label="Title"
                        name="title"
                        v-model:value="form.title"
                        placeholder="Masukkan judul acara"
                        :message="form.errors.title"
                    />
                    <BaseInput
                        label="Description"
                        name="description"
                        v-model:value="form.description"
                        placeholder="Masukkan deskripsi acara"
                        :message="form.errors.description"
                    />
                    <div class="grid">
                        <Label class="mb-2" for="image">Gambar Acara</Label>
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
                        <InputError :message="form.errors.image" class="mt-2" />
                    </div>

                    <div class="flex justify-end gap-2">
                        <Link href="/events"
                            ><Button
                                class="inline-flex w-24 bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 text-white hover:bg-gradient-to-br focus:ring-4 focus:ring-blue-300 focus:outline-none dark:focus:ring-blue-800"
                                type="button"
                            >
                                <ChevronLeft class="h-4 w-4" />
                                Back
                            </Button></Link
                        >
                        <Button class="w-24" type="submit" :disabled="form.processing">Submit</Button>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
