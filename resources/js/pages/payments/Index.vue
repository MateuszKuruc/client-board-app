<script setup lang="ts">
import Paginator from '@/components/Paginator.vue';
import { useServerSearch } from '@/composables/useServerSearch';
import AppLayout from '@/layouts/AppLayout.vue';
import DataTableToolbar from '@/components/DataTableToolbar.vue';
import type { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import Button from '@/components/volt/Button.vue';
import DataTable from '@/components/volt/DataTable.vue';
import Tag from '@/components/volt/Tag.vue';
import { SquarePen } from 'lucide-vue-next';
import Column from 'primevue/column';
import dayjs from '@/plugins/dayjs'

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
</script>

<template>
    <Head title="Płatności" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <DataTable :value="payments.data" dataKey="id">
                <template #header>
                    <DataTableToolbar v-model="globalSearch" :exportUrl="'payments.export'" :filters="filters" />
                </template>

                <Column field="status" header="Status">
                    <template #body="{ data }">
                        <Tag :value="data.status === 'paid' ? 'Opłacona' : data.status === 'pending' ? 'Oczekująca' : 'Anulowana'" :severity="data.status === 'paid' ? 'success' : data.status === 'pending' ? 'info' : 'danger' " />
                    </template>
                </Column>
                <Column field="amount" header="Kwota"></Column>
                <Column field="project.client.name" header="Klient"></Column>
                <Column field="project.name" header="Projekt"></Column>
                <Column field="payment_date" header="Data płatności">
                    <template #body="{ data }">
                        {{ data.payment_date ? dayjs(data.payment_date).format('DD.MM.YYYY') : '-' }}
                    </template>
                </Column>
                <Column>
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
