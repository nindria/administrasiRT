<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { ChevronLeft } from 'lucide-vue-next';

defineProps<{
  dataIpl: {
    id: number;
    no_rumah_id: number;
    amount: number;
    payment_date: string;
    payment_method: string;
    receipt_path: string | null;
    notes: string | null;
    status: string;
    recorded_by: number;
    created_at: string;
    updated_at: string;
    no_rumah: {
      id: number;
      name: string;
    };
    recorded_by_user?: {
      id: number;
      name: string;
    };
  };
}>();

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Data IPL', href: '/dataipl' },
  { title: 'Detail', href: '#' },
];
</script>

<template>
  <Head title="Detail Pembayaran IPL" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4 space-y-4">
      <Link href="/dataipl" class="inline-flex items-center text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2">
        <ChevronLeft class="w-4 h-4 mr-2" />
        Kembali
      </Link>

      <div class="bg-white rounded-xl shadow p-6 space-y-4">
        <h2 class="text-xl font-semibold mb-4">Detail Pembayaran IPL</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div><strong>Nomor Rumah:</strong> {{ dataIpl.no_rumah.name }}</div>
          <div><strong>Jumlah Pembayaran:</strong> Rp {{ Number(dataIpl.amount).toLocaleString() }}</div>
          <div><strong>Tanggal Pembayaran:</strong> {{ dataIpl.payment_date }}</div>
          <div><strong>Metode Pembayaran:</strong> {{ dataIpl.payment_method }}</div>
          <div><strong>Status:</strong> 
            <span :class="{
              'text-green-600': dataIpl.status === 'ok',
              'text-yellow-500': dataIpl.status === 'pending',
              'text-red-500': dataIpl.status === 'reject',
            }">{{ dataIpl.status }}</span>
          </div>
          <div><strong>Dicatat Oleh:</strong> {{ dataIpl.recorded_by_user?.name ?? 'Admin' }}</div>
          <div><strong>Catatan:</strong> {{ dataIpl.notes || '-' }}</div>
          <div v-if="dataIpl.receipt_path">
            <strong>Bukti Pembayaran:</strong><br />
            <a :href="`/storage/${dataIpl.receipt_path}`" target="_blank" class="text-blue-600 underline">Lihat File</a>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
