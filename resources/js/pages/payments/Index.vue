<script setup lang="ts">
import DataTableToolbar from '@/components/DataTableToolbar.vue';
import Paginator from '@/components/Paginator.vue';
import SortableHeader from '@/components/SortableHeader.vue';
import StyledLink from '@/components/StyledLink.vue';
import DataTable from '@/components/volt/DataTable.vue';
import Tag from '@/components/volt/Tag.vue';
import { useExportParams } from '@/composables/useExportParams';
import { useServerSearch } from '@/composables/useServerSearch';
import { useServerSorting } from '@/composables/useServerSorting';
import AppLayout from '@/layouts/AppLayout.vue';
import dayjs from '@/plugins/dayjs';
import type { BreadcrumbItem } from '@/types';
import { Filters, Payment, Paginated } from '@/types/models';
import { Head } from '@inertiajs/vue3';
import { SquarePen } from 'lucide-vue-next';
import Column from 'primevue/column';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Płatności',
        href: '/platnosci',
    },
];

const props = defineProps<{
    payments: Paginated<Payment>;
    filters: Filters;
}>();

const { globalSearch } = useServerSearch(props.filters.search || '', 'payments.index');

const { sortBy, sortDir, setSort } = useServerSorting(
    'payments.index',
    props.filters.sort_by || 'created_at',
    (props.filters.sort_dir as 'asc' | 'desc') || 'desc',
);

const exportParams = useExportParams(globalSearch, sortBy, sortDir);
</script>

<template>
    <Head title="Płatności" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <DataTable :value="payments.data" dataKey="id">
                <template #header>
                    <DataTableToolbar
                        v-model="globalSearch"
                        :exportUrl="'payments.export'"
                        :exportParams="exportParams"
                        :filters="filters"
                        addButtonLabel="Dodaj płatność"
                        :addButtonRoute="route('payments.create')"
                    />
                </template>
                <Column field="id">
                    <template #header>
                        <SortableHeader field="id" :active="sortBy === 'id'" :direction="sortDir" @sort="setSort">Płatność </SortableHeader>
                    </template>
                    <template #body="{ data: payment }: { data: Payment }">Nr {{ payment.id }} </template>
                </Column>
                <Column field="status">
                    <template #header>
                        <SortableHeader field="status" :active="sortBy === 'status'" :direction="sortDir" @sort="setSort">Status </SortableHeader>
                    </template>
                    <template #body="{ data }: { data: Payment }">
                        <Tag
                            :value="data.status === 'paid' ? 'Opłacona' : data.status === 'pending' ? 'Oczekująca' : 'Anulowana'"
                            :severity="data.status === 'paid' ? 'success' : data.status === 'pending' ? 'info' : 'danger'"
                        />
                    </template>
                </Column>
                <Column field="amount" header="Kwota">
                    <template #body="{ data: payment }: { data: Payment }">
                        {{ payment.amount }} zł
                    </template>
                </Column>
                <Column field="project.client.name" header="Klient" />
                <Column field="project.name" header="Projekt" class="capitalize-first-letter" />
                <Column field="payment_date">
                    <template #header>
                        <SortableHeader field="payment_date" :active="sortBy === 'payment_date'" :direction="sortDir" @sort="setSort"
                            >Data płatności
                        </SortableHeader>
                    </template>
                    <template #body="{ data }: { data: Payment }">
                        {{ data.payment_date ? dayjs(data.payment_date).format('DD.MM.YYYY') : '-' }}
                    </template>
                </Column>
                <Column>
                    <template #body="{ data }: { data: Payment }">
                        <StyledLink
                            variant="default"
                            :href="route('payments.show', { client: data.project.client.slug, project: data.project.id, payment: data.id })"
                        >
                            <SquarePen />
                        </StyledLink>
                    </template>
                </Column>
            </DataTable>
            <Paginator :data="payments" />
        </div>
    </AppLayout>
</template>
