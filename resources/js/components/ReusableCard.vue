<script setup lang="ts">
import Card from '@/components/volt/Card.vue';
import Tag from '@/components/volt/Tag.vue';
import { Client, Project } from '@/types/models';
import { Link } from '@inertiajs/vue3';
import { FolderOpen, User } from 'lucide-vue-next';

const { value } = defineProps<{
    heading: string;
    subheading?: string;
    value?: number;
    secondValue?: number;
    percentage?: number;
    summary?: number;
    plainNumber?: number;
    client?: Client;
    project?: Project;
}>();
</script>

<template>
    <Card class="flex h-60 justify-center border text-center">
        <template #title>{{ heading }}</template>
        <template #subtitle>{{ subheading }}</template>
        <template #content>
            <div class="flex flex-col gap-2">
                <p v-if="value !== null && value !== undefined" class="text-3xl font-bold text-accent">{{ value > 0 ? `${value} zł` : '-' }}</p>
                <p v-if="percentage" class="text-3xl font-bold">
                    <span :class="percentage >= 0 ? 'text-success' : 'text-danger dark:text-red-500'"> {{ percentage }} % </span>
                </p>
                <p v-if="percentage === null" class="text-3xl font-bold">-</p>
                <p v-if="percentage === 0" class="text-3xl font-bold">0 %</p>
                <p v-if="summary" class="text-3xl font-bold">
                    <span :class="summary >= 0 ? 'text-success' : 'text-danger dark:text-red-500'">
                        {{ summary > 0 ? `+${summary}` : `${summary}` }} zł
                    </span>
                </p>
                <p v-if="summary === 0" class="text-3xl font-bold">0 zł</p>
                <p v-if="secondValue">
                    Pozostało do zapłaty: <span class="text-danger dark:text-red-500">{{ secondValue }} zł</span>
                </p>
                <p v-if="plainNumber" class="text-3xl font-bold text-accent">{{ plainNumber === 0 ? 0 : plainNumber }}</p>

                <Link v-if="client" :href="route('clients.show', { client: client.slug })">
                    <Tag severity="warn">
                        <User />
                        {{ client.name }}
                    </Tag>
                </Link>

                <Link v-if="project" :href="route('projects.show', { client: project.client.slug, project: project.id })">
                    <Tag severity="warn">
                        <FolderOpen />
                        <p class="capitalize-first-letter max-w-xs truncate">
                            {{ project.name }}
                        </p>
                    </Tag>
                </Link>
            </div>
        </template>
    </Card>
</template>
