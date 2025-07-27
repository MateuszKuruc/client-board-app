<script setup lang="ts">
import SectionHeading from '@/components/SectionHeading.vue';
import StyledLink from '@/components/StyledLink.vue';
import DataTable from '@/components/volt/DataTable.vue';
import Divider from '@/components/volt/Divider.vue';
import dayjs from '@/plugins/dayjs';
import { Project } from '@/types/models';
import Column from 'primevue/column';

const props = defineProps<{
    projects: Project[];
    heading: string;
    subheading: string;
    tag?: string;
    severity?: string;
}>();
</script>

<template>
    <div>
        <div class="flex items-center justify-between">
            <SectionHeading :heading="heading" :subheading="subheading" :tag="tag" :severity="severity" />
        </div>
        <div v-if="projects.length < 1" class="mt-4 flex flex-col gap-4 text-xl font-semibold text-gray-500">
            Brak
            <Divider />
        </div>

        <DataTable v-if="projects.length > 0" :value="projects">
            <Column field="name" header="Projekt">
                <template #body="{ data: project }: { data: Project }">
                    <StyledLink :href="route('projects.show', { client: project.client.slug, project: project.id })" variant="text">
                        {{ project.name }}
                    </StyledLink>
                </template>
            </Column>
            <Column field="service.name" header="Usługa" />
            <Column field="price" header="Cena" />
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
            <Column field="active" header="Aktywny">
                <template #body="{ data }: { data: Project }">
                    <span :class="data.active ? 'text-green-600' : 'text-red-600'">
                        {{ data.active ? 'Tak' : 'Nie' }}
                    </span>
                </template>
            </Column>
        </DataTable>
    </div>
</template>
