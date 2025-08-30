<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

interface Rumah {
    id: number;
    name: string;
}

interface User {
    id: number;
    name: string;
}

interface Ipl {
    id: number;
    no_rumah?: Rumah | null;
    amount?: number | null;
    payment_date?: string | null;
    payment_method?: string | null;
    recorded_by?: User | null;
    created_at?: string | null;
    status?: string | null;
    rejection_reason?: string | null;
}

const props = defineProps<{
    ipl: Ipl;
    verificationStatuses: Record<string, string>;
}>();

const form = useForm({
    status: props.ipl?.status ?? 'pending',
    rejection_reason: props.ipl?.rejection_reason ?? null,
});

const submitForm = () => {
    form.put(route('verifikasiipl.verify', props.ipl?.id), {
        preserveScroll: true,
        onSuccess: () => {
            // Optional success handling
        },
        onError: (errors) => {
            console.error('Verification error:', errors);
        },
    });
};

const formatDate = (dateString: string | null | undefined): string => {
    if (!dateString) return '-';
    try {
        const options: Intl.DateTimeFormatOptions = {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        };
        return new Date(dateString).toLocaleDateString('id-ID', options);
    } catch {
        return '-';
    }
};

// Safe property accessors
const getSafeValue = {
    rumahName: (rumah: Rumah | null | undefined): string => rumah?.name ?? '-',
    userName: (user: User | null | undefined): string => user?.name ?? '-',
    amount: (amount: number | null | undefined): string => {
        if (amount === null || amount === undefined) return '0';
        return amount.toLocaleString('id-ID');
    },
    paymentMethod: (method: string | null | undefined): string => method ?? '-',
};
</script>

<template>
    <Head title="Verifikasi IPL" />

    <AppLayout>
        <template #header>
            <h2 class="text-xl leading-tight font-semibold text-foreground">Verifikasi Pembayaran IPL</h2>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-card shadow-sm sm:rounded-lg">
                    <div class="border-b border-border bg-card p-6">
                        <h1 class="mb-6 text-2xl font-bold text-foreground">Verifikasi Data IPL</h1>

                        <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <h3 class="mb-4 text-lg font-medium text-foreground">Data Pembayaran</h3>
                                <div class="space-y-3">
                                    <p><strong>No Rumah:</strong> {{ getSafeValue.rumahName(ipl?.no_rumah) }}</p>
                                    <p><strong>Jumlah:</strong> Rp {{ getSafeValue.amount(ipl?.amount) }}</p>
                                    <p><strong>Tanggal Bayar:</strong> {{ formatDate(ipl?.payment_date) }}</p>
                                    <p><strong>Metode Pembayaran:</strong> {{ getSafeValue.paymentMethod(ipl?.payment_method) }}</p>
                                </div>
                            </div>

                            <div>
                                <h3 class="mb-4 text-lg font-medium text-foreground">Data Pencatatan</h3>
                                <div class="space-y-3">
                                    <p><strong>Dicatat Oleh:</strong> {{ getSafeValue.userName(ipl?.recorded_by) }}</p>
                                    <p><strong>Tanggal Catat:</strong> {{ formatDate(ipl?.created_at) }}</p>
                                </div>
                            </div>
                        </div>

                        <form @submit.prevent="submitForm">
                            <div class="mb-4">
                                <label class="mb-1 block text-sm font-medium text-foreground"> Status Verifikasi </label>
                                <select
                                    v-model="form.status"
                                    class="w-full rounded-md border-input bg-background text-foreground shadow-sm focus:border-primary focus:ring-primary"
                                    @change="form.rejection_reason = null"
                                >
                                    <option v-for="(label, value) in verificationStatuses" :value="value" :key="value">
                                        {{ label }}
                                    </option>
                                </select>
                                <p v-if="form.errors.status" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.status }}
                                </p>
                            </div>

                            <div class="mb-4" v-if="form.status === 'rejected'">
                                <label class="mb-1 block text-sm font-medium text-foreground"> Alasan Penolakan </label>
                                <textarea
                                    v-model="form.rejection_reason"
                                    class="w-full rounded-md border-input bg-background text-foreground shadow-sm focus:border-primary focus:ring-primary"
                                    rows="3"
                                    required
                                ></textarea>
                                <p v-if="form.errors.rejection_reason" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.rejection_reason }}
                                </p>
                            </div>

                            <div class="flex justify-end space-x-4">
                                <Link
                                    :href="route('verifikasiipl.index')"
                                    class="rounded-md bg-secondary px-4 py-2 text-foreground hover:bg-secondary/80"
                                >
                                    Kembali
                                </Link>
                                <button
                                    type="submit"
                                    class="rounded-md bg-primary px-4 py-2 text-primary-foreground hover:bg-primary/90 disabled:opacity-50"
                                    :disabled="form.processing"
                                >
                                    <span v-if="form.processing">Memproses...</span>
                                    <span v-else>Simpan</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
