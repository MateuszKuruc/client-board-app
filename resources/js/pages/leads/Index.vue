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
        title: 'Leady',
        href: '/leady',
    },
];

const props = defineProps({
    leads: Object,
    filters: Object,
});

const { globalSearch } = useServerSearch(props.filters.search || '', 'leads.index');

const dt = ref();
const exportCSV = () => {
    dt.value.exportCSV();
};
</script>

<template>
    <Head title="Leady" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <DataTable :value="leads.data" dataKey="id" ref="dt">
                <template #header>
                    <DataTableToolbar v-model="globalSearch" :onExportCSV="exportCSV" />
                </template>

                <Column field="email" header="Email"> </Column>
                <Column field="phone" header="Telefon">
                    <template #body="{ data }">
                        {{ data.phone ? data.phone : '-' }}
                    </template>
                </Column>
                <Column field="converted_at" header="Status">
                    <template #body="{ data }">
                        <Tag :value="data.converted_at ? 'Przekonwertowany' : 'Potencjalny'" :severity="data.converted_at ? 'success' : 'warn'" />
                    </template>
                </Column>

                <Column header="Aktualizacja">
                    <template #body="{ data }">
                        <!--                        <Link :href="route('projects.show', { client: data.client.slug, project: data.id })">-->
                        <Button><SquarePen />Zamień w klienta</Button>
                        <!--                        </Link>-->
                    </template>
                </Column>
            </DataTable>
            <Paginator :data="leads" />
        </div>
    </AppLayout>
</template>
