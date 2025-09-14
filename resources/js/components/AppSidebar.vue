<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BadgeCheck, CalendarClock, House, LayoutGrid, Notebook, NotebookTabs, Users } from 'lucide-vue-next';
import { can } from '@/lib/can';
import { computed } from 'vue';
import AppLogo from './AppLogo.vue';

const allNavItems: NavItem[] = [
    {
        title: 'Dasbor',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Pengguna',
        href: '/users',
        icon: Users,
        permission: 'users.view',
    },
    {
        title: 'Peran',
        href: '/roles',
        icon: Notebook,
        permission: 'roles.view',
    },

    {
        title: 'Kartu Keluarga',
        href: '/kartukeluarga',
        icon: House,
        permission: 'kartukeluarga.view',
    },

    {
        title: 'Rumah',
        href: '/rumah',
        icon: House,
        permission: 'rumah.view',
    },
    {
        title: 'Data Warga',
        href: '/datawarga',
        icon: NotebookTabs,
        permission: 'datawarga.view',
    },
    {
        title: 'Verifikasi Warga',
        href: '/verifikasiwarga',
        icon: BadgeCheck,
        permission: 'verifikasiwarga.view',
    },
    {
        title: 'Acara',
        href: '/events',
        icon: CalendarClock,
        permission: 'events.view',
    },
    {
        title: 'Spanduk',
        href: '/banners',
        icon: CalendarClock,
        permission: 'banners.view',
    },
];

const mainNavItems = computed(() =>
    allNavItems.filter(item => !item.permission || can(item.permission))
);

const footerNavItems: NavItem[] = [];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader class="rounded-t-xl">
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter class="rounded-b-xl">
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
