<script setup lang="ts">
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Calendar, CheckSquare, FileText, Home, Image, Users } from 'lucide-vue-next';

interface Props {
    stats: {
        total_warga: number;
        total_kartu_keluarga: number;
        total_rumah: number;
        total_event: number;
        total_banner: number;
        total_verifikasi_pending: number;
    };
}

const { stats } = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-xl p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Dashboard Admin</h1>
            </div>

            <!-- Statistik Utama -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                <!-- Kartu Total Warga -->
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Warga</CardTitle>
                        <Users class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats?.total_warga || 0 }}</div>
                        <p class="text-xs text-muted-foreground">Jumlah warga terdaftar</p>
                    </CardContent>
                </Card>

                <!-- Kartu Total Kartu Keluarga -->
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Kartu Keluarga</CardTitle>
                        <FileText class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats?.total_kartu_keluarga || 0 }}</div>
                        <p class="text-xs text-muted-foreground">Jumlah kartu keluarga terdaftar</p>
                    </CardContent>
                </Card>

                <!-- Kartu Total Rumah -->
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Rumah</CardTitle>
                        <Home class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats?.total_rumah || 0 }}</div>
                        <p class="text-xs text-muted-foreground">Jumlah rumah terdaftar</p>
                    </CardContent>
                </Card>

                <!-- Kartu Total Event -->
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Event</CardTitle>
                        <Calendar class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats?.total_event || 0 }}</div>
                        <p class="text-xs text-muted-foreground">Jumlah event terdaftar</p>
                    </CardContent>
                </Card>

                <!-- Kartu Total Banner -->
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Banner</CardTitle>
                        <Image class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats?.total_banner || 0 }}</div>
                        <p class="text-xs text-muted-foreground">Jumlah banner terdaftar</p>
                    </CardContent>
                </Card>

                <!-- Kartu Verifikasi Pending -->
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Verifikasi Pending</CardTitle>
                        <CheckSquare class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats?.total_verifikasi_pending || 0 }}</div>
                        <p class="text-xs text-muted-foreground">Jumlah verifikasi warga pending</p>
                    </CardContent>
                </Card>
            </div>

            <!-- Informasi Tambahan -->
            <div class="grid grid-cols-1 gap-4">
                <!-- Informasi Sistem -->
                <Card class="col-span-1">
                    <CardHeader>
                        <CardTitle>Informasi Sistem</CardTitle>
                        <CardDescription>Status dan informasi sistem</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <div class="ml-4 space-y-1">
                                    <p class="text-sm font-medium">Sistem Administrasi RT</p>
                                    <p class="text-sm text-muted-foreground">Versi 1.0</p>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                    <CardFooter>
                        <p class="text-xs text-muted-foreground">Terakhir diperbarui: {{ new Date().toLocaleDateString('id-ID') }}</p>
                    </CardFooter>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
