<script setup lang="ts">
import SectionHeading from '@/components/SectionHeading.vue';
import Button from '@/components/volt/Button.vue';
import DataTable from '@/components/volt/DataTable.vue';
import SecondaryButton from '@/components/volt/SecondaryButton.vue';
import dayjs from '@/plugins/dayjs';
import { Payment } from '@/types/models';
import { Plus } from 'lucide-vue-next';
import Column from 'primevue/column';

const { heading, subheading, payments } = withDefaults(
    defineProps<{
        payments: Payment[];
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
    <span v-if="payments.length < 1" class="mt-4 block text-xl font-semibold">Brak</span>
    <DataTable v-if="payments.length >= 1" class="mt-6" :value="payments" dataKey="id">
        <Column field="amount" header="Kwota" />
        <Column field="status" header="Status">
            <template #body="{ data }: { data: Payment }">
                {{ data.status === 'paid' ? 'Opłacona' : data.status === 'pending' ? 'Oczekująca' : 'Anulowana' }}
            </template>
        </Column>
        <Column field="payment_date" header="Data płatności">
            <template #body="{ data }: { data: Payment }">
                {{ data.start_date !== null ? dayjs(data.start_date).format('DD.MM.YYYY') : '-' }}
            </template>
        </Column>
        <Column>
            <template #body="{ data }: { data: Payment }">
                <Button label="Edytuj"></Button>
            </template>
        </Column>
    </DataTable>
</template>
