<script setup lang="ts">
import DashboardClientsTable from '@/components/DashboardClientsTable.vue';
import DashboardPaymentsTable from '@/components/DashboardPaymentsTable.vue';
import DashboardProjectsTable from '@/components/DashboardProjectsTable.vue';
import ReusableCard from '@/components/ReusableCard.vue';
import StyledLink from '@/components/StyledLink.vue';
import Fieldset from '@/components/volt/Fieldset.vue';
import Toolbar from '@/components/volt/Toolbar.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Client, Payment, Project } from '@/types/models';
import { Head } from '@inertiajs/vue3';

const props = defineProps<{
    newestClient: Client;
    newestProject: Project;
    latestPayments: Payment[];
    pendingPayments: Payment[];
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
    <Head title="Pulpit" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <Toolbar class="my-8">
                <template #start>
                    <div class="flex flex-col gap-2">
                        <h3 class="font-bold">Przejdź do:</h3>
                        <div class="flex w-full gap-2">
                            <StyledLink :href="route('clients.index')" variant="outline">Klienci</StyledLink>
                            <StyledLink :href="route('projects.index')" variant="outline">Projekty</StyledLink>
                            <StyledLink :href="route('payments.index')" variant="outline">Płatności</StyledLink>
                            <StyledLink :href="route('expenses.index')" variant="outline">Koszty</StyledLink>
                            <StyledLink :href="route('finances.index')" variant="outline">Finanse</StyledLink>
                            <StyledLink :href="route('leads.index')" variant="outline">Leady</StyledLink>
                            <StyledLink :href="route('projects.assigned')" variant="outline">Moje projekty</StyledLink>
                        </div>
                    </div>
                </template>
            </Toolbar>

            <div>
                <Fieldset legend="Aktualności" toggleable>
                    <div class="mx-8 my-4 grid grid-cols-3 gap-4">
                        <ReusableCard heading="Liczba aktywnych projektów" :plainNumber="activeProjects" class="h-fit" />
                        <ReusableCard heading="Najnowszy klient" :client="newestClient" class="h-fit" />
                        <ReusableCard heading="Najnowszy projekt" :project="newestProject" class="h-fit" />
                    </div>
                </Fieldset>

                <div class="flex flex-col gap-16">
                    <DashboardProjectsTable
                        :projects="endingProjects"
                        heading="Niedługo wygasające projekty"
                        subheading="Projekty, które zbliżają się do daty końcowej i nadal nie zostały przedłużone"
                        tag="uwaga"
                        severity="danger"
                    />

                    <DashboardProjectsTable
                        :projects="recentlyEndedProjects"
                        heading="Projekty, które niedawno wygasły"
                        subheading="Zakończone w ostatnim czasie projekty"
                        tag="ważne"
                        severity="warn"
                    />

                    <DashboardPaymentsTable
                        :payments="pendingPayments"
                        heading="Ostatnie płatności w toku"
                        subheading="Lista płatności, na które nadal oczekujemy"
                        tag="oczekujące"
                        severity="warn"
                    />

                    <DashboardPaymentsTable
                        :payments="latestPayments"
                        heading="Ostatnie potwierdzone płatności"
                        subheading="Lista dokonanych w ostatnich dniach płatności"
                        tag="opłacone"
                        severity="success"
                    />

                    <DashboardClientsTable
                        :clients="latestClients"
                        heading="Najnowsi klienci"
                        subheading="Ostatnio pozyskani klienci"
                        tag="top 5"
                        severity="info"
                    />

                    <DashboardClientsTable
                        :clients="biggestValueClients"
                        heading="Najbardziej dochodowi klienci"
                        subheading="Klienci z największą wartością umów w historii"
                        tag="top 5"
                        severity="info"
                    />

                    <DashboardClientsTable
                        :clients="longestClients"
                        heading="Najdłuższe współprace"
                        subheading="Klienci z najdłuższą historią projektów"
                        tag="top 5"
                        severity="info"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
