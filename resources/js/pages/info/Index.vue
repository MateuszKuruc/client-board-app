<script setup lang="ts">
import Fieldset from '@/components/volt/Fieldset.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import InfoInputIcon from '@/pages/info/InfoInputIcon.vue';
import type { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Building2, Facebook, Instagram, Locate, Youtube } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Informacje biznesowe',
        href: '/info',
    },
];

const data = {
    name: 'K&M Systems sp. z o.o.',
    nip: 7941840960,
    address: 'Budowlanych 1, 37-200 Przeworsk',
    website: 'https://studiofeniks.eu',
    fbFeniks: 'https://www.facebook.com/studiofeniks.eu',
    igFeniks: 'https://www.instagram.com/studiofeniks.eu/',
    ytFeniks: 'https://www.youtube.com/channel/UCqLeL0D1JrjsRx4mBW4SI-Q',
    portfolio: [
        {
            id: 1,
            url: 'https://mateuszkuruc.com',
        },
        {
            id: 2,
            url: 'https://kurucbartek.pl',
        },
        {
            id: 3,
            url: 'https://patrykmisiewicz.pl',
        },
        {
            id: 4,
            url: 'https://studiofeniks.eu/portfolio-reels',
        },
        {
            id: 5,
            url: 'https://studiofeniks.eu/portfolio-reklamy',
        },
    ],
    vsl: 'https://sprzedawajwiedze.pl/',
    fbBarti: 'https://www.facebook.com/profile.php?id=61565315033883',
    igBarti: 'https://www.instagram.com/bartek_kuruc/',
    ytBarti: 'https://www.youtube.com/@Bartek_kuruc',
};
</script>

<template>
    <Head title="Informacje biznesowe" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-8 overflow-x-auto rounded-xl p-4">
            <Fieldset legend="Dane firmy">
                <div class="card mt-4 grid grid-cols-1 gap-4 xl:grid-cols-2">
                    <InfoInputIcon :data="data.name" :icon="Building2" />
                    <InfoInputIcon :data="data.address" :icon="Locate" />
                    <InfoInputIcon :data="data.nip" text="NIP" />
                    <InfoInputIcon :data="data.website" :icon="Locate" text="www" />
                    <InfoInputIcon :data="data.fbFeniks" :icon="Facebook" />
                    <InfoInputIcon :data="data.igFeniks" :icon="Instagram" />
                    <InfoInputIcon :data="data.ytFeniks" :icon="Youtube" />
                </div>
            </Fieldset>

            <Fieldset legend="Portfolio">
                <div class="card mt-4 grid grid-cols-1 gap-4 xl:grid-cols-2">
                    <InfoInputIcon v-for="item in data.portfolio" :data="item.url" :icon="Locate" text="www" :key="item.id" />
                </div>
            </Fieldset>

            <Fieldset legend="Barti Ekspert">
                <div class="card mt-4 grid grid-cols-1 gap-4 xl:grid-cols-2">
                    <InfoInputIcon :data="data.vsl" text="www" />
                    <InfoInputIcon :data="data.fbBarti" :icon="Facebook" />
                    <InfoInputIcon :data="data.igBarti" :icon="Instagram" />
                    <InfoInputIcon :data="data.ytBarti" :icon="Youtube" />
                </div>
            </Fieldset>
        </div>
    </AppLayout>
</template>
