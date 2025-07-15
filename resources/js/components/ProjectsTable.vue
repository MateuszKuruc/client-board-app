<script setup lang="ts">
import SectionHeading from '@/components/SectionHeading.vue';
import DataTable from '@/components/volt/DataTable.vue';
import Divider from '@/components/volt/Divider.vue';
import SecondaryButton from '@/components/volt/SecondaryButton.vue';
import dayjs from '@/plugins/dayjs';
import { Project } from '@/types/models';
import { Plus } from 'lucide-vue-next';
import Column from 'primevue/column';

const { projects, heading, subheading } = withDefaults(
    defineProps<{
        projects: Project[];
        heading: string;
        subheading?: string;
        button?: boolean;
    }>(),
    {
        button: false,
    },
);
</script>

<template>
    <div class="flex items-center justify-between">
        <SectionHeading :heading="heading" :subheading="subheading" />
        <SecondaryButton v-if="button">
            <Plus class="w-5 text-gray-400" />
            Dodaj projekt</SecondaryButton
        >
    </div>
    <div v-if="projects.length < 1" class="mt-4 flex flex-col gap-4 text-xl font-semibold text-gray-500">
        Brak
        <Divider />
    </div>
    <DataTable v-if="projects.length >= 1" class="mt-6" :value="projects" dataKey="id">
        <Column field="name" header="Projekt" />
        <Column field="service.name" header="Usługa" />
        <Column field="start_date" header="Data startu">
            <template #body="{ data }: { data: Project }">
                {{ dayjs(data.start_date).format('DD.MM.YYYY') }}
            </template>
        </Column>
        <Column field="end_date" header="Data zakończenia">
            <template #body="{ data }: { data: Project }">
                {{ dayjs(data.end_date).format('DD.MM.YYYY') }}
            </template>
        </Column>
        <Column field="price" header="Cena" />
        <Column field="active" header="Aktywny">
            <template #body="{ data }: { data: Project }">
                <span :class="data.active ? 'text-green-600' : 'text-red-600'">
                    {{ data.active ? 'Tak' : 'Nie' }}
                </span>
            </template>
        </Column>
    </DataTable>
</template>
