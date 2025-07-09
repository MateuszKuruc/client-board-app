<script setup lang="ts">
import Paginator from '@/components/Paginator.vue';
import { useExpandableRows } from '@/composables/useExpandableRows';
import { useServerSearch } from '@/composables/useServerSearch';
import AppLayout from '@/layouts/AppLayout.vue';
import DataTableToolbar from '@/pages/clients/DataTableToolbar.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import Button from '@volt/Button.vue';
import ContrastButton from '@volt/ContrastButton.vue';
import DataTable from '@volt/DataTable.vue';
import Tag from '@volt/Tag.vue';
import { FolderOpenDot } from 'lucide-vue-next';
import Column from 'primevue/column';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Projekty',
        href: '/projekty',
    },
];

const props = defineProps({
    projects: Object,
    filters: Object,
});

const { globalSearch } = useServerSearch(props.filters.search || '', 'projects.index');

const { expandedRows, expandAll, collapseAll } = useExpandableRows(props.projects.data);

function getSortedPayments(project) {
    return [...project.payments].sort((a, b) => {
        if (!a.payment_date && b.payment_date) return -1;
        if (a.payment_date && !b.payment_date) return 1;
        if (!a.payment_date && !b.payment_date) return 0;

        return new Date(b.payment_date) - new Date(a.payment_date);
    });
}
</script>

<template>
    <Head title="Projekty" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <DataTable v-model:expandedRows="expandedRows" :value="projects.data" dataKey="id" ref="dt">
                <template #header>
                    <DataTableToolbar v-model="globalSearch" :onExpandAll="expandAll" :onCollapseAll="collapseAll" :exportUrl="'projects.export'" :filters="filters" />
                </template>
                <Column expander style="width: 5rem" />
                <Column field="name" header="Projekt"></Column>
                <Column field="client.name" header="Klient"></Column>
                <!--            <Column field="active" header="Aktywny"></Column>-->
                <Column field="price" header="Cena"></Column>
                <Column field="start_date" header="Data startu"></Column>
                <Column field="end_date" header="Data zakończenia"></Column>
                <Column header="Szczegóły">
                    <template #body="{ data }">
                        <Link :href="route('projects.show', { client: data.client.slug, project: data.id })">
                            <Button><FolderOpenDot /></Button>
                        </Link>
                    </template>
                </Column>

                <template #expansion="{ data }">
                    <div class="space-y-4 bg-gray-50 p-4">
                        <h5 class="font-semibold">Historia płatności</h5>
                        <div v-if="data.payments.length > 0">
                            <DataTable :value="getSortedPayments(data)" dataKey="id" scrollable scrollHeight="200px" removableSort>
                                <Column field="amount" header="Kwota" sortable />
                                <Column field="status" header="Status" sortable>
                                    <template #body="{ data }">
                                        <Tag
                                            :value="data.status === 'paid' ? 'Opłacone' : 'Oczekujące'"
                                            :severity="data.status === 'paid' ? 'success' : 'warn'"
                                        ></Tag>
                                    </template>
                                </Column>
                                <Column field="payment_date" header="Data płatności" sortable>
                                    <template #body="{ data }">
                                        {{ data.payment_date ? data.payment_date : '-' }}
                                    </template>
                                </Column>

                                <Column header="Akcja">
                                    <template #body="{ data }">
                                        <Link :href="route('payments.show', { project: data.project_id, payment: data.id })">
                                            <ContrastButton severity="info" label="Szczegóły płatności" />
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
            </DataTable>
            <Paginator :data="projects" />
        </div>
    </AppLayout>
</template>
