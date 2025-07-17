<script setup lang="ts">
import DataTableToolbar from '@/components/DataTableToolbar.vue';
import Paginator from '@/components/Paginator.vue';
import StyledLink from '@/components/StyledLink.vue';
import DataTable from '@/components/volt/DataTable.vue';
import { useServerSearch } from '@/composables/useServerSearch';
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
</script>

<template>
    <Head title="Leady" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <DataTable :value="expenses.data" dataKey="id">
                <template #header>
                    <DataTableToolbar v-model="globalSearch" :exportUrl="'leads.export'" :filters="filters" />
                </template>
                <Column field="id" header="Numer płatności" />
                <Column field="name" header="Koszt" />
                <Column field="amount" header="Cena" />
                <Column field="type" header="Rodzaj płatności">
                    <template #body="{ data: expense }: { data: Expense }">
                        {{ expense.type }}
                    </template>
                </Column>
                <Column field="is_paid" header="Status płatności">
                    <template #body="{ data: expense }: { data: Expense }">
                        {{ expense.is_paid ? 'Opłacona' : 'Nieopłacona' }}
                    </template>
                </Column>
                <Column field="payment_date" header="Data płatności" />

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
