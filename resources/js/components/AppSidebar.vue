<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import {
    BadgeDollarSign,
    Banknote,
    BookA,
    BookOpen,
    Cog,
    FolderOpen,
    LayoutGrid,
    PiggyBank,
    Plus,
    ScrollText,
    SquareUserRound,
} from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Pulpit',
        href: route('dashboard'),
        pattern: '/pulpit',
        icon: LayoutGrid,
    },
    {
        title: 'Klienci',
        href: route('clients.index'),
        pattern: '/klienci',
        icon: SquareUserRound,
    },
    {
        title: 'Projekty',
        href: route('projects.index'),
        pattern: '/projekty',
        icon: FolderOpen,
    },
    {
        title: 'Płatności',
        href: route('payments.index'),
        pattern: '/platnosci',
        icon: BadgeDollarSign,
    },
    {
        title: 'Koszty',
        href: route('expenses.index'),
        pattern: '/koszty',
        icon: Banknote,
    },
    {
        title: 'Finanse',
        href: route('finances.index'),
        pattern: '/finanse',
        icon: PiggyBank,
    },
    {
        title: 'Leady',
        href: route('leads.index'),
        pattern: '/leady',
        icon: BookA,
    },
    {
        title: 'Moje projekty',
        href: route('projects.assigned'),
        pattern: '/projekty/moje',
        icon: ScrollText,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Utwórz',
        icon: Plus,
        pattern: null,
        children: [
            {
                title: 'Klient',
                href: route('clients.create'),
                pattern: '/klienci/dodaj',
                icon: SquareUserRound,
            },
            {
                title: 'Projekt',
                href: route('projects.create'),
                pattern: '/projekty/dodaj',
                icon: FolderOpen,
            },
            {
                title: 'Płatność',
                href: route('payments.create'),
                pattern: '/platnosci/dodaj',
                icon: BadgeDollarSign,
            },
            {
                title: 'Koszt',
                href: route('expenses.create'),
                pattern: '/koszty/dodaj',
                icon: Banknote,
            },
            {
                title: 'Lead',
                href: route('leads.create'),
                pattern: '/leady/dodaj',
                icon: BookA,
            },
        ],
    },
    {
        title: 'Konfiguracja',
        href: '/konfiguracja',
        pattern: '/konfiguracja',
        icon: Cog,
    },
    {
        title: 'Dokumentacja',
        href: 'https://laravel.com/docs/starter-kits#vue',
        pattern: '/dokumentacja',
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
