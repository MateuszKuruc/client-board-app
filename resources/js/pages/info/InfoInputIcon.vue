<script setup lang="ts">
import InputText from '@/components/volt/InputText.vue';
import { Copy, LucideIcon } from 'lucide-vue-next';
import { useToast } from 'primevue/usetoast';
import { defineProps } from 'vue';

const toast = useToast();

const props = defineProps<{
    data: string | number;
    icon?: LucideIcon;
    text?: string;
}>();

const copyToClipboard = async () => {
    try {
        if (navigator.clipboard && window.isSecureContext) {
            await navigator.clipboard.writeText(props.data);
        } else {
            const textarea = document.createElement('textarea');
            textarea.value = props.data;
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
        }

        toast.add({
            severity: 'success',
            summary: 'Skopiowano!',
            detail: 'Dane zostały skopiowane do schowka',
            life: 3000,
        });
    } catch {
        toast.add({
            severity: 'error',
            summary: 'Błąd',
            detail: 'Nie udało się skopiować danych',
            life: 3000,
        });
    }
};
</script>

<template>
    <div class="flex w-full items-stretch">
        <span
            v-if="!text"
            class="flex w-11.5 items-center justify-center rounded-s-md border-y border-s border-surface-300 bg-surface-0 text-surface-400 dark:border-surface-700 dark:bg-surface-950"
        >
            <component v-if="!text" :is="icon" class="w-5 text-amber-500" />
        </span>
        <span
            v-if="text"
            class="flex w-11.5 items-center justify-center rounded-s-md border-y border-s border-surface-300 bg-surface-0 text-amber-500 dark:border-surface-700 dark:bg-surface-950"
        >
            {{ text }}
        </span>

        <InputText :value="data" readonly pt:root="flex-1 rounded-s-none rounded-e-md pointer-events-none" style="color: #D1D5DB !important" class="truncate" />
        <button
            type="button"
            @click="copyToClipboard"
            class="flex w-10 items-center justify-center rounded-s-md border-y border-s border-surface-300 bg-surface-0 text-surface-400 hover:cursor-pointer hover:text-amber-500 dark:border-surface-700 dark:bg-surface-950"
        >
            <Copy />
        </button>
    </div>
</template>
