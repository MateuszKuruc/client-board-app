<script setup lang="ts">
import BarChart from '@/components/BarChart.vue';
import FinanceCards from '@/components/FinanceCards.vue';
import FinanceTable from '@/components/FinanceTable.vue';
import ReusableCard from '@/components/ReusableCard.vue';
import Fieldset from '@/components/volt/Fieldset.vue';
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
import { computed, ref, watch } from 'vue';
import Divider from '@/components/volt/Divider.vue';

const props = defineProps<{
    month: string;
    tab?: string;
    payments: Paginated<Payment>;
    expenses: Paginated<Expense>;
    totalPayments: number;
    totalExpenses: number;
    summary: number;
    changeInPayments: number;
    changeInExpenses: number;
    changeInSummary: number;
    activeSubsValue: number;
    activeProjects: number;
    subPercentage: number;
    previousSubPercentage: number;
    averagePayment: number;
    biggestSub: number;
    averageSub: number;
    averageStandard: number;
    subCount: number;
    standardCount: number;
    subPaymentsTotal: number;
    standardPaymentsTotal: number;
    last3MonthsAverageNet: number;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Finanse',
        href: '/finanse',
    },
];

const optionValues = ref(['Podsumowanie', 'Wpływy', 'Wydatki']);
const selectValue = ref(props.tab || optionValues.value[0]);

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
    () => selectValue.value,
    (newValue) => {
        router.get(
            route('finances.index', {
                month: selectedMonth.value,
                tab: newValue,
            }),
            {},
            {
                preserveState: true,
                preserveScroll: true,
            },
        );
    },
);

watch(
    () => selectedMonth.value,
    () => {
        router.get(route('finances.index', { month: selectedMonth.value, tab: selectValue.value }));
    },
);
</script>

<template>
    <Head title="Finanse" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div>
                <Fieldset legend="Statystyki" toggleable>
                    <div class="mx-8 my-4 grid grid-cols-1 gap-4 xl:grid-cols-3">
                        <ReusableCard heading="Aktualna wartość subskrypcji" :value="activeSubsValue" class="h-fit" />
                        <ReusableCard heading="Średnie wpływy z 3 miesięcy" :value="last3MonthsAverageNet" class="h-fit" />
                        <ReusableCard heading="Liczba aktywnych projektów" :plainNumber="activeProjects" class="h-fit" />
                    </div>
                </Fieldset>

                <Tabs v-model:value="selectedMonth" scrollable>
                    <TabList class="mt-8 w-full">
                        <Tab v-for="month in scrollableTabs" :key="month" :value="month">{{ month }}</Tab>
                    </TabList>

                    <TabPanels>
                        <TabPanel class="space-y-2">
                            <SelectButton v-model="selectValue" :options="optionValues">
                                <template #option="{ option }">
                                    <button
                                        :disabled="option === selectValue"
                                        :class="option !== selectValue ? 'cursor-pointer' : 'cursor-not-allowed'"
                                    >
                                        {{ option }}
                                    </button>
                                </template>
                            </SelectButton>
                            <div v-if="selectValue === optionValues[0]">
                                <FinanceCards
                                    firstHeading="Podsumowanie miesiąca"
                                    secondHeading="W porównaniu do zeszłego miesiąca"
                                    :summary="summary"
                                    :percentage="changeInSummary"
                                />

                                <div class="flex flex-col gap-24">
                                    <div class="grid gap-8 xl:grid-cols-2 xl:gap-0">
                                        <BarChart
                                            v-if="subCount || standardCount"
                                            type="pie"
                                            :labels="['Subskrypcje', 'Standard']"
                                            :values="[subCount, standardCount]"
                                            class="max-w-[500px]"
                                            mainLabel="Udział subskrypcji we wszystkich projektach"
                                            secondaryLabel="Liczba projektów"
                                        />

                                        <div v-else class="xl:mt-[40%] text-center flex flex-col gap-4 text-xl font-semibold text-gray-500">
                                            Brak danych do wykresu
                                        </div>

                                        <Fieldset legend="Miesiąc w liczbach" class="h-fit">
                                            <div class="flex flex-col justify-between gap-4 p-4">
                                                <ReusableCard heading="Subskrypcje w tym miesiącu" :percentage="subPercentage" class="h-fit" />
                                                <ReusableCard heading="Subskrypcje miesiąc temu" :percentage="previousSubPercentage" class="h-fit" />
                                                <ReusableCard heading="Średnia kwota subskrypcji" :value="averageSub" class="h-fit" />
                                            </div>
                                        </Fieldset>
                                    </div>

                                    <div class="grid gap-8 xl:grid-cols-2 xl:gap-0">
                                        <Fieldset legend="Dodatkowe statystyki" class="order-1 xl:order-0">
                                            <div class="flex flex-col justify-between gap-4 p-4">
                                                <ReusableCard heading="Najwyższa subskrypcja" :value="biggestSub" class="h-fit" />
                                                <ReusableCard heading="Średnia opłata za projekt" :value="averagePayment" class="h-fit" />
                                                <ReusableCard heading="Średnia kwota standardowego projektu" :value="averageStandard" class="h-fit" />
                                            </div>
                                        </Fieldset>

                                        <BarChart
                                            v-if="subPaymentsTotal || standardPaymentsTotal"
                                            type="doughnut"
                                            :labels="['Subskrypcje', 'Standard']"
                                            :values="[subPaymentsTotal, standardPaymentsTotal]"
                                            class="max-w-[500px]"
                                            mainLabel="Wysokość zysków z subskrybcji"
                                            secondaryLabel="Zapłacona kwota"
                                        />
                                        <div v-else class="xl:mt-[50%] text-center flex flex-col gap-4 text-xl font-semibold text-gray-500">
                                            Brak danych do wykresu
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="selectValue === optionValues[1]">
                                <FinanceCards
                                    firstHeading="Wpływy miesięczne"
                                    secondHeading="W porównaniu do zeszłego miesiąca"
                                    :value="totalPayments"
                                    :percentage="changeInPayments"
                                />
                                <FinanceTable v-if="totalPayments" :data="payments" />

                                <div v-else class="mt-4 flex flex-col gap-4 text-xl font-semibold text-gray-500">
                                    Brak wpływów
                                    <Divider />
                                </div>
                            </div>

                            <div v-if="selectValue === optionValues[2]">
                                <FinanceCards
                                    firstHeading="Wydatki miesięczne"
                                    secondHeading="W porównaniu do zeszłego miesiąca"
                                    :value="totalExpenses"
                                    :percentage="changeInExpenses"
                                />
                                <FinanceTable v-if="totalExpenses" :data="expenses" />
                                <div v-else class="mt-4 flex flex-col gap-4 text-xl font-semibold text-gray-500">
                                    Brak wydatków
                                    <Divider />
                                </div>
                            </div>
                        </TabPanel>
                    </TabPanels>
                </Tabs>
            </div>
        </div>
    </AppLayout>
</template>
