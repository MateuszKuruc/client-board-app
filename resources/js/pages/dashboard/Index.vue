<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../../components/PlaceholderPattern.vue';
import ReusableCard from '@/components/ReusableCard.vue';
import Fieldset from '@/components/volt/Fieldset.vue';
import { Payment, Client, Project } from '@/types/models';

const props = defineProps<{
    newestClient: Client;
    newestProject: Project;
    latestPayments: Payment[];
    latestClients: Client[];
    longestClients: Client[];
    activeProjects: number;
    endingProjects: Project[];
    recentlyEndedProjects: Project[];
    biggestValueClients: Client[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Pulpit',
        href: '/pulpit',
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
           <div>

               <Fieldset legend="Statystyki" toggleable>
                   <div class="mx-8 my-4 grid grid-cols-3 gap-4">
                       <ReusableCard heading="Liczba aktywnych projektów" :plainNumber="activeProjects" class="h-fit" />
                       <ReusableCard heading="Najnowszy klient" :client="newestClient" class="h-fit" />
                       <ReusableCard heading="Najnowszy projekt" :project="newestProject" class="h-fit" />
                   </div>
               </Fieldset>

           </div>
        </div>
    </AppLayout>
</template>
