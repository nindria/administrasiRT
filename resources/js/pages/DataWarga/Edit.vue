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

interface Props {
    warga: {
        id: number;
        full_name: string;
        family_card_number: string;
        no_rumah_id: number;
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
        other_family_members: Array<{
            name: string;
            status: string;
        }>;
        residence_status: 'tetap' | 'tidak_tetap';
        document_path: string | null;
    };
    noRumahs: Array<{ id: number; name: string }>;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Edit Data Warga',
        href: `/datawarga/${props.warga.id}/edit`,
    },
];

const form = useForm({
    full_name: props.warga.full_name,
    family_card_number: props.warga.family_card_number,
    no_rumah_id: props.warga.no_rumah_id,
    husband_birth_place: props.warga.husband_birth_place,
    husband_birth_date: props.warga.husband_birth_date,
    married_status: props.warga.married_status,
    wife_name: props.warga.wife_name,
    wife_birth_place: props.warga.wife_birth_place,
    wife_birth_date: props.warga.wife_birth_date,
    children_count: props.warga.children_count || 0,
    children_data: parseChildrenData(props.warga.children_data),
    other_family_members: parseFamilyMembers(props.warga.other_family_members),

    residence_status: props.warga.residence_status,
    document: null as File | null
});

function parseChildrenData(data: any): Array<{ name: string, birth_place: string, birth_date: string }> {
    if (Array.isArray(data)) return [...data];
    if (typeof data === 'string') {
        try {
            return JSON.parse(data) || [];
        } catch {
            return [];
        }
    }
    return [];
}

function parseFamilyMembers(data: any): Array<{ name: string, status: string }> {
    if (Array.isArray(data)) return data.map(member => ({
        name: member.name || '',
        status: member.status || ''
    }));
    if (typeof data === 'string') {
        try {
            const parsed = JSON.parse(data);
            return Array.isArray(parsed) ? parsed.map(member => ({
                name: member.name || '',
                status: member.status || ''
            })) : [];
        } catch {
            return [{ name: '', status: '' }];
        }
    }
    return [{ name: '', status: '' }];
}

watch(() => form.children_count, (newCount) => {
    const count = Math.max(0, Math.min(99, Number(newCount) || 0));
    if (count > form.children_data.length) {
        for (let i = form.children_data.length; i < count; i++) {
            form.children_data.push({ name: '', birth_place: '', birth_date: '' });
        }
    } else if (count < form.children_data.length) {
        form.children_data = form.children_data.slice(0, count);
    }
});

watch(() => form.married_status, (newStatus, oldStatus) => {
    // Only reset if changing from 'menikah' to 'belum_menikah'
    if (newStatus === 'belum_menikah' && oldStatus === 'menikah') {
        form.wife_name = '';
        form.wife_birth_place = '';
        form.wife_birth_date = '';
        form.children_count = 0;
        form.children_data = [];
    }
    // When changing back to 'menikah', restore children count if needed
    if (newStatus === 'menikah' && oldStatus === 'belum_menikah') {
        form.children_count = props.warga.children_data?.length || 0;
        form.children_data = Array.isArray(props.warga.children_data)
            ? [...props.warga.children_data]
            : [];
    }
});

function addOtherFamilyMember() {
    form.other_family_members.push({ name: '', status: '' });
}

function removeOtherFamilyMember(index: number) {
    form.other_family_members.splice(index, 1);
}

function handleChildrenCountInput(e: Event) {
    const target = e.target as HTMLInputElement;
    // Ensure only numbers between 0-99
    let value = target.value.replace(/\D/g, '');
    value = value.slice(0, 2); // Limit to 2 digits
    const numValue = Math.min(99, parseInt(value) || 0);
    form.children_count = numValue;
    target.value = numValue.toString(); // Update input with cleaned value
}

function submit() {
    if (form.married_status === 'belum_menikah') {
        form.wife_name = '';
        form.wife_birth_place = '';
        form.wife_birth_date = '';
        form.children_count = 0;
        form.children_data = [];
    }

    form.children_count = form.children_data.length;
    form.transform((data) => ({
        ...data,
        children_data: form.children_data.filter((child: { name: string; birth_date: string }) => child.name && child.birth_date),
        other_family_members: form.other_family_members.filter((member: { name: string }) => member.name),
        _method: 'put'
    })).post(`/datawarga/${props.warga.id}`, {
        onSuccess: () => form.reset(),
        preserveScroll: true
    })
}
</script>

<template>

    <Head title="Edit Data Warga" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <div class="pb-4">
                <Link href="/datawarga"
                    class="inline-flex items-center text-white bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                <ChevronLeft class="w-4 h-4 mr-2" />
                Kembali
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
                            <option v-for="rumah in noRumahs" :key="rumah.id" :value="rumah.id"
                                :selected="rumah.id === form.no_rumah_id">
                                {{ rumah.name }}
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
                        <Input type="date" v-model="form.husband_birth_date" />
                        <InputError :message="form.errors.husband_birth_date" />
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

                    <template v-if="form.married_status === 'menikah'">
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
                            <InputError :message="form.errors.wife_birth_date" />
                        </div>

                        <div class="pt-4 font-semibold">Data Anak</div>
                        <div>
                            <Label for="children_count">Jumlah Anak</Label>
                            <Input type="number" min="0" v-model.number="form.children_count"
                                @input="handleChildrenCountInput" />
                        </div>

                        <div v-for="(child, index) in form.children_data" :key="index"
                            class="grid gap-2 pl-4 border-l border-gray-300 mt-2">
                            <div>
                                <Label :for="`child_name_${index}`">Nama Anak ke-{{ index + 1 }}</Label>
                                <Input v-model="child.name" :id="`child_name_${index}`" />
                            </div>
                            <div>
                                <Label :for="`child_birth_place_${index}`">Tempat Lahir</Label>
                                <Input v-model="child.birth_place" :id="`child_birth_place_${index}`" />
                            </div>
                            <div>
                                <Label :for="`child_birth_date_${index}`">Tanggal Lahir</Label>
                                <Input type="date" v-model="child.birth_date" :id="`child_birth_date_${index}`" />
                            </div>
                        </div>
                    </template>

                    <!-- Anggota Keluarga Lain -->
                    <div class="pt-4 font-semibold">Anggota Keluarga Lain</div>
                    <div v-for="(member, index) in form.other_family_members" :key="index" class="grid gap-2 mb-4">
                        <div class="flex items-center gap-2">
                            <div class="flex-1">
                                <Label :for="`member_name_${index}`">Nama Anggota</Label>
                                <Input v-model="member.name" :id="`member_name_${index}`" class="uppercase" />
                            </div>
                            <div class="flex-1">
                                <Label :for="`member_status_${index}`">Status</Label>
                                <Input v-model="member.status" :id="`member_status_${index}`" />
                            </div>
                            <Button type="button" variant="destructive" @click="removeOtherFamilyMember(index)"
                                class="self-end mb-1">
                                Hapus
                            </Button>
                        </div>
                    </div>
                    <Button type="button" variant="secondary" class="mt-2" @click="addOtherFamilyMember">
                        Tambah Anggota
                    </Button>

                    <!-- Status Tempat Tinggal -->
                    <div>
                        <Label for="residence_status">Status Tempat Tinggal</Label>
                        <select v-model="form.residence_status" class="w-full border rounded">
                            <option value="tetap">Tetap</option>
                            <option value="tidak_tetap">Tidak Tetap</option>
                        </select>
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
                        <div v-if="props.warga.document_path" class="mt-2">
                            <a :href="props.warga.document_path" target="_blank" class="text-blue-600 underline">
                                Lihat Dokumen Saat Ini
                            </a>
                        </div>
                        <InputError :message="form.errors.document" />
                    </div>
                </div>

                <div class="py-6 flex items-center gap-4">
                    <Button type="submit" :disabled="form.processing">
                        Simpan Perubahan
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>