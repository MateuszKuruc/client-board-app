<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';

const props = defineProps<{
    href: string;
    size?: 'sm' | 'md' | 'lg';
}>();

const base =
    'inline-flex items-center justify-center gap-2 rounded-md font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 text-surface-700 hover:text-amber-800 focus:ring-amber-500 dark:text-gray-300 dark:hover:text-amber-500 duration-300';

const sizes: Record<string, string> = {
    sm: 'px-3 py-1.5 text-xs',
    md: 'px-4 py-2 text-sm',
    lg: 'px-6 py-3 text-base',
};

const classes = `${base} ${sizes[props.size || 'md']}`;
</script>

<template>
    <Link :href="href" :class="classes">
        <ArrowLeft class="w-4" />
        Wstecz
        <slot />
    </Link>
</template>
