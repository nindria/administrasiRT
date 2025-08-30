<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

interface Warga {
  id: number;
  full_name: string;
  family_card_number: string;
  married_status: string;
  wife_name?: string;
  wife_birth_place?: string;
  wife_birth_date?: string;
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
  warga: Warga;
  verificationStatuses: {
    pending: string;
    verified: string;
    rejected: string;
  };
}

const props = defineProps<Props>();

const form = useForm({
  status: props.warga.verification_status,
  rejection_reason: props.warga.rejection_reason || null
});

const statusClasses = (status: 'pending' | 'verified' | 'rejected') => {
  return {
    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300': status === 'pending',
    'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300': status === 'verified',
    'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300': status === 'rejected'
  };
};

const formatDate = (dateString: string | undefined): string => {
  if (!dateString) return '-';
  const options: Intl.DateTimeFormatOptions = { 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric' 
  };
  return new Date(dateString).toLocaleDateString('id-ID', options);
};
</script><template>
 
 
 <AppLayout>
    <div class="px-4 py-6 sm:px-6 lg:px-8">
      <div class="max-w-4xl mx-auto">
        <div class="bg-card shadow-sm sm:rounded-lg border border-border">
          <div class="p-6 bg-card border-b border-border">
            <h1 class="text-2xl font-bold mb-6 text-foreground">Verifikasi Data Warga</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
              <div>
                <h3 class="text-lg font-medium mb-4 text-foreground">Data Utama</h3>
                <div class="space-y-3">
                  <p class="text-foreground"><strong>Nama:</strong> {{ warga.full_name }}</p>
                  <p class="text-foreground"><strong>No KK:</strong> {{ warga.family_card_number }}</p>
                  <p class="text-foreground"><strong>No Rumah:</strong> {{ warga.no_rumah?.name|| '-' }}</p>
                  <p class="text-foreground"><strong>Status Pernikahan:</strong> 
                    {{ warga.married_status === 'menikah' ? 'Menikah' : 'Belum Menikah' }}
                  </p>
                </div>
              </div>
              
              <div v-if="warga.married_status === 'menikah'">
                <h3 class="text-lg font-medium mb-4 text-foreground">Data Istri</h3>
                <div class="space-y-3">
                  <p v-if="warga.wife_name" class="text-foreground"><strong>Nama Istri:</strong> {{ warga.wife_name }}</p>
                  <p v-if="warga.wife_birth_place" class="text-foreground"><strong>Tempat Lahir Istri:</strong> {{ warga.wife_birth_place }}</p>
                  <p v-if="warga.wife_birth_date" class="text-foreground"><strong>Tanggal Lahir Istri:</strong> {{ formatDate(warga.wife_birth_date) }}</p>
                </div>
              </div>
            </div>

            <div v-if="warga.verification_status !== 'pending'" class="mb-6 p-4 bg-muted rounded-md">
              <h3 class="text-lg font-medium mb-2 text-foreground">Status Verifikasi Saat Ini</h3>
              <div class="space-y-2">
                <p class="text-foreground"><strong>Status:</strong> 
                  <span :class="statusClasses(warga.verification_status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                    {{ verificationStatuses[warga.verification_status] }}
                  </span>
                </p>
                <p class="text-foreground"><strong>Diverifikasi oleh:</strong> {{ warga.verifier?.name || '-' }}</p>
                <p class="text-foreground"><strong>Pada tanggal:</strong> {{ formatDate(warga.verified_at) }}</p>
                <p v-if="warga.rejection_reason" class="text-foreground"><strong>Alasan penolakan:</strong> {{ warga.rejection_reason }}</p>
              </div>
            </div>

            <form @submit.prevent="form.post(route('verifikasiwarga.verify', warga.id))">
              <div class="mb-4">
                <label class="block text-sm font-medium text-foreground mb-1">
                  Ubah Status Verifikasi
                </label>
                <select
                  v-model="form.status"
                  class="w-full rounded-md border-input bg-background text-foreground shadow-sm focus:border-ring focus:ring-ring"
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
                <label class="block text-sm font-medium text-foreground mb-1">
                  Alasan Penolakan
                </label>
                <textarea
                  v-model="form.rejection_reason"
                  class="w-full rounded-md border-input bg-background text-foreground shadow-sm focus:border-ring focus:ring-ring"
                  rows="3"
                  required
                ></textarea>
              </div>

              <div class="flex justify-end space-x-4">
                <Link
                  :href="route('verifikasiwarga.index')"
                  class="px-4 py-2 bg-secondary text-secondary-foreground rounded-md hover:bg-secondary/80"
                >
                  Kembali
                </Link>
                <button
                  type="submit"
                  class="px-4 py-2 bg-primary text-primary-foreground rounded-md hover:bg-primary/90 disabled:opacity-50"
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

