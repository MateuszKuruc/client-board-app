<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { FilterMatchMode } from '@primevue/core/api';
import ContrastButton from '@volt/ContrastButton.vue';
import DataTable from '@volt/DataTable.vue';
import InputText from '@volt/InputText.vue';
import SecondaryButton from '@volt/SecondaryButton.vue';
import { Circle, Search } from 'lucide-vue-next';
import Column from 'primevue/column';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Klienci',
        href: '/klienci',
    },
];

const props = defineProps({
    clients: Array,
});

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const expandedRows = ref({});

const expandAll = () => {
    expandedRows.value = Object.fromEntries(props.clients.map((c) => [c.id, true]));
};

const collapseAll = () => {
    expandedRows.value = null;
};

const dt = ref();
const exportCSV = () => {
    dt.value.exportCSV();
};
</script>

<template>
    <Head title="Klienci" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <DataTable
                ref="dt"
                v-model:filters="filters"
                v-model:expandedRows="expandedRows"
                :globalFilterFields="['name', 'email']"
                :value="clients"
                paginator
                :rows="10"
                dataKey="id"
                removableSort
                pt:table="min-w-200"
            >
                <template #header>
                    <div class="flex justify-between">
                        <div>
                            <SecondaryButton text icon="pi pi-plus" label="Rozwiń wszystkie" @click="expandAll" />
                            <SecondaryButton text icon="pi pi-minus" label="Zwiń wszystkie" @click="collapseAll" />
                        </div>
                        <div class="relative flex gap-2">
                            <!--                            <i class="pi pi-search absolute end-3 top-1/2 z-1 -mt-2 leading-none text-surface-400" />-->
                            <Search />
                            <InputText v-model="filters['global'].value" placeholder="Search" />
                            <SecondaryButton label="Eksportuj do CSV" @click="exportCSV" />
                        </div>
                    </div>
                </template>
                <Column expander style="width: 5rem" />
                <Column field="name" header="Klient" sortable>
                    <template #body="{ data }">
                        <div class="flex items-center gap-3">
                            <Circle :fill="data.projects.some((p) => p.active) ? 'green' : 'red'" class="shrink-0" />
                            <span>{{ data.name }}</span>
                        </div>
                    </template>
                </Column>
                <Column field="email" header="Email" sortable></Column>
                <Column field="phone" header="Telefon"></Column>
                <Column field="NIP" header="NIP"></Column>

                <template #expansion="{ data }">
                    <div class="space-y-4 bg-gray-50 p-4">
                        <h5 class="font-semibold">Historia projektów</h5>

                        <DataTable :value="data.projects" dataKey="id" scrollable scrollHeight="200px" removableSort>
                            <Column field="name" header="Nazwa projektu" sortable />
                            <Column field="service.name" header="Usługa" sortable />
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
                        </DataTable>
                    </div>
                </template>

                <Column>
                    <template #body="{ data }">
                        <Link :href="route('clients.show', data.slug)">
                            <SecondaryButton label="Profil" />
                        </Link>
                    </template>
                </Column>

                <Column></Column>
            </DataTable>
        </div>
    </AppLayout>
</template>
