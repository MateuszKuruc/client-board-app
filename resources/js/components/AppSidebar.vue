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
        href: '/pulpit',
        icon: LayoutGrid,
    },
    {
        title: 'Klienci',
        href: route('clients.index'),
        icon: SquareUserRound,
    },
    {
        title: 'Projekty',
        href: route('projects.index'),
        icon: FolderOpen,
    },
    {
        title: 'Płatności',
        href: route('payments.index'),
        icon: BadgeDollarSign,
    },
    {
        title: 'Koszty',
        href: route('expenses.index'),
        icon: Banknote,
    },
    {
        title: 'Finanse',
        href: route('finances.index'),
        icon: PiggyBank,
    },
    {
        title: 'Leady',
        href: route('leads.index'),
        icon: BookA,
    },
    {
        title: 'Moje projekty',
        href: '/projekty/przypisane',
        icon: ScrollText,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Utwórz',
        icon: Plus,
        children: [
            {
                title: 'Klient',
                href: route('clients.create'),
                icon: SquareUserRound,
            },
            {
                title: 'Projekt',
                href: route('projects.create'),
                icon: FolderOpen,
            },
            {
                title: 'Płatność',
                href: route('payments.create'),
                icon: BadgeDollarSign,
            },
            {
                title: 'Koszt',
                href: route('expenses.create'),
                icon: Banknote,
            },
            {
                title: 'Lead',
                href: route('leads.create'),
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
