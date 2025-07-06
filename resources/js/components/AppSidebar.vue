<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, Plus } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Klienci',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Projekty',
        href: '/',
        icon: LayoutGrid,
    },
    {
        title: 'Płatności',
        href: '/',
        icon: LayoutGrid,
    },
    {
        title: 'Koszty',
        href: '/',
        icon: LayoutGrid,
    },
    {
        title: 'Finanse',
        href: '/',
        icon: LayoutGrid,
    },
    {
        title: 'Leady',
        href: '/',
        icon: LayoutGrid,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Nowy',
        href: '/',
        icon: Plus,
        children: [
            {
                title: 'Klient',
                href: '/',
                icon: Folder,
            },
            {
                title: 'Projekt',
                href: '/',
                icon: Folder,
            },
            {
                title: 'Koszt',
                href: '/',
                icon: Folder,
            },
            {
                title: 'Lead',
                href: '/',
                icon: Folder,
            },
        ],
    },
    {
        title: 'Panel administracyjny',
        href: '/',
        icon: Folder,
    },
    {
        title: 'Dokumentacja',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
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

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
