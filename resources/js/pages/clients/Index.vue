<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';
import DataTable from '@volt/DataTable.vue';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';  // optional
import Row from 'primevue/row';          // optional
import { ref } from 'vue';
import { FilterMatchMode } from '@primevue/core/api';
import InputText from '@volt/InputText.vue';
import { Link } from '@inertiajs/vue3'
import SecondaryButton from '@volt/SecondaryButton.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Klienci',
        href: '/klienci',
    },
];

defineProps({
    clients: Array
})

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS }
});
</script>

<template>
    <Head title="Klienci" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div>
            <p>yoooo test</p>
            <Link :href="route('clients.index')">Gunwo</Link>
        </div>

        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <DataTable v-model:filters="filters" :globalFilterFields="['name', 'email', 'phone', 'NIP']" :value="clients" paginator :rows="5" sortMode="multiple" pt:table="min-w-200">
                <template #header>
                    <div class="flex justify-end">
                        <div class="relative">
                            <i class="pi pi-search absolute top-1/2 -mt-2 text-surface-400 leading-none end-3 z-1" />
                            <InputText v-model="filters['global'].value" placeholder="Search" />
                        </div>
                    </div>
                </template>
                <Column field="name" header="Klient" sortable></Column>
                <Column field="email" header="Email" sortable></Column>
                <Column field="phone" header="Telefon" sortable></Column>
                <Column field="NIP" header="NIP" sortable></Column>
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

