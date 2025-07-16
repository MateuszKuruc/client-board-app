<script setup lang="ts">
import DataTableToolbar from '@/components/DataTableToolbar.vue';
import Paginator from '@/components/Paginator.vue';
import StyledLink from '@/components/StyledLink.vue';
import DataTable from '@/components/volt/DataTable.vue';
import Tag from '@/components/volt/Tag.vue';
import { useServerSearch } from '@/composables/useServerSearch';
import AppLayout from '@/layouts/AppLayout.vue';
import dayjs from '@/plugins/dayjs';
import type { BreadcrumbItem } from '@/types';
import { Filters, Payment } from '@/types/models';
import { Head } from '@inertiajs/vue3';
import { SquarePen } from 'lucide-vue-next';
import Column from 'primevue/column';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Płatności',
        href: '/platnosci',
    },
];

const { payments, filters } = defineProps<{
    payments: Paginated<Payment>;
    filters: Filters;
}>();

const { globalSearch } = useServerSearch(filters.search || '', 'payments.index');
</script>

<template>
    <Head title="Płatności" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <DataTable :value="payments.data" dataKey="id">
                <template #header>
                    <DataTableToolbar v-model="globalSearch" :exportUrl="'payments.export'" :filters="filters" />
                </template>
                <Column field="id" header="Płatność">
                    <template #body="{ data: payment }: { data: Payment }">Nr {{ payment.id }} </template>
                </Column>
                <Column field="status" header="Status">
                    <template #body="{ data }: { data: Payment }">
                        <Tag
                            :value="data.status === 'paid' ? 'Opłacona' : data.status === 'pending' ? 'Oczekująca' : 'Anulowana'"
                            :severity="data.status === 'paid' ? 'success' : data.status === 'pending' ? 'info' : 'danger'"
                        />
                    </template>
                </Column>
                <Column field="amount" header="Kwota"></Column>
                <Column field="project.client.name" header="Klient"></Column>
                <Column field="project.name" header="Projekt"></Column>
                <Column field="payment_date" header="Data płatności">
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
