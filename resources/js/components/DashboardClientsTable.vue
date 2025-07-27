<script setup lang="ts">
import SectionHeading from '@/components/SectionHeading.vue';
import StyledLink from '@/components/StyledLink.vue';
import DataTable from '@/components/volt/DataTable.vue';
import Divider from '@/components/volt/Divider.vue';
import { Client } from '@/types/models';
import Column from 'primevue/column';

const props = defineProps<{
    clients: Client[];
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
        <div v-if="clients.length < 1" class="mt-4 flex flex-col gap-4 text-xl font-semibold text-gray-500">
            Brak
            <Divider />
        </div>

        <DataTable v-if="clients.length > 0" :value="clients">
            <Column field="name" header="Klient">
                <template #body="{ data: client }: { data: Client }">
                    <StyledLink :href="route('clients.show', { client: client.slug })" variant="text">
                        {{ client.name }}
                    </StyledLink>
                </template>
            </Column>
            <Column field="email" header="Email" />
            <Column field="phone" header="Telefon" />
            <Column field="nip" header="NIP" />
            <Column field="source" header="Źródło" />
            <Column field="location" header="Lokalizacja">
                <template #body="{ data: client }: { data: Client }">
                    {{ client.location === 'local' ? 'Lokalny' : client.location === 'remote' ? 'Krajowy' : 'Międzynarodowy' }}
                </template>
            </Column>
        </DataTable>
    </div>
</template>
