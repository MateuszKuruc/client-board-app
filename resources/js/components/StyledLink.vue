<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    href: string;
    variant?: 'default' | 'outline' | 'destructive' | 'text';
    size?: 'sm' | 'md' | 'lg';
}>();

const base = 'inline-flex items-center justify-center gap-2 rounded-md font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50';

const variants: Record<string, string> = {
    default: 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500 shadow-sm',
    outline: 'border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:ring-blue-500 shadow-sm',
    destructive: 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500 shadow-sm',
    text: 'text-blue-600 hover:text-blue-500 focus:ring-blue-500 hover:underline',
};

const sizes: Record<string, string> = {
    sm: 'px-3 py-1.5 text-xs',
    md: 'px-4 py-2 text-sm',
    lg: 'px-6 py-3 text-base',
};

const classes = `${base} ${variants[props.variant || 'default']} ${sizes[props.size || 'md']}`;
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
