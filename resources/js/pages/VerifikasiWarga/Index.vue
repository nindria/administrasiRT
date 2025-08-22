<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import Badge from '@/components/ui/badge/Badge.vue';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Verifikasi Data Warga',
    href: '/verifikasiwarga',
  },
];

interface Warga {
  id: number;
  nik: string;
  full_name: string;
  verification_status: 'pending' | 'verified' | 'rejected';
}

const props = defineProps<{
  wargas: Warga[]
}>()



function updateStatus(nik: string, newStatus:'pending' | 'verified' | 'rejected') {
  router.put(route('verifikasiwarga.update', {verifikasiwarga: nik}), {
    verification_status: newStatus
  })
}
</script>

<template>
  <Head title="Verifikasi Warga" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
      <div class="p-6 space-y-4 bg-white rounded-xl shadow">
        <h1 class="text-xl font-bold">Daftar Verifikasi Warga</h1>

        <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">NIK</th>
              <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Nama</th>
              <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Status</th>
              <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="w in props.wargas" :key="w.nik" class="border-t hover:bg-gray-50">
              <td class="px-4 py-2 text-sm text-gray-800">{{ w.nik }}</td>
              <td class="px-4 py-2 text-sm text-gray-800">{{ w.full_name }}</td>
              <td class="px-4 py-2">
                <Badge :variant="w.verification_status === 'verified'
                  ? 'success'
                  : w.verification_status === 'rejected'
                    ? 'destructive'
                    : 'warning'">
                  {{ w.verification_status }}
                </Badge>
              </td>
              <td class="px-4 py-2 text-center space-x-2">
                <button
                  class="px-3 py-1 rounded-lg bg-green-600 text-white text-sm"
                  @click="updateStatus(w.nik, 'verified')"
                >
                  Verify
                </button>
                <button
                  class="px-3 py-1 rounded-lg bg-red-600 text-white text-sm"
                  @click="updateStatus(w.nik, 'rejected')"
                >
                  Reject
                </button>
                <button
                  class="px-3 py-1 rounded-lg bg-yellow-500 text-white text-sm"
                  @click="updateStatus(w.nik, 'pending')"
                >
                  Pending
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- fallback kalau data kosong -->
        <div v-if="props.wargas.length === 0" class="text-center text-gray-500 py-4">
          Tidak ada data warga pending untuk diverifikasi.
        </div>
      </div>
    </div>
  </AppLayout>
</template>
