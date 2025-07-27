<script setup lang="ts">
import SectionHeading from '@/components/SectionHeading.vue';
import StyledLink from '@/components/StyledLink.vue';
import DataTable from '@/components/volt/DataTable.vue';
import Divider from '@/components/volt/Divider.vue';
import dayjs from '@/plugins/dayjs';
import { Payment } from '@/types/models';
import Column from 'primevue/column';

const props = defineProps<{
    payments: Payment[];
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

        <div v-if="payments.length < 1" class="mt-4 flex flex-col gap-4 text-xl font-semibold text-gray-500">
            Brak
            <Divider />
        </div>

        <DataTable v-if="payments.length > 0" :value="payments">
            <Column field="id" header="Płatność">
                <template #body="{ data: payment }: { data: Payment }">
                    <StyledLink
                        :href="route('payments.show', { client: payment.project.client.slug, project: payment.project_id, payment: payment.id })"
                        variant="text"
                    >
                        Płatność nr {{ payment.id }}
                    </StyledLink>
                </template>
            </Column>
            <Column field="amount" header="Kwota" />
            <Column field="payment_date" header="Data płatności">
                <template #body="{ data: payment }: { data: Payment }">
                    {{ payment.payment_date ? dayjs(payment.payment_date).format('DD.MM.YYYY') : '-' }}
                </template>
            </Column>
        </DataTable>
    </div>
</template>
