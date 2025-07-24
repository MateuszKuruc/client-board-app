<script setup lang="ts">
import Paginator from '@/components/Paginator.vue';
import ReusableCard from '@/components/ReusableCard.vue';
import StyledLink from '@/components/StyledLink.vue';
import DataTable from '@/components/volt/DataTable.vue';
import SelectButton from '@/components/volt/SelectButton.vue';
import Tab from '@/components/volt/Tab.vue';
import TabList from '@/components/volt/TabList.vue';
import { default as TabPanel, default as TabPanels } from '@/components/volt/TabPanels.vue';
import Tabs from '@/components/volt/Tabs.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import dayjs from '@/plugins/dayjs';
import type { BreadcrumbItem } from '@/types';
import { Expense, Paginated, Payment } from '@/types/models';
import { Head, router } from '@inertiajs/vue3';
import { SquarePen } from 'lucide-vue-next';
import Column from 'primevue/column';
import { computed, ref, watch } from 'vue';

const props = defineProps<{
    month: string;
    payments: Paginated<Payment>;
    expenses: Paginated<Expense>;
    totalPayments: number;
    totalExpenses: number;
    changeInPayments: number;
    changeInExpenses: number;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Finanse',
        href: '/finanse',
    },
];

const optionValues = ref(['Wpływy', 'Wydatki', 'Zestawienie']);
const selectValue = ref(optionValues.value[0]);

const startMonth = dayjs('2024-03-01');
const now = dayjs();

const scrollableTabs = computed(() => {
    const months: string[] = [];
    let currentMonth = now.startOf('month');

    while (currentMonth.isAfter(startMonth, 'month') || currentMonth.isSame(startMonth, 'month')) {
        months.push(currentMonth.format('YYYY-MM'));
        currentMonth = currentMonth.subtract(1, 'month');
    }

    return months;
});

const selectedMonth = ref(props.month);

watch(
    () => selectedMonth.value,
    () => {
        router.get(route('finances.index', { month: selectedMonth.value }));
    },
);
</script>

<template>
    <Head title="Finanse" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="card">
                <Tabs v-model:value="selectedMonth" scrollable>
                    <TabList>
                        <Tab v-for="month in scrollableTabs" :key="month" :value="month">{{ month }}</Tab>
                    </TabList>

                    <TabPanels>
                        <TabPanel class="space-y-2">
                            <SelectButton v-model="selectValue" :options="optionValues" />

                            <div v-if="selectValue === 'Wpływy'">
                                <div class="my-8 grid grid-cols-2 gap-4">
                                    <ReusableCard heading="Wpływy miesięczne" :value="totalPayments" />
                                    <ReusableCard heading="W porównaniu z poprzednim miesiącem" :percentage="changeInPayments" />
                                </div>

                                <DataTable :value="payments.data" dataKey="id">
                                    <Column field="id" header="Nr płatności" />
                                    <Column field="amount" header="Kwota" />
                                    <Column field="project.name" header="Projekt" />
                                    <Column field="project.client.name" header="Klient" />
                                    <Column field="payment_date" header="Data płatności" />
                                    <Column>
                                        <template #body="{ data }: { data: Payment }">
                                            <StyledLink
                                                variant="default"
                                                :href="
                                                    route('payments.show', {
                                                        client: data.project.client.slug,
                                                        project: data.project.id,
                                                        payment: data.id,
                                                    })
                                                "
                                            >
                                                <SquarePen />
                                            </StyledLink>
                                        </template>
                                    </Column>
                                </DataTable>
                                <Paginator :data="payments" />
                            </div>
                        </TabPanel>
                    </TabPanels>
                </Tabs>
            </div>
        </div>
    </AppLayout>
</template>
