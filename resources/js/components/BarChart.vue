<script setup lang="ts">
import { computed } from 'vue';
import Chart from 'primevue/chart';

const props = defineProps<{
    labels: string[];
    values: number[];
}>();

const chartData = computed(() => {
    const colors = [
        '#3B82F6', '#10B981', '#F59E0B', '#EF4444',
        '#6366F1', '#EC4899', '#14B8A6', '#8B5CF6',
        '#F472B6', '#22D3EE', '#A78BFA', '#34D399'
    ];

    return {
        labels: props.labels,
        datasets: [
            {
                label: 'Zapłacona kwota',
                data: props.values,
                backgroundColor: props.values.map((_, i) => colors[i % colors.length])
            }
        ]
    };
});

const chartOptions = {
    responsive: true,
    plugins: {
        legend: {
            display: false,
        },
        title: {
            display: true,
            text: 'Płatności miesiąc po miesiącu'
        },

    },
};
</script>

<template>
    <Chart type="bar" :data="chartData" :options="chartOptions" />
</template>
