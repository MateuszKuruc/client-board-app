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
import type { BreadcrumbItem } from '@/types';
import type { Expense, Filters, Paginated } from '@/types/models';
import { Head } from '@inertiajs/vue3';
import { SquarePen } from 'lucide-vue-next';
import Column from 'primevue/column';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Koszty',
        href: '/koszty',
    },
];

const props = defineProps<{
    expenses: Paginated<Expense>;
    filters: Filters;
}>();

const { globalSearch } = useServerSearch(props.filters.search || '', 'expenses.index');

const { sortBy, sortDir, setSort } = useServerSorting(
    'expenses.index',
    props.filters.sort_by || 'created_at',
    (props.filters.sort_dir as 'asc' | 'desc') || 'desc',
);

const exportParams = useExportParams(globalSearch, sortBy, sortDir);
</script>

<template>
    <Head title="Leady" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <DataTable :value="expenses.data" dataKey="id">
                <template #header>
                    <DataTableToolbar
                        v-model="globalSearch"
                        :exportUrl="'expenses.export'"
                        :exportParams="exportParams"
                        :filters="filters"
                        addButtonLabel="Dodaj koszt"
                        :addButtonRoute="route('expenses.create')"
                    />
                </template>
                <Column field="id" header="Numer płatności" />
                <Column field="name" class="capitalize-first-letter">
                    <template #header>
                        <SortableHeader field="name" :active="sortBy === 'name'" :direction="sortDir" @sort="setSort">Koszt</SortableHeader>
                    </template>
                </Column>
                <Column field="amount">
                    <template #header>
                        <SortableHeader field="amount" :active="sortBy === 'amount'" :direction="sortDir" @sort="setSort">Cena</SortableHeader>
                    </template>
                </Column>
                <Column field="type">
                    <template #header>
                        <SortableHeader field="type" :active="sortBy === 'type'" :direction="sortDir" @sort="setSort"
                            >Rodzaj płatności</SortableHeader
                        >
                    </template>
                    <template #body="{ data: expense }: { data: Expense }">
                        {{ expense.type }}
                    </template>
                </Column>
                <Column field="is_paid">
                    <template #header>
                        <SortableHeader field="is_paid" :active="sortBy === 'is_paid'" :direction="sortDir" @sort="setSort"
                            >Status płatności</SortableHeader
                        >
                    </template>
                    <template #body="{ data: expense }: { data: Expense }">
                        <Tag :value="expense.is_paid ? 'Opłacona' : 'Nieopłacona'" :severity="expense.is_paid ? 'success' : 'danger'" />
                    </template>
                </Column>
                <Column field="payment_date">
                    <template #header>
                        <SortableHeader field="payment_date" :active="sortBy === 'payment_date'" :direction="sortDir" @sort="setSort"
                            >Data płatności</SortableHeader
                        >
                    </template>
                    <template #body="{ data: expense }: { data: Expense }">
                        {{ expense.payment_date ? expense.payment_date : '-' }}
                    </template>
                </Column>

                <Column header="Opcje">
                    <template #body="{ data: expense }: { data: Expense }">
                        <StyledLink variant="text" :href="route('expenses.show', expense.id)">
                            <SquarePen />
                            Edytuj
                        </StyledLink>
                    </template>
                </Column>
            </DataTable>
            <Paginator :data="expenses" />
        </div>
    </AppLayout>
</template>
