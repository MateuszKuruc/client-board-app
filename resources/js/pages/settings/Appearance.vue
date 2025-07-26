<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import AppearanceTabs from '@/components/AppearanceTabs.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { type BreadcrumbItem } from '@/types';

import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Ustawienia wyglądu',
        href: '/settings/appearance',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Ustawienia wyglądu" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall title="Ustawienia wyglądu" description="Zmień swoje ustawienia wyświetlania" />
                <AppearanceTabs />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
