<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

interface Warga {
  id: number;
  full_name: string;
  family_card_number: string;
  no_rumah?: {
    name: string;
  };
  verification_status: 'pending' | 'verified' | 'rejected';
  verifier?: {
    name: string;
  };
  verified_at?: string;
  rejection_reason?: string;
}

interface Props {
  allWargas: Warga[];
  verificationStatuses: {
    pending: string;
    verified: string;
    rejected: string;
  };
}

const props = defineProps<Props>();
const filterStatus = ref<'all' | 'pending' | 'verified' | 'rejected'>('all');

const filteredWargas = computed(() => {
  if (!props.allWargas) return [];
  if (filterStatus.value === 'all') return props.allWargas;
  return props.allWargas.filter(
    warga => warga.verification_status === filterStatus.value
  );
});

const statusClasses = (status: 'pending' | 'verified' | 'rejected') => {
  return {
    'bg-yellow-100 text-yellow-800': status === 'pending',
    'bg-green-100 text-green-800': status === 'verified',
    'bg-red-100 text-red-800': status === 'rejected'
  };
};

const formatDate = (dateString: string | undefined): string => {
  if (!dateString) return '-';
  const options: Intl.DateTimeFormatOptions = { 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric', 
    hour: '2-digit', 
    minute: '2-digit' 
  };
  return new Date(dateString).toLocaleDateString('id-ID', options);
};
</script>

<template>
  <AppLayout>
    <div class="px-4 py-6 sm:px-6 lg:px-8">
      <div class="max-w-7xl mx-auto">
        <div class="bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <h1 class="text-2xl font-bold mb-6">Verifikasi Data Warga</h1>
            
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">Filter Status</label>
              <select 
                v-model="filterStatus"
                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
              >
                <option value="all">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="verified">Verified</option>
                <option value="rejected">Rejected</option>
              </select>
            </div>

            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No KK</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No Rumah</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail Verifikasi</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="warga in filteredWargas" :key="warga.id">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">{{ warga.full_name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-500">{{ warga.family_card_number }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-500">{{ warga.no_rumah?.name || '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="statusClasses(warga.verification_status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                        {{ verificationStatuses[warga.verification_status] }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      <div v-if="warga.verification_status !== 'pending'">
                        <div>Oleh: {{ warga.verifier?.name || '-' }}</div>
                        <div>Pada: {{ formatDate(warga.verified_at) }}</div>
                        <div v-if="warga.rejection_reason">
                          Alasan: {{ warga.rejection_reason }}
                        </div>
                      </div>
                      <div v-else>-</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <Link 
                        :href="route('verifikasiwarga.show', warga.id)" 
                        class="text-indigo-600 hover:text-indigo-900"
                      >
                        {{ warga.verification_status === 'pending' ? 'Verifikasi' : 'Detail' }}
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