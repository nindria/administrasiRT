<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ChevronLeft } from 'lucide-vue-next';

defineProps<{
    noRumahs: Array<{ id: number; name: string }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create',
        href: '/dataipl/create',
    },
];

const form = useForm<{
    no_rumah_id: number | null;
    amount: string;
    payment_date: string;
    payment_method: 'cash' | 'transfer';
    receipt_path: File | null;
    notes: string;
    status: string;
}>({
    no_rumah_id: null,
    amount: '',
    payment_date: '',
    payment_method: 'transfer',
    receipt_path: null,
    notes: '',
    status: 'pending',
});

function submit() {
    form.post(route('dataipl.store'), {
        onSuccess: () => form.reset(),
    });
}
</script>

<template>
    <Head title="Tambah Pembayaran IPL" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="pb-4">
                <Link
                    href="/dataipl"
                    class="me-2 mb-2 inline-flex items-center rounded-lg bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-gradient-to-br focus:ring-4 focus:ring-blue-300 focus:outline-none"
                >
                    <ChevronLeft class="mr-2 h-4 w-4" />
                    Back
                </Link>
            </div>

            <form @submit.prevent="submit">
                <div class="grid gap-4">
                    <div class="pt-4 font-semibold">Data Pembayaran IPL</div>

                    <!-- No Rumah -->
                    <div>
                        <Label for="no_rumah_id">No Rumah</Label>
                        <select v-model="form.no_rumah_id" class="w-full rounded border">
                            <option value="" disabled>Pilih Nomor Rumah</option>
                            <option v-for="rumah in noRumahs" :key="rumah.id" :value="rumah.id">{{ rumah.name }}</option>
                        </select>
                        <InputError :message="form.errors.no_rumah_id" />
                    </div>

                    <!-- Amount -->
                    <div>
                        <Label for="amount">Jumlah Pembayaran (Rp)</Label>
                        <Input v-model="form.amount" type="number" min="0" />
                        <InputError :message="form.errors.amount" />
                    </div>

                    <!-- Payment Date -->
                    <div>
                        <Label for="payment_date">Tanggal Pembayaran</Label>
                        <Input v-model="form.payment_date" type="date" />
                        <InputError :message="form.errors.payment_date" />
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <Label for="payment_method">Metode Pembayaran</Label>
                        <select v-model="form.payment_method" class="w-full rounded border">
                            <option value="cash">Cash</option>
                            <option value="transfer">Transfer</option>
                        </select>
                        <InputError :message="form.errors.payment_method" />
                    </div>

                    <!-- Receipt Number -->
                    <div>
                        <Label for="receipt_path">Upload Bukti Pembayaran</Label>
                        <Input
                            type="file"
                            accept="image/*"
                            @input="(e: Event) => (form.receipt_path = (e.target as HTMLInputElement).files?.[0] ?? null)"
                            required
                        />
                        <InputError :message="form.errors.receipt_path" />
                    </div>

                    <!-- Notes -->
                    <div>
                        <Label for="notes">Catatan (Opsional)</Label>
                        <textarea v-model="form.notes" class="w-full rounded border p-2" rows="3" />
                        <InputError :message="form.errors.notes" />
                    </div>
                </div>
                <div class="flex items-center gap-4 py-6">
                    <Button :disabled="form.processing">Submit</Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
