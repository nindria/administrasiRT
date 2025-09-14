<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { Pencil } from 'lucide-vue-next';
import { PropType } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Data Ipl',
        href: '/dataipl',
    },
];

interface NoRumah {
    id: number;
    name: string;
}

interface IplPayment {
    id: number;
    no_rumah: NoRumah;
    amount: number;
    payment_date: string;
    payment_method: string;
    status: 'verified' | 'pending' | 'reject';
}

const { iplPayments } = defineProps({
    iplPayments: {
        type: Array as PropType<IplPayment[]>,
        required: true,
    },
});
const statusClasses = (status: 'verified' | 'pending' | 'reject') => {
    return {
        'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300': status === 'verified',
        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300': status === 'pending',
        'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300': status === 'reject',
    };
};

const formatDate = (dateString: string): string => {
    const options: Intl.DateTimeFormatOptions = {
        year: 'numeric' as const,
        month: 'long' as const,
        day: 'numeric' as const,
    };
    return new Date(dateString).toLocaleDateString('id-ID', options);
};

const getStatusLabel = (status: 'verified' | 'pending' | 'reject'): string => {
    return {
        verified: 'verified',
        pending: 'Menunggu Verifikasi',
        reject: 'Ditolak',
    }[status];
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="px-4 py-6 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <div class="bg-card shadow-sm sm:rounded-lg">
                    <div class="border-b border-border bg-card p-6">
                        <div class="mb-6 flex items-center justify-between">
                            <h1 class="text-2xl font-bold text-foreground">Data Pembayaran IPL</h1>
                            <Link :href="route('dataipl.create')" class="rounded-md bg-primary px-4 py-2 text-primary-foreground hover:bg-primary/90">
                                Tambah Pembayaran
                            </Link>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-border">
                                <thead class="bg-muted">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-muted-foreground uppercase">
                                            No Rumah
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-muted-foreground uppercase">Jumlah</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-muted-foreground uppercase">
                                            Tanggal Bayar
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-muted-foreground uppercase">Metode</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-muted-foreground uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-muted-foreground uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-border bg-card">
                                    <tr v-for="payment in iplPayments" :key="payment.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ payment.no_rumah.name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">Rp {{ payment.amount.toLocaleString('id-ID') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ formatDate(payment.payment_date) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ payment.payment_method }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                :class="statusClasses(payment.status)"
                                                class="inline-flex rounded-full px-2 text-xs leading-5 font-semibold"
                                            >
                                                {{ getStatusLabel(payment.status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                            <Link :href="route('dataipl.show', payment.id)" class="mr-3 text-primary hover:text-primary/80">
                                                Detail
                                            </Link>
                                            <Link :href="route('dataipl.edit', payment.id)">
                                                <Button variant="outline" size="sm">
                                                    <Pencil class="h-4 w-4" />
                                                </Button>
                                            </Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
