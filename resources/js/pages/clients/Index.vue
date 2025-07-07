<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { FilterMatchMode } from '@primevue/core/api';
import DataTable from '@volt/DataTable.vue';
import InputText from '@volt/InputText.vue';
import SecondaryButton from '@volt/SecondaryButton.vue';
import Column from 'primevue/column';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Klienci',
        href: '/klienci',
    },
];

defineProps({
    clients: Array,
});

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
</script>

<template>
    <Head title="Klienci" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <DataTable
                v-model:filters="filters"
                :globalFilterFields="['name', 'email', 'phone', 'NIP']"
                :value="clients"
                paginator
                :rows="5"
                sortMode="multiple"
                pt:table="min-w-200"
            >
                <template #header>
                    <div class="flex justify-end">
                        <div class="relative">
                            <i class="pi pi-search absolute end-3 top-1/2 z-1 -mt-2 leading-none text-surface-400" />
                            <InputText v-model="filters['global'].value" placeholder="Search" />
                        </div>
                    </div>
                </template>
                <Column field="name" header="Klient" sortable></Column>
                <Column field="email" header="Email" sortable></Column>
                <Column field="phone" header="Telefon" sortable></Column>
                <Column field="NIP" header="NIP" sortable></Column>
                <Column header="Usługi">
                    <template #body="{ data }">
                        <div v-if="data.projects.length > 0">
                            <ul>
                                <li v-for="project in data.projects" :key="project.id">
                                    {{ project.service.name }}
                                </li>
                            </ul>
                        </div>
                        <span v-else>-</span>
                    </template>
                </Column>
                <Column>
                    <template #body="{ data }">
                        <SecondaryButton label="Edit" />
                    </template>
                </Column>
                <Column></Column>
            </DataTable>
        </div>
    </AppLayout>
</template>
