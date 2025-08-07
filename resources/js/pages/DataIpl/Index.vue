<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { PropType } from 'vue';
import { Button } from '@/components/ui/button';
import { Pencil } from 'lucide-vue-next';

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
    required: true
  }
});
const statusClasses = (status: 'verified' | 'pending' | 'reject') => {
  return {
    'bg-green-100 text-green-800': status === 'verified',
    'bg-yellow-100 text-yellow-800': status === 'pending',
    'bg-red-100 text-red-800': status === 'reject'
  };
};

const formatDate = (dateString: string): string => {
  const options: Intl.DateTimeFormatOptions = {
    year: 'numeric' as const,
    month: 'long' as const,
    day: 'numeric' as const
  };
  return new Date(dateString).toLocaleDateString('id-ID', options);
};

const getStatusLabel = (status: 'verified' | 'pending' | 'reject'): string => {
  return {
    verified: 'verified',
    pending: 'Menunggu Verifikasi',
    reject: 'Ditolak'
  }[status];
};
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="px-4 py-6 sm:px-6 lg:px-8">
      <div class="max-w-7xl mx-auto">
        <div class="bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
              <h1 class="text-2xl font-bold">Data Pembayaran IPL</h1>
              <Link :href="route('dataipl.create')"
                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
              Tambah Pembayaran
              </Link>
            </div>

            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No Rumah
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal
                      Bayar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="payment in iplPayments" :key="payment.id">
                    <td class="px-6 py-4 whitespace-nowrap">
                      {{ payment.no_rumah.name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      Rp {{ payment.amount.toLocaleString('id-ID') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      {{ formatDate(payment.payment_date) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      {{ payment.payment_method }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="statusClasses(payment.status)"
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                        {{ getStatusLabel(payment.status) }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <Link :href="route('dataipl.show', payment.id)"
                        class="text-indigo-600 hover:text-indigo-900 mr-3">
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