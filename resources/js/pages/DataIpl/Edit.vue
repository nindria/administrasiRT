<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { ChevronLeft } from 'lucide-vue-next';
import { type BreadcrumbItem } from '@/types';

const props = defineProps<{
  dataipl: {
    id: number;
    no_rumah_id: number;
    amount: number;
    payment_date: string;
    payment_method: string;
    receipt_path: string | null;
    notes: string;
    status: string;
  };
  noRumahs: Array<{ id: number; name: string }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Data IPL', href: '/dataipl' },
  { title: 'Edit', href: '#' },
];

const form = useForm({
  no_rumah_id: props.dataipl.no_rumah_id,
  amount: props.dataipl.amount,
  payment_date: props.dataipl.payment_date,
  payment_method: props.dataipl.payment_method,
  receipt_path: null as File | null, // Untuk upload file baru
  notes: props.dataipl.notes,
});

function submit() {
  form.post(route('dataipl.update', props.dataipl.id), {
    forceFormData: true,
    onSuccess: () => form.reset('receipt_path'),
  });
}
</script>

<template>
  <Head title="Edit Pembayaran IPL" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
      <div class="pb-4">
        <Link
          href="/dataipl"
          class="inline-flex items-center text-white bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2"
        >
          <ChevronLeft class="w-4 h-4 mr-2" />
          Back
        </Link>
      </div>

      <form @submit.prevent="submit">
        <div class="grid gap-4">
          <div class="pt-4 font-semibold">Edit Data Pembayaran IPL</div>

          <div>
            <Label for="no_rumah_id">No Rumah</Label>
            <select v-model="form.no_rumah_id" class="w-full border rounded">
              <option value="" disabled>Pilih Nomor Rumah</option>
              <option
                v-for="rumah in noRumahs"
                :key="rumah.id"
                :value="rumah.id"
              >
                {{ rumah.name }}
              </option>
            </select>
            <InputError :message="form.errors.no_rumah_id" />
          </div>

          <div>
            <Label for="amount">Jumlah Pembayaran (Rp)</Label>
            <Input v-model="form.amount" type="number" min="0" />
            <InputError :message="form.errors.amount" />
          </div>

          <div>
            <Label for="payment_date">Tanggal Pembayaran</Label>
            <Input v-model="form.payment_date" type="date" />
            <InputError :message="form.errors.payment_date" />
          </div>

          <div>
            <Label for="payment_method">Metode Pembayaran</Label>
            <Input v-model="form.payment_method" type="text" />
            <InputError :message="form.errors.payment_method" />
          </div>

          <div>
            <Label for="receipt_path">Upload Bukti Pembayaran Baru (opsional)</Label>
            <Input
              type="file"
              accept="image/*,application/pdf"
              @input="(e: Event) => form.receipt_path = (e.target as HTMLInputElement).files?.[0] ?? null"
            />
            <InputError :message="form.errors.receipt_path" />
            <div v-if="dataipl.receipt_path" class="text-sm text-gray-500 pt-1">
              Bukti sebelumnya: 
              <a :href="dataipl.receipt_path" target="_blank" class="underline text-blue-600">Lihat</a>
            </div>
          </div>

          <div>
            <Label for="notes">Catatan</Label>
            <textarea v-model="form.notes" class="w-full border rounded p-2" rows="3" />
            <InputError :message="form.errors.notes" />
          </div>
        </div>

        <div class="py-6 flex items-center gap-4">
          <Button :disabled="form.processing">Update</Button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>
