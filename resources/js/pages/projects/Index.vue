<script setup lang="ts">
import DataTableToolbar from '@/components/DataTableToolbar.vue';
import Paginator from '@/components/Paginator.vue';
import Button from '@/components/volt/Button.vue';
import ContrastButton from '@/components/volt/ContrastButton.vue';
import DataTable from '@/components/volt/DataTable.vue';
import Tag from '@/components/volt/Tag.vue';
import { useExpandableRows } from '@/composables/useExpandableRows';
import { useServerSearch } from '@/composables/useServerSearch';
import AppLayout from '@/layouts/AppLayout.vue';
import dayjs from '@/plugins/dayjs';
import type { BreadcrumbItem } from '@/types';
import { Filter, Paginated, Payment, Project } from '@/types/models';
import { Head, Link } from '@inertiajs/vue3';
import { FolderOpenDot } from 'lucide-vue-next';
import Column from 'primevue/column';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Projekty',
        href: '/projekty',
    },
];

const { projects, filters } = defineProps<{
    projects: Paginated<Project>;
    filters: Filter;
}>();

const { globalSearch } = useServerSearch(filters.search || '', 'projects.index');

const { expandedRows, expandAll, collapseAll } = useExpandableRows(projects.data);

function getSortedPayments(project) {
    return [...project.payments].sort((a, b) => {
        if (a.status === 'cancelled' && b.status !== 'cancelled') return 1;
        if (a.status !== 'cancelled' && b.status === 'cancelled') return -1;

        if (!a.payment_date && b.payment_date) return -1;
        if (a.payment_date && !b.payment_date) return 1;
        if (!a.payment_date && !b.payment_date) return 0;

        return new Date(b.payment_date) - new Date(a.payment_date); // descending
    });
}
</script>

<template>
    <Head title="Projekty" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <DataTable v-model:expandedRows="expandedRows" :value="projects.data" dataKey="id" ref="dt">
                <template #header>
                    <DataTableToolbar
                        v-model="globalSearch"
                        :onExpandAll="expandAll"
                        :onCollapseAll="collapseAll"
                        :exportUrl="'projects.export'"
                        :filters="filters"
                    />
                </template>
                <Column expander style="width: 5rem" />
                <Column field="name" header="Projekt" />
                <Column field="client.name" header="Klient" />
                <Column field="price" header="Cena">
                    <template #body="{ data }: { data: Project }"> {{ data.price }} zł </template>
                </Column>
                <Column field="start_date" header="Data startu">
                    <template #body="{ data }: { data: Project }">
                        {{ dayjs(data.start_date).format('DD.MM.YYYY') }}
                    </template>
                </Column>
                <Column field="end_date" header="Data zakończenia">
                    <template #body="{ data }: { data: Project }">
                        {{ dayjs(data.end_date).format('DD.MM.YYYY') }}
                    </template>
                </Column>
                <Column header="Szczegóły">
                    <template #body="{ data }: { data: Project }">
                        <Link :href="route('projects.show', { client: data.client?.slug, project: data.id })">
                            <Button><FolderOpenDot /></Button>
                        </Link>
                    </template>
                </Column>

                <template #expansion="{ data: project }">
                    <div class="space-y-4 bg-gray-50 p-4">
                        <h5 class="font-semibold">Historia płatności</h5>
                        <div v-if="project.payments.length > 0">
                            <DataTable :value="getSortedPayments(project)" dataKey="id" scrollable scrollHeight="200px" removableSort>
                                <Column field="amount" header="Kwota" sortable />
                                <Column field="status" header="Status" sortable>
                                    <template #body="{ data: payment }: { data: Payment }">
                                        <Tag
                                            :value="
                                                payment.status === 'paid' ? 'Opłacona' : payment.status === 'pending' ? 'Oczekująca' : 'Anulowana'
                                            "
                                            :severity="payment.status === 'paid' ? 'success' : payment.status === 'pending' ? 'info' : 'danger'"
                                        />
                                    </template>
                                </Column>
                                <Column field="payment_date" header="Data płatności" sortable>
                                    <template #body="{ data: payment }: { data: Payment }">
                                        {{ payment.payment_date ? dayjs(payment.payment_date).format('DD.MM.YYYY') : '-' }}
                                    </template>
                                </Column>

                                <Column header="Akcja">
                                    <template #body="{ data: payment }: { data: Payment }">
                                        <Link
                                            :href="route('payments.show', { client: project.client.slug, project: project.id, payment: payment.id })"
                                        >
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
