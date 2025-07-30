<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

interface Ipl {
  verified_by: any;
  id: number;
  bulan: string;
  tahun: number;
  nominal: number;
  verification_status: 'pending' | 'ok' | 'rejected';
  verifier?: {
    name: string;
  };
  verified_at?: string;
  rejection_reason?: string;
  warga?: {
    full_name: string;
    family_card_number: string;
    no_rumah?: {
      name: string;
    };
  };
}

interface Props {
  ipls: Ipl[];
  verificationStatuses: {
    pending: string;
    ok: string;
    rejected: string;
  };
}

const props = defineProps<Props>();
const filterStatus = ref<'all' | 'pending' | 'ok' | 'rejected'>('all');

const filteredIpls = computed(() => {
  if (!props.ipls) return [];
  if (filterStatus.value === 'all') return props.ipls;
  return props.ipls.filter(
    ipl => ipl.verification_status === filterStatus.value
  );
});

const statusClasses = (status: 'pending' | 'ok' | 'rejected') => {
  return {
    'bg-yellow-100 text-yellow-800': status === 'pending',
    'bg-green-100 text-green-800': status === 'ok',
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
            <h1 class="text-2xl font-bold mb-6">Verifikasi Data IPL</h1>

            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">Filter Status</label>
              <select 
                v-model="filterStatus"
                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
              >
                <option value="all">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="ok">Ok</option>
                <option value="rejected">Rejected</option>
              </select>
            </div>

            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No KK</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No Rumah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bulan/Tahun</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verifikasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="ipl in filteredIpls" :key="ipl.id">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ ipl.warga?.full_name || '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ ipl.warga?.family_card_number || '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ ipl.warga?.no_rumah?.name || '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ ipl.bulan }}/{{ ipl.tahun }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      Rp{{ ipl.nominal.toLocaleString('id-ID') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="statusClasses(ipl.verification_status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                        {{ verificationStatuses[ipl.verification_status] }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      <div v-if="ipl.verification_status !== 'pending'">
                        <div>Oleh: {{ ipl.verified_by?.name || '-' }}</div>
                        <div>Pada: {{ formatDate(ipl.verified_at) }}</div>
                        <div v-if="ipl.rejection_reason">
                          Alasan: {{ ipl.rejection_reason }}
                        </div>
                      </div>
                      <div v-else>-</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <Link 
                        :href="route('verifikasiipl.show', ipl.id)" 
                        class="text-indigo-600 hover:text-indigo-900"
                      >
                        {{ ipl.verification_status === 'pending' ? 'Verifikasi' : 'Detail' }}
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
