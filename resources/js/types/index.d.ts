import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
    permissions?: string[];
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
    permission?: string;
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    role: string;
    roles?: Role[];
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Permission {
    id: number;
    name: string;
    created_at?: string;
    updated_at?: string;
}

export interface Role {
    id: number;
    name: string;
    permissions: Permission[];
    created_at?: string;
    updated_at?: string;
}

export interface KartuKeluarga {
    no_kk: string;
    nik: string;
    jumlah_anggota: number;
    foto_ktp_kepala_keluarga?: string;
    public_id?: string;
    dataWarga?: DataWarga;
    rumah?: Rumah;
    created_at?: string;
    updated_at?: string;
}

export interface DataWarga {
    id: number;
    nik: string;
    name: string;
    tempat_lahir: string;
    tanggal_lahir: string;
    jenis_kelamin: string;
    alamat: string;
    agama: string;
    status_perkawinan: string;
    pekerjaan: string;
    kewarganegaraan: string;
    status: string;
    no_kk?: string;
    created_at?: string;
    updated_at?: string;
}

export interface Rumah {
    id: number;
    nik: string;
    alamat: string;
    rt: string;
    rw: string;
    kode_pos: string;
    created_at?: string;
    updated_at?: string;
}

export type BreadcrumbItemType = BreadcrumbItem;
