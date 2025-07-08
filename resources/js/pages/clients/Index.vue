<script setup lang="ts">
import Paginator from '@/components/Paginator.vue';
import { useExpandableRows } from '@/composables/useExpandableRows';
import { useServerSearch } from '@/composables/useServerSearch';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import Button from '@volt/Button.vue';
import ContrastButton from '@volt/ContrastButton.vue';
import DataTable from '@volt/DataTable.vue';
import InputText from '@volt/InputText.vue';
import SecondaryButton from '@volt/SecondaryButton.vue';
import Tag from '@volt/Tag.vue';
import { ChevronsDown, ChevronsUp, Circle, Search, User } from 'lucide-vue-next';
import Column from 'primevue/column';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Klienci',
        href: '/klienci',
    },
];

const props = defineProps({
    clients: Object,
    filters: Object,
});

const { globalSearch } = useServerSearch(props.filters.search || '', 'clients.index');

const { expandedRows, expandAll, collapseAll } = useExpandableRows(props.clients.data);

const dt = ref();
const exportCSV = () => {
    dt.value.exportCSV();
};
</script>

<template>
    <Head title="Klienci" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <DataTable ref="dt" v-model:expandedRows="expandedRows" :value="clients.data" dataKey="id" pt:table="min-w-200">
                <template #header>
                    <div class="flex flex-col-reverse justify-between gap-4 py-4 xl:flex-row xl:py-8">
                        <div class="flex gap-2">
                            <SecondaryButton @click="expandAll"><ChevronsDown /> Rozwiń wszystkie </SecondaryButton>
                            <SecondaryButton @click="collapseAll"><ChevronsUp /> Zwiń wszystkie</SecondaryButton>
                            <!--                            <Button @click="expandAll"><ChevronsDown /> Rozwiń wszystkie </Button>-->
                            <!--                            <Button @click="collapseAll"><ChevronsUp /> Zwiń wszystkie</Button>-->
                        </div>
                        <div class="relative flex items-center gap-2">
                            <Search />
                            <InputText v-model="globalSearch" placeholder="Search" />
                            <Button label="Eksportuj do CSV" @click="exportCSV" />
                        </div>
                    </div>
                </template>
                <Column expander style="width: 5rem" />
                <Column field="name" header="Klient">
                    <template #body="{ data }">
                        <div class="flex items-center gap-3">
                            <Circle :fill="data.projects.some((p) => p.active) ? 'green' : 'red'" class="shrink-0" />
                            <span>{{ data.name }}</span>
                        </div>
                    </template>
                </Column>
                <Column field="email" header="Email"></Column>
                <Column field="phone" header="Telefon"></Column>
                <Column field="nip" header="NIP"></Column>

                <template #expansion="{ data }">
                    <div class="space-y-4 bg-gray-50 p-4">
                        <h5 class="font-semibold">Historia projektów</h5>

                        <DataTable :value="data.projects" dataKey="id" scrollable scrollHeight="200px" removableSort>
                            <div v-if="data.projects.length > 0">
                                <Column field="name" header="Nazwa projektu" sortable />
                                <Column field="service.name" header="Usługa" sortable>
                                    <template #body="{ data }">
                                        <Tag :value="data.service.name" severity="info"></Tag>
                                    </template>
                                </Column>
                                <Column field="start_date" header="Data startu" sortable />
                                <Column field="end_date" header="Data końca" sortable />
                                <Column field="price" header="Cena" sortable>
                                    <template #body="{ data: project }"> {{ Number(project.price).toFixed(2) }} zł </template>
                                </Column>
                                <Column field="active" header="Aktywny" sortable>
                                    <template #body="{ data: project }">
                                        <span :class="project.active ? 'text-green-600' : 'text-red-600'">
                                            {{ project.active ? 'Tak' : 'Nie' }}
                                        </span>
                                    </template>
                                </Column>
                                <Column header="Akcja">
                                    <template #body="{ data: project }">
                                        <Link :href="route('projects.show', { client: project.client.slug, project: project.id })">
                                            <ContrastButton icon="pi pi-search" severity="info" label="Szczegóły projektu" />
                                        </Link>
                                    </template>
                                </Column>
                            </div>
                            <div v-else>
                                <p>Żadne projekty nie zostały zrealizowane.</p>
                            </div>
                        </DataTable>
                    </div>
                </template>

                <Column header="Profil">
                    <template #body="{ data }">
                        <Link :href="route('clients.show', data.slug)">
                            <Button><User /></Button>
                        </Link>
                    </template>
                </Column>
            </DataTable>
            <Paginator :data="clients" />
        </div>
    </AppLayout>
</template>
