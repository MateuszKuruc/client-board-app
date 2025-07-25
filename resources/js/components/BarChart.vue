<script setup lang="ts">
import { computed } from 'vue';
import Chart from 'primevue/chart';

interface Props {
    labels: string[];
    values: number[];
    type?: string;
    mainLabel?: string;
    secondaryLabel?: string;
}

const props = withDefaults(defineProps<Props>(), {
    type: 'bar',
    mainLabel: 'Płatności miesiąc po miesiącu',
    secondaryLabel: 'Zapłacona kwota'
});

const chartData = computed(() => {
    const colors = [
        '#8B5CF6', // Violet – bold modern purple
        '#F59E0B', // Amber – bright contrast
        '#22D3EE', // Cyan – crisp pop
        '#EF4444', // Red – vivid emphasis

        '#6366F1', // Indigo – deep complement
        '#EC4899', // Pink – energetic accent
        '#14B8A6', // Teal – calming balance
        '#F472B6', // Rose – warm pastel

        '#3B82F6', // Blue – trusted staple
        '#10B981', // Emerald – grounded green
        '#A78BFA', // Lavender – soft highlight
        '#34D399', // Light green – subtle close
    ];

    return {
        labels: props.labels,
        datasets: [
            {
                label: props.secondaryLabel,
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
            display: true,
        },
        title: {
            display: true,
            text: props.mainLabel
        },

    },
};
</script>

<template>
    <Chart :type="type" :data="chartData" :options="chartOptions" />
</template>
