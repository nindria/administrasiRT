<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, NotebookTabs, House, CreditCard, BadgeCheck, CalendarClock, Users } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Users',
        href: '/users',
        icon: Users,
    },

    {
        title: 'Kartu Keluarga',
        href: '/kartukeluarga',
        icon: House,
    },

    {
        title: 'Lokasi',
        href: '/lokasi',
        icon: House,
    },
    {
        title: 'Data Warga',
        href: '/datawarga',
        icon: NotebookTabs,
    },
    {
        title: 'Verifikasi Warga',
        href: '/verifikasiwarga',
        icon: BadgeCheck ,
    },
    {
        title: 'Data IPL',
        href: '/dataipl',
        icon: CreditCard,
    },
    {
        title: 'Verifikasi Ipl',
        href: '/verifikasiipl',
        icon: BadgeCheck ,
    },
    {
        title: 'Event',
        href: '/event',
        icon: CalendarClock,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset" >
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
