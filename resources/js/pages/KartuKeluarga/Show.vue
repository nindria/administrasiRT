<script setup lang="ts">
import { KartuKeluarga } from '@/types';

const props = defineProps<{ kartukeluarga: KartuKeluarga }>();
</script>

<template>
  <div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Detail Kartu Keluarga</h1>
    <div class="mb-4">
      <strong>No KK:</strong> {{ props.kartukeluarga.no_kk }}
    </div>
    <div class="mb-4">
      <strong>Kepala Keluarga (NIK):</strong> {{ props.kartukeluarga.nik }}
    </div>
    <div class="mb-4">
      <strong>Jumlah Anggota:</strong> {{ props.kartukeluarga.jumlah_anggota }}
    </div>
    <div v-if="props.kartukeluarga.foto_ktp_kepala_keluarga" class="mb-4">
      <strong>Foto KTP Kepala Keluarga:</strong>
      <img :src="props.kartukeluarga.foto_ktp_kepala_keluarga" alt="Foto KTP Kepala Keluarga" class="max-w-xs mt-2" />
    </div>
    <div class="mt-6">
      <h2 class="text-xl font-semibold mb-2">Kepala Keluarga</h2>
      <div v-if="props.kartukeluarga.dataWarga" class="mb-4">
        <p><strong>Nama:</strong> {{ props.kartukeluarga.dataWarga.name }}</p>
        <p><strong>NIK:</strong> {{ props.kartukeluarga.dataWarga.nik }}</p>
      </div>
      <div v-else>
        <p>Data kepala keluarga tidak ditemukan.</p>
      </div>
    </div>
  </div>
</template>
