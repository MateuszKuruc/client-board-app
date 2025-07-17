<script setup lang="ts">
import SectionHeading from '@/components/SectionHeading.vue';
import StyledLink from '@/components/StyledLink.vue';
import DataTable from '@/components/volt/DataTable.vue';
import Divider from '@/components/volt/Divider.vue';
import dayjs from '@/plugins/dayjs';
import { Expense } from '@/types/models';
import { Plus } from 'lucide-vue-next';
import Column from 'primevue/column';

const props = withDefaults(
    defineProps<{
        expenses: Expense[];
        heading: string;
        subheading?: string;
        button?: boolean;
        href?: string;
    }>(),
    {
        button: false,
    },
);
</script>

<template>
    <div class="flex items-center justify-between">
        <SectionHeading :heading="heading" :subheading="subheading" />
        <StyledLink :href="href" variant="outline" v-if="button">
            <Plus class="w-5 text-gray-400" />
            Dodaj płatność</StyledLink
        >
    </div>
    <div v-if="expenses.length < 1" class="mt-4 flex flex-col gap-4 text-xl font-semibold text-gray-500">
        Brak
        <Divider />
    </div>
    <DataTable v-if="expenses" :value="expenses" dataKey="id">
        <Column field="id" header="Płatność">
            <template #body="{ data: expense }: { data: Expense }"> Nr {{ expense.id }} </template>
        </Column>
        <Column field="amount" header="Kwota" />
        <Column field="is_paid" header="Status">
            <template #body="{ data: expense }: { data: Expense }">
                {{ expense.is_paid ? 'Opłacona' : 'Nieopłacona' }}
            </template>
        </Column>
        <Column field="type" header="Rodzaj płatności" />
        <Column field="payment_date" header="Data płatności">
            <template #body="{ data: expense }: { data: Expense }">
                {{ expense.payment_date !== null ? dayjs(expense.payment_date).format('DD.MM.YYYY') : '-' }}
            </template>
        </Column>
        <Column>
            <template #body="{ data: expense }: { data: Expense }">
                <StyledLink variant="text" :href="route('expenses.show', { expense: expense.id })"> Zarządzaj </StyledLink>
            </template>
        </Column>
    </DataTable>
</template>
