<script setup lang="ts">
import SectionHeading from '@/components/SectionHeading.vue';
import StyledLink from '@/components/StyledLink.vue';
import DataTable from '@/components/volt/DataTable.vue';
import Divider from '@/components/volt/Divider.vue';
import SecondaryButton from '@/components/volt/SecondaryButton.vue';
import dayjs from '@/plugins/dayjs';
import { Client, Payment, Project } from '@/types/models';
import { Plus } from 'lucide-vue-next';
import Column from 'primevue/column';

const { heading, subheading, payments } = withDefaults(
    defineProps<{
        payments: Payment[];
        client?: Client;
        project?: Project;
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
            Dodaj płatność</SecondaryButton
        >
    </div>
    <div v-if="payments.length < 1" class="mt-4 flex flex-col gap-4 text-xl font-semibold text-gray-500">
        Brak
        <Divider />
    </div>
    <DataTable v-if="payments.length >= 1" :value="payments" dataKey="id">
        <Column field="id" header="Numer płatności">
            <template #body="{ data: payment }: { data: Payment }"> Płatność {{ payment.id }} </template>
        </Column>
        <Column field="amount" header="Kwota" />
        <Column field="status" header="Status">
            <template #body="{ data: payment }: { data: Payment }">
                {{ payment.status === 'paid' ? 'Opłacona' : payment.status === 'pending' ? 'Oczekująca' : 'Anulowana' }}
            </template>
        </Column>
        <Column field="payment_date" header="Data płatności">
            <template #body="{ data: payment }: { data: Payment }">
                {{ payment.payment_date !== null ? dayjs(payment.payment_date).format('DD.MM.YYYY') : '-' }}
            </template>
        </Column>
        <Column>
            <template #body="{ data: payment }: { data: Payment }">
                <StyledLink variant="text" :href="route('payments.show', { client: client.slug, project: project.id, payment: payment.id })">
                    Zarządzaj
                </StyledLink>
            </template>
        </Column>
    </DataTable>
</template>
