<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BadgeDollarSign, Banknote, BookA, BookOpen, Cog, Folder, FolderOpen, LayoutGrid, PiggyBank, Plus, SquareUserRound } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Pulpit',
        href: '/pulpit',
        icon: LayoutGrid,
    },
    {
        title: 'Klienci',
        href: '/klienci',
        icon: SquareUserRound,
    },
    {
        title: 'Projekty',
        href: '/projekty',
        icon: FolderOpen,
    },
    {
        title: 'Płatności',
        href: '/platnosci',
        icon: BadgeDollarSign,
    },
    {
        title: 'Koszty',
        href: '/koszty',
        icon: Banknote,
    },
    {
        title: 'Finanse',
        href: '/finanse',
        icon: PiggyBank,
    },
    {
        title: 'Leady',
        href: '/leady',
        icon: BookA,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Nowy',
        icon: Plus,
        children: [
            {
                title: 'Klient',
                href: '/klienci/dodaj',
                icon: SquareUserRound,
            },
            {
                title: 'Projekt',
                href: '/projekty/dodaj',
                icon: Folder,
            },
            {
                title: 'Koszt',
                href: '/koszty/dodaj',
                icon: Banknote,
            },
            {
                title: 'Lead',
                href: '/leady/dodaj',
                icon: BookA,
            },
        ],
    },
    {
        title: 'Konfiguracja',
        href: '/konfiguracja',
        icon: Cog,
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
