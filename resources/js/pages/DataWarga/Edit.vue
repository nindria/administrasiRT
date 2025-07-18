<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

interface ChildData {
    name: string
    birth_place: string
    birth_date: string
}

interface DataWargaType {
    id: number
    full_name: string
    family_card_number: string
    no_rumah_id: number
    husband_name: string
    husband_birth_place: string
    husband_birth_date: string
    wife_name: string
    wife_birth_place: string
    wife_birth_date: string
    children_count: number
    children_data: ChildData[]
    other_family_members: string[]
    status: string
    residence_status: string
    document_path: string | null
}

interface Props {
    warga: DataWargaType
    noRumahs: { id: number; name: string }[]
}

const props = defineProps<Props>()

const form = useForm({
    full_name: props.warga.full_name,
    family_card_number: props.warga.family_card_number,
    no_rumah_id: props.warga.no_rumah_id,
    husband_birth_place: props.warga.husband_birth_place ?? '',
    husband_birth_date: props.warga.husband_birth_date ?? '',
    wife_name: props.warga.wife_name ?? '',
    wife_birth_place: props.warga.wife_birth_place ?? '',
    wife_birth_date: props.warga.wife_birth_date ?? '',
    children_count: props.warga.children_count ?? 0,
    children_data: [] as Array<{ name: string; birth_place: string; birth_date: string }>,
    other_family_members: props.warga.other_family_members ?? [''],
    status: props.warga.status,
    residence_status: props.warga.residence_status,
    document: null as File | null
})

function addChild() {
    form.children_data?.push({
        name: '',
        birth_place: '',
        birth_date: ''
    })
    form.children_count = form.children_data.length
}

function removeChild(index: number) {
    form.children_data.splice(index, 1)
    form.children_count = form.children_data.length
}

function addFamilyMember() {
    form.other_family_members.push('')
}

function removeFamilyMember(index: number) {
    form.other_family_members.splice(index, 1)
}

function submit() {
    form.post(`/datawarga/${props.warga.id}`, {
        preserveScroll: true,
        onBefore: () => {
            form.transform((data) => ({ ...data, _method: 'put' }))
            return true
        }
    })
}
</script>

<template>
    <div class="max-w-4xl mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Edit Data Warga</h1>

        <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-4">

            <div>
                <label>Nama Lengkap:</label>
                <input v-model="form.full_name" type="text" class="border rounded w-full p-1" />
            </div>

            <div>
                <label>No KK:</label>
                <input v-model="form.family_card_number" type="text" class="border rounded w-full p-1" />
            </div>

            <div>
                <label>No Rumah:</label>
                <select v-model="form.no_rumah_id" class="border rounded w-full p-1">
                    <option v-for="rumah in props.noRumahs" :key="rumah.id" :value="rumah.id">
                        {{ rumah.name }}
                    </option>
                </select>
            </div>

            <div>
                <label>Tempat Lahir Suami:</label>
                <input v-model="form.husband_birth_place" type="text" class="border rounded w-full p-1" />
            </div>

            <div>
                <label>Tanggal Lahir Suami:</label>
                <input v-model="form.husband_birth_date" type="date" class="border rounded w-full p-1" />
            </div>

            <div>
                <label>Nama Istri:</label>
                <input v-model="form.wife_name" type="text" class="border rounded w-full p-1" />
            </div>

            <div>
                <label>Tempat Lahir Istri:</label>
                <input v-model="form.wife_birth_place" type="text" class="border rounded w-full p-1" />
            </div>

            <div>
                <label>Tanggal Lahir Istri:</label>
                <input v-model="form.wife_birth_date" type="date" class="border rounded w-full p-1" />
            </div>

            <div>
                <label>Anak-anak:</label>
                <button type="button" @click="addChild" class="ml-2 text-blue-500">+ Tambah Anak</button>
                <div v-for="(child, index) in form.children_data" :key="index"
                    class="mt-2 space-y-1 border p-2 rounded">
                    <input v-model="child.name" placeholder="Nama Anak" class="w-full border rounded p-1" />
                    <input v-model="child.birth_place" placeholder="Tempat Lahir" class="w-full border rounded p-1" />
                    <input v-model="child.birth_date" type="date" class="w-full border rounded p-1" />
                    <button type="button" @click="removeChild(index)" class="text-red-500">Hapus</button>
                </div>
            </div>

            <div>
                <label>Anggota Keluarga Lain:</label>
                <button type="button" @click="addFamilyMember" class="ml-2 text-blue-500">+ Tambah</button>
                <div v-for="(member, index) in form.other_family_members" :key="index" class="mt-1 flex gap-2">
                    <input v-model="form.other_family_members[index]" class="w-full border rounded p-1" />
                    <button type="button" @click="removeFamilyMember(index)" class="text-red-500">Hapus</button>
                </div>
            </div>

            <div>
                <label>Status:</label>
                <input v-model="form.status" type="text" class="border rounded w-full p-1" />
            </div>

            <div>
                <label>Status Tempat Tinggal:</label>
                <input v-model="form.residence_status" type="text" class="border rounded w-full p-1" />
            </div>

            <div>
                <label>Upload Dokumen (opsional):</label>
                <input @change="(e: Event) => {
                    const target = e.target as HTMLInputElement
                    if (target.files) form.document = target.files[0]
                }" type="file" accept=".jpg,.jpeg,.png" />
                <div v-if="props.warga.document_path" class="mt-2">
                    <a :href="props.warga.document_path" target="_blank" class="text-blue-600 underline">Lihat Dokumen
                        Lama</a>
                </div>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                Simpan Perubahan
            </button>
        </form>
    </div>
</template>
