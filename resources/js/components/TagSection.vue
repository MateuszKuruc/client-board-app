<script setup lang="ts">
import Button from '@/components/volt/Button.vue';
import MultiSelect from '@/components/volt/MultiSelect.vue';
import SecondaryButton from '@/components/volt/SecondaryButton.vue';
import TagComponent from '@/components/volt/Tag.vue';
import { Client, Project, Tag } from '@/types/models';
import { useForm } from '@inertiajs/vue3';
import { Pencil, X } from 'lucide-vue-next';
import { useToast } from 'primevue/usetoast';
import { computed, ref } from 'vue';

const toast = useToast();

const props = defineProps<{
    tags: Tag[];
    client?: Client;
    project?: Project;
}>();

const initialTags = computed(() => {
    if (props.project) {
        return props.project.tags.map((tag) => tag.id);
    }
    if (props.client) {
        return props.client.tags.map((tag) => tag.id);
    }

    return [];
});

const isEditing = ref(false);

const form = useForm({
    tags: initialTags.value,
});

function startEdit() {
    isEditing.value = !isEditing.value;
}

function cancelEdit() {
    form.reset({
        tags: initialTags.value,
    });
    form.clearErrors();
    isEditing.value = !isEditing.value;
}

function submitEdit() {
    let routeName = '';
    let routeParam = '';

    if (props.project) {
        routeName = 'projects.tags.update';
        routeParam = props.project.id;
    }
    if (props.client) {
        routeName = 'clients.tags.update';
        routeParam = props.client.slug;
    }

    form.put(route(routeName, routeParam), {
        onSuccess: () => {
            form.reset({
                tags: initialTags.value,
            });
            isEditing.value = !isEditing.value;
            toast.add({ severity: 'success', summary: 'Sekcja tagów zaktualizowana', detail: 'Dane zostały zapisane w systemie', life: 3000 });
        },
        onError: () => {
            toast.add({ severity: 'error', summary: 'Wystąpił błąd', detail: 'Tagi nie zostały zapisane', life: 3000 });
        },
    });
}
</script>

<template>
    <div class="flex items-center justify-between">
        <div v-if="!isEditing" class="flex w-[600px] flex-wrap gap-2">
            <TagComponent v-for="tag in tags.filter((t) => form.tags.includes(t.id))" :key="tag.id" :value="tag.name" :severity="tag.severity" />
        </div>

        <div v-if="isEditing">
            <MultiSelect
                v-model="form.tags"
                :options="tags"
                optionLabel="name"
                optionValue="id"
                display="chip"
                class="max-w-[500px]"
                placeholder="Wybierz tagi"
            />
        </div>

        <div class="mt-5 flex items-center lg:mt-0">
            <span>
                <SecondaryButton v-if="!isEditing" @click="startEdit">
                    <Pencil class="w-5 text-gray-400" />
                    {{ form.tags.length ? 'Edytuj tagi' : 'Dodaj tagi' }}
                </SecondaryButton>

                <SecondaryButton v-if="isEditing" @click="cancelEdit">
                    <X class="w-5 text-gray-400" />
                    Anuluj
                </SecondaryButton>
            </span>

            <span>
                <Button v-if="isEditing" @click="submitEdit" class="ml-2">
                    <svg class="mr-1.5 -ml-0.5 size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                        <path
                            fill-rule="evenodd"
                            d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    Zapisz
                </Button>
            </span>
        </div>
    </div>
</template>
