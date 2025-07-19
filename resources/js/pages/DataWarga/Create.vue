<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { ChevronLeft } from 'lucide-vue-next';
import { watch } from 'vue';

defineProps<{
    noRumahs: Array<{ id: number, name: string }>
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create',
        href: '/datawarga/create',
    },
];

const form = useForm<{
    full_name: string;
    family_card_number: string;
    no_rumah_id: number | null;
    husband_birth_place: string;
    husband_birth_date: string;
    married_status: 'menikah' | 'belum_menikah';
    wife_name: string;
    wife_birth_place: string;
    wife_birth_date: string;
    children_count: number;
    children_data: Array<{
        name: string;
        birth_place: string;
        birth_date: string;
    }>;
    other_family_members: string[];
    status: string;
    residence_status: string;
    document: File | null;
}>({
    full_name: '',
    family_card_number: '',
    no_rumah_id: null,
    husband_birth_place: '',
    husband_birth_date: '',
    married_status: 'belum_menikah',
    wife_name: '',
    wife_birth_place: '',
    wife_birth_date: '',
    children_count: 0,
    children_data: [],
    other_family_members: [''],
    status: '',
    residence_status: '',
    document: null,
});


watch(() => form.children_count, (newCount) => {
    const count = Number(newCount);
    form.children_data = [];
    for (let i = 0; i < count; i++) {
        form.children_data.push({ name: '', birth_place: '', birth_date: '' });
    }
});

watch(() => form.married_status, (newStatus) => {
    if (newStatus === 'belum_menikah') {
        form.wife_name = '';
        form.wife_birth_place = '';
        form.wife_birth_place = '';
        form.children_count = 0;
    }
});

function addOtherFamilyMember() {
    form.other_family_members.push('');
}

function removeOtherFamilyMember(index: number) {
    form.other_family_members.splice(index, 1);
}

function submit() {
    if (form.married_status === 'belum_menikah') {
        form.wife_name = '';
        form.wife_birth_place = '';
        form.wife_birth_date = '';
        form.children_count = 0;
    }
    form.post(route('datawarga.store'), {
        onSuccess: () => form.reset()
    });
}
</script>

<template>

    <Head title="Data Warga" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <div class="pb-4">
                <Link href="/datawarga"
                    class="inline-flex items-center text-white bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                <ChevronLeft class="w-4 h-4 mr-2" />
                Back
                </Link>
            </div>

            <form @submit.prevent="submit" enctype="multipart/form-data">
                <div class="grid gap-4">

                    <!-- Data Warga -->
                    <div class="pt-4 font-semibold">Data Diri</div>


                    <div>
                        <Label for="family_card_number">Nomor KK</Label>
                        <Input v-model="form.family_card_number" type="text" maxlength="16" inputmode="numeric"
                            pattern="[0-9]{16}" @input="(e: Event) => {
                                const target = e.target as HTMLInputElement;
                                target.value = target.value.replace(/\D/g, '').slice(0, 16);
                                form.family_card_number = target.value;
                            }" />
                        <InputError :message="form.errors.family_card_number" />
                    </div>

                    <div>
                        <Label for="no_rumah_id">No Rumah</Label>
                        <select v-model="form.no_rumah_id" class="w-full border rounded">
                            <option value="" disabled>Pilih Nomor Rumah</option>
                            <option v-for="rumah in noRumahs" :key="rumah.id" :value="rumah.id">{{ rumah.name }}
                            </option>
                        </select>
                        <InputError :message="form.errors.no_rumah_id" />
                    </div>

                    <!-- Suami -->
                     <div>
                        <Label for="full_name">Nama Lengkap</Label>
                        <Input v-model="form.full_name" class="uppercase" @input="(e: Event) => {
                            const target = e.target as HTMLInputElement;
                            form.full_name = target.value.toUpperCase();
                        }" />
                        <InputError :message="form.errors.full_name" />
                    </div>
                    <div>
                        <Label for="husband_birth_place">Tempat Lahir</Label>
                        <Input v-model="form.husband_birth_place" class="uppercase" @input="(e: Event) => {
                            const target = e.target as HTMLInputElement;
                            form.husband_birth_place = target.value.toUpperCase();
                        }" />
                        <InputError :message="form.errors.husband_birth_place" />
                    </div>

                    <div>
                        <Label for="husband_birth_date">Tanggal Lahir</Label>
                        <Input type="date" v-model="form.husband_birth_date" placeholder="Tanggal Lahir" />
                    </div>

                    <!-- Istri -->
                    <div>
                        <Label for="married_status">Status Pernikahan</Label>
                        <select v-model="form.married_status" class="w-full border rounded">
                            <option value="belum_menikah">Belum Menikah</option>
                            <option value="menikah">Menikah</option>
                        </select>
                        <InputError :message="form.errors.married_status" />
                    </div>
                    <div v-if="form.married_status === 'menikah'">
                        <div class="pt-4 font-semibold">Data Istri</div>
                        <div>
                            <Label for="wife_name">Nama Istri</Label>
                            <Input v-model="form.wife_name" class="uppercase" @input="(e: Event) => {
                                const target = e.target as HTMLInputElement;
                                form.wife_name = target.value.toUpperCase();
                            }" />
                            <InputError :message="form.errors.wife_name" />
                        </div>
                        <div>
                            <Label for="wife_birth_place">Tempat Lahir Istri</Label>
                            <Input v-model="form.wife_birth_place" class="uppercase" @input="(e: Event) => {
                                const target = e.target as HTMLInputElement;
                                form.wife_birth_place = target.value.toUpperCase();
                            }" />
                            <InputError :message="form.errors.wife_birth_place" />
                        </div>
                        <div>
                            <Label for="wife_birth_date">Tanggal Lahir Istri</Label>
                            <Input type="date" v-model="form.wife_birth_date" />
                        </div>
                        <div class="pt-4 font-semibold">Data Anak</div>
                        <Input type="number" min="0" v-model="form.children_count" placeholder="Jumlah Anak" />

                        <div v-for="(child, index) in form.children_data" :key="index"
                            class="grid gap-2 pl-4 border-l border-gray-300 mt-2">
                            <Input v-model="child.name" :placeholder="`Nama Anak ke-${index + 1}`" />
                            <Input v-model="child.birth_place" placeholder="Tempat Lahir" />
                            <Input type="date" v-model="child.birth_date" placeholder="Tanggal Lahir" />
                        </div>

                    </div>

                    <!-- Anak -->


                    <!-- Anggota Keluarga Lain -->
                    <div class="pt-4 font-semibold">Anggota Keluarga Lain</div>
                    <div v-for="(member, index) in form.other_family_members" :key="index"
                        class="flex items-center gap-2">
                        <Input v-model="form.other_family_members[index]" placeholder="Nama Anggota Keluarga Lain" />
                        <Button type="button" variant="destructive" @click="removeOtherFamilyMember(index)"
                            v-if="form.other_family_members.length > 1">Hapus</Button>
                    </div>
                    <Button type="button" variant="secondary" class="mt-2" @click="addOtherFamilyMember">Tambah
                        Anggota</Button>

                    <!-- Status -->
                    <div>
                        <Label for="status">Status</Label>
                        <Input v-model="form.status" placeholder="Contoh: Kepala Keluarga" />
                        <InputError :message="form.errors.status" />
                    </div>

                    <!-- Status Tempat Tinggal -->
                    <div>
                        <Label for="residence_status">Status Tempat Tinggal</Label>
                        <Input v-model="form.residence_status" placeholder="Contoh: Tetap / Kontrak" />
                        <InputError :message="form.errors.residence_status" />
                    </div>

                    <!-- Dokumen -->
                    <div>
                        <Label for="document">Upload Dokumen (opsional)</Label>
                        <Input type="file" @change="(e: Event) => {
                            const target = e.target as HTMLInputElement;
                            if (target.files?.[0]) {
                                form.document = target.files[0];
                            }
                        }" />

                        <InputError :message="form.errors.document" />
                    </div>

                </div>

                <div class="py-6 flex items-center gap-4">
                    <Button :disabled="form.processing">Submit</Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
