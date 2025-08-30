<!-- resources/js/Pages/VerifikasiIpl/Index.vue -->

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

defineProps<{
    pendingIpls: Array<{
        id: number;
        no_rumah: { name: string };
        amount: number;
        payment_date: string;
        status: string;
    }>;
    verificationStatuses: Record<string, string>;
}>();
</script>

<template>
    <Head title="Verifikasi IPL" />

    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-foreground">
                Verifikasi Pembayaran IPL
            </h2>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-card shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-card border-b border-border">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-border">
                                <thead class="bg-muted">
                                    <tr>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-muted-foreground uppercase">No Rumah</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-muted-foreground uppercase">Jumlah</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-muted-foreground uppercase">Tanggal Bayar</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-muted-foreground uppercase">Status</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-muted-foreground uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-card divide-y divide-border">
                                    <tr v-for="ipl in pendingIpls" :key="ipl.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ ipl.no_rumah.name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            Rp {{ ipl.amount.toLocaleString('id-ID') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ new Date(ipl.payment_date).toLocaleDateString('id-ID') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full" 
                                                  :class="{
                                                      'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300': ipl.status === 'pending',
                                                      'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300': ipl.status === 'verified',
                                                      'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300': ipl.status === 'rejected'
                                                  }">
                                                {{ verificationStatuses[ipl.status] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <Link :href="route('verifikasiipl.show', ipl.id)" 
                                                  class="text-primary hover:text-primary/80">
                                                Verifikasi
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