<script setup lang="ts">
import Paginator from '@/components/Paginator.vue';
import { useServerSearch } from '@/composables/useServerSearch';
import AppLayout from '@/layouts/AppLayout.vue';
import DataTableToolbar from '@/pages/clients/DataTableToolbar.vue';
import type { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import Button from '@volt/Button.vue';
import DataTable from '@volt/DataTable.vue';
import Tag from '@volt/Tag.vue';
import { SquarePen } from 'lucide-vue-next';
import Column from 'primevue/column';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Płatności',
        href: '/platnosci',
    },
];

const props = defineProps({
    payments: Object,
    filters: Object,
});

const { globalSearch } = useServerSearch(props.filters.search || '', 'payments.index');

const dt = ref();
const exportCSV = () => {
    dt.value.exportCSV();
};
</script>

<template>
    <Head title="Klienci" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <DataTable :value="payments.data" dataKey="id" ref="dt">
                <DataTableToolbar v-model="globalSearch" onExportCSV="exportCSV" />
                <template #header> </template>
                <Column field="status" header="Status">
                    <template #body="{ data }">
                        <Tag :value="data.status === 'paid' ? 'Opłacone' : 'Oczekujące'" :severity="data.status === 'paid' ? 'success' : 'warn'" />
                    </template>
                </Column>
                <Column field="amount" header="Kwota"></Column>
                <Column field="project.client.name" header="Klient"></Column>
                <Column field="project.name" header="Projekt"></Column>
                <Column field="payment_date" header="Data płatności">
                    <template #body="{ data }">
                        {{ data.payment_date ? data.payment_date : '-' }}
                    </template>
                </Column>
                <Column header="Aktualizacja">
                    <template #body="{ data }">
                        <!--                        <Link :href="route('projects.show', { client: data.client.slug, project: data.id })">-->
                        <Button><SquarePen />Edytuj</Button>
                        <!--                        </Link>-->
                    </template>
                </Column>
            </DataTable>
            <Paginator :data="payments" />
        </div>
    </AppLayout>
</template>
