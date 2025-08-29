<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Checkbox } from '@/components/ui/checkbox';
import InputError from '@/components/InputError.vue';

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
});

const submit = () => {
    form.put(route('banners.update', props.banner.id), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <AppLayout>
        <Head title="Edit Banner" />

        <div class="container mx-auto py-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold">Edit Banner</h1>
                <Link :href="route('banners.index')">
                    <Button variant="outline">Back to Banners</Button>
                </Link>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid gap-2">
                        <Label for="title">Title</Label>
                        <Input
                            id="title"
                            v-model="form.title"
                            required
                            placeholder="Enter banner title"
                        />
                        <InputError :message="form.errors.title" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="description">Description</Label>
                        <Input
                            id="description"
                            v-model="form.description"
                            placeholder="Enter banner description (optional)"
                        />
                        <InputError :message="form.errors.description" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="image">Banner Image</Label>
                        <Input
                            id="image"
                            type="file"
                            accept="image/*"
                            @input="form.image = $event.target.files[0]"
                        />
                        <InputError :message="form.errors.image" />
                        <p class="text-sm text-gray-500">Supported formats: JPG, JPEG, PNG. Max size: 2MB</p>
                    </div>

                    <div class="flex items-center space-x-2">
                        <Checkbox
                            id="is_active"
                            v-model="form.is_active"
                        />
                        <Label for="is_active" class="cursor-pointer">Set as active banner</Label>
                    </div>
                    <InputError :message="form.errors.is_active" />

                    <div class="flex justify-end space-x-4">
                        <Link :href="route('banners.index')">
                            <Button type="button" variant="outline">Cancel</Button>
                        </Link>
                        <Button type="submit" :disabled="form.processing">
                            Update Banner
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
