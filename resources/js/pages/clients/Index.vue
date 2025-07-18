<script setup lang="ts">
import Paginator from '@/components/Paginator.vue';
import { useExpandableRows } from '@/composables/useExpandableRows';
import { useServerSearch } from '@/composables/useServerSearch';
import AppLayout from '@/layouts/AppLayout.vue';
import DataTableToolbar from '@/components/DataTableToolbar.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import Button from '@/components/volt/Button.vue';
import ContrastButton from '@/components/volt/ContrastButton.vue';
import DataTable from '@/components/volt/DataTable.vue';
import Tag from '@/components/volt/Tag.vue';
import { Circle, User } from 'lucide-vue-next';
import Column from 'primevue/column';
import dayjs from '@/plugins/dayjs'
import { Paginated, Client, Filters } from '@/types/models';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Klienci',
        href: '/klienci',
    },
];

const { clients, filters } = defineProps<{
    clients: Paginated<Client>;
    filters: Filters
}>();

const { globalSearch } = useServerSearch(filters.search || '', 'clients.index');

const { expandedRows, expandAll, collapseAll } = useExpandableRows(clients.data);
</script>

<template>
    <Head title="Klienci" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <DataTable v-model:expandedRows="expandedRows" :value="clients.data" dataKey="id" pt:table="min-w-200">
                <template #header>
                    <DataTableToolbar v-model="globalSearch" :onExpandAll="expandAll" :onCollapseAll="collapseAll" :exportUrl="'clients.export'" :filters="filters" />
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
                <Column field="email" header="Email" />
                <Column field="phone" header="Telefon" />
                <Column field="nip" header="NIP" />

                <template #expansion="{ data }">
                    <div class="space-y-4 bg-gray-50 p-4">
                        <h5 class="font-semibold">Historia projektów</h5>

                        <div v-if="data.projects.length > 0">
                            <DataTable :value="data.projects" dataKey="id" scrollable scrollHeight="200px" removableSort>
                                <Column field="name" header="Nazwa projektu" sortable />
                                <Column field="service.name" header="Usługa" sortable>
                                    <template #body="{ data }">
                                        <Tag :value="data.service.name" severity="info"></Tag>
                                    </template>
                                </Column>
                                <Column field="start_date" header="Data startu" sortable>
                                    <template #body="{data}">
                                        {{ dayjs(data.start_date).format('DD.MM.YYYY') }}
                                    </template>
                                </Column>
                                <Column field="end_date" header="Data zakończenia" sortable>
                                    <template #body="{data}">
                                        {{ dayjs(data.end_date).format('DD.MM.YYYY') }}
                                    </template>
                                </Column>
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
                                            <ContrastButton severity="info" label="Szczegóły projektu" />
                                        </Link>
                                    </template>
                                </Column>
                            </DataTable>
                        </div>
                        <div v-else>
                            <p>Żadne projekty nie zostały zrealizowane.</p>
                        </div>
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
