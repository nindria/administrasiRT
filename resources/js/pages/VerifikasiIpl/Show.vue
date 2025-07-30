<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

interface Ipl {
  verified_by: any;
  id: number;
  bulan: string;
  tahun: number;
  nominal: number;
  no_rumah?: {
    name: string;
  };
  verification_status: 'pending' | 'ok' | 'rejected';
  verifier?: {
    name: string;
  };
  verified_at?: string;
  rejection_reason?: string;
}

interface Props {
  ipl: Ipl;
  verificationStatuses: {
    pending: string;
    ok: string;
    rejected: string;
  };
}

const props = defineProps<Props>();

const form = useForm({
  status: props.ipl.verification_status,
  rejection_reason: props.ipl.rejection_reason || null
});

const statusClasses = (status: 'pending' | 'ok' | 'rejected') => {
  return {
    'bg-yellow-100 text-yellow-800': status === 'pending',
    'bg-green-100 text-green-800': status === 'ok',
    'bg-red-100 text-red-800': status === 'rejected'
  };
};

const formatDate = (dateString?: string): string => {
  if (!dateString) return '-';
  return new Date(dateString).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};
</script>

<template>
  <AppLayout>
    <div class="px-4 py-6 sm:px-6 lg:px-8">
      <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <h1 class="text-2xl font-bold mb-6">Verifikasi Data IPL</h1>

            <div class="space-y-4 mb-6">
              <p><strong>No Rumah:</strong> {{ ipl.no_rumah?.name || '-' }}</p>
              <p><strong>Bulan:</strong> {{ ipl.bulan }}</p>
              <p><strong>Tahun:</strong> {{ ipl.tahun }}</p>
              <p><strong>Nominal:</strong> Rp {{ ipl.nominal.toLocaleString() }}</p>
            </div>

            <div v-if="ipl.verification_status !== 'pending'" class="mb-6 p-4 bg-gray-50 rounded-md">
              <h3 class="text-lg font-medium mb-2">Status Verifikasi Saat Ini</h3>
              <div class="space-y-2">
                <p><strong>Status:</strong> 
                  <span :class="statusClasses(ipl.verification_status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                    {{ verificationStatuses[ipl.verification_status] }}
                  </span>
                </p>
                <p><strong>Diverifikasi oleh:</strong> {{ ipl.verified_by?.name || '-' }}</p>
                <p><strong>Pada tanggal:</strong> {{ formatDate(ipl.verified_at) }}</p>
                <p v-if="ipl.rejection_reason"><strong>Alasan penolakan:</strong> {{ ipl.rejection_reason }}</p>
              </div>
            </div>

            <form @submit.prevent="form.post(route('verifikasiipl.verify', ipl.id))">
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Ubah Status Verifikasi
                </label>
                <select
                  v-model="form.status"
                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  @change="form.rejection_reason = null"
                >
                  <option 
                    v-for="(label, value) in verificationStatuses" 
                    :value="value"
                    :key="value"
                  >
                    {{ label }}
                  </option>
                </select>
              </div>

              <div class="mb-4" v-if="form.status === 'rejected'">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Alasan Penolakan
                </label>
                <textarea
                  v-model="form.rejection_reason"
                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  rows="3"
                  required
                ></textarea>
              </div>

              <div class="flex justify-end space-x-4">
                <Link
                  :href="route('verifikasiipl.index')"
                  class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400"
                >
                  Kembali
                </Link>
                <button
                  type="submit"
                  class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 disabled:opacity-50"
                  :disabled="form.processing"
                >
                  Simpan Perubahan
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
