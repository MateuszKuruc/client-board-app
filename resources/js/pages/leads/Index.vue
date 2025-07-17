<script setup lang="ts">
import DataTableToolbar from '@/components/DataTableToolbar.vue';
import Paginator from '@/components/Paginator.vue';
import StyledLink from '@/components/StyledLink.vue';
import DataTable from '@/components/volt/DataTable.vue';
import Tag from '@/components/volt/Tag.vue';
import { useServerSearch } from '@/composables/useServerSearch';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Filters, Lead, Paginated } from '@/types/models';
import { Head } from '@inertiajs/vue3';
import { SquarePen } from 'lucide-vue-next';
import Column from 'primevue/column';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Leady',
        href: '/leady',
    },
];

const { leads, filters } = defineProps<{
    leads: Paginated<Lead>;
    filters: Filters;
}>();

const { globalSearch } = useServerSearch(filters.search || '', 'leads.index');
</script>

<template>
    <Head title="Leady" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <DataTable :value="leads.data" dataKey="id">
                <template #header>
                    <DataTableToolbar v-model="globalSearch" :exportUrl="'leads.export'" :filters="filters" />
                </template>

                <Column field="email" header="Email"> </Column>
                <Column field="phone" header="Telefon">
                    <template #body="{ data: lead }: { data: Lead }">
                        {{ lead.phone ? lead.phone : '-' }}
                    </template>
                </Column>
                <Column field="converted_at" header="Status">
                    <template #body="{ data: lead }: { data: Lead }">
                        <Tag :value="lead.converted_at ? 'Przekonwertowany' : 'Potencjalny'" :severity="lead.converted_at ? 'success' : 'warn'" />
                    </template>
                </Column>

                <Column header="Aktualizacja">
                    <template #body="{ data: lead }: { data: Lead }">
                        <StyledLink variant="text" href="#">
                            <SquarePen />
                            Zamień w klienta
                        </StyledLink>
                    </template>
                </Column>
            </DataTable>
            <Paginator :data="leads" />
        </div>
    </AppLayout>
</template>
