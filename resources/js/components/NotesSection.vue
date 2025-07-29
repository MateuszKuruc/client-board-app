<script setup lang="ts">
import SectionHeading from '@/components/SectionHeading.vue';
import Avatar from '@/components/volt/Avatar.vue';
import Button from '@/components/volt/Button.vue';
import DangerButton from '@/components/volt/DangerButton.vue';
import Dialog from '@/components/volt/Dialog.vue';
import Divider from '@/components/volt/Divider.vue';
import Panel from '@/components/volt/Panel.vue';
import SecondaryButton from '@/components/volt/SecondaryButton.vue';
import Textarea from '@/components/volt/Textarea.vue';
import dayjs from '@/plugins/dayjs';
import { Client, Expense, Note, Payment, Project } from '@/types/models';
import { useForm, usePage } from '@inertiajs/vue3';
import { ArrowDownFromLine, Delete, Expand, Pencil, Plus, X } from 'lucide-vue-next';
import { useToast } from 'primevue/usetoast';
import { ref } from 'vue';

const toast = useToast();

const page = usePage();
const user = page.props.auth.user;

const props = withDefaults(
    defineProps<{
        subheading?: string;
        href: string;
        noteable: Client | Project | Payment | Expense;
        notes: Note[];
    }>(),
    {
        subheading: 'Sprawdź listę dodatkowych notatek',
    },
);

const isActive = ref(false);
const isExpanded = ref(false);
const isPanelCollapsed = ref(false);
const showAllNotes = ref(false);
const showDialog = ref(false);

const noteToEdit = ref<number | null>(null);
const noteToDelete = ref<number | null>(null);

const form = useForm({
    content: '',
    user_id: user.id,
    noteable_type: props.noteable.model_type,
    noteable_id: props.noteable.id,
    edited_at: null,
});

const editForm = useForm({
    content: '',
});

function startNote(): void {
    isActive.value = !isActive.value;
}

function cancelNote(): void {
    isActive.value = false;
}

function submitNote(): void {
    form.post(route('notes.store'), {
        preserveScroll: true,
        onSuccess: () => {
            isActive.value = false;
            form.reset();
            toast.add({ severity: 'success', summary: 'Notatka została zapisana', detail: 'Zmiany zostały pomyślnie zapisane', life: 3000 });
        },
        onError: () => {
            toast.add({ severity: 'error', summary: 'Wystąpił błąd', detail: 'Zmiany nie zostały zapisane', life: 3000 });
        },
    });
}

function startEditingNote(note: Note): void {
    noteToEdit.value = note.id;
    editForm.content = note.content;
}

function cancelEditingNote(): void {
    noteToEdit.value = null;
    editForm.reset();
}

function saveEditedNote(): void {
    if (noteToEdit.value) {
        editForm.put(route('notes.update', noteToEdit.value), {
            preserveScroll: true,
            onSuccess: () => {
                editForm.reset();
                noteToEdit.value = null;
                toast.add({ severity: 'success', summary: 'Notatka została zmieniona', detail: 'Dane zostały pomyślnie zapisane', life: 3000 });
            },
            onError: () => {
                toast.add({ severity: 'error', summary: 'Wystąpił błąd', detail: 'Dane nie zostały zmienione', life: 3000 });
            },
        });
    }
}

function expandNotes(): void {
    isExpanded.value = !isExpanded.value;
}

function openDeleteDialog(noteId: number): void {
    noteToDelete.value = noteId;
    showDialog.value = true;
}

function deleteNote(): void {
    if (noteToDelete.value) {
        form.delete(route('notes.destroy', noteToDelete.value), {
            preserveScroll: true,
            onSuccess: () => {
                toast.add({ severity: 'success', summary: 'Notatka została usunięta', detail: 'Dane zostały pomyślnie usunięte', life: 3000 });
            },
            onError: () => {
                toast.add({ severity: 'error', summary: 'Wystąpił błąd', detail: 'Dane nie zostały usunięte', life: 3000 });
            },
        });
    }
    toggleDialog();
}

function toggleDialog(): void {
    showDialog.value = !showDialog.value;
}
</script>

<template>
    <div>
        <div class="flex items-center justify-between">
            <div class="my-8 w-full">
                <div class="flex items-center justify-between">
                    <SectionHeading heading="Notatki" :subheading="subheading" />
                    <SecondaryButton v-if="!isActive" @click="startNote">
                        <Pencil class="w-5 text-gray-400" />
                        Dodaj notatkę
                    </SecondaryButton>
                </div>

                <div class="flex flex-col gap-3" v-if="isActive">
                    <Textarea v-model="form.content" :rows="5" class="max-w-[500px]" />
                    <div class="mt-5 flex items-center lg:mt-0">
                        <span>
                            <SecondaryButton v-if="!isActive" @click="startNote">
                                <Plus class="w-5 text-gray-400" />
                                Dodaj notatkę
                            </SecondaryButton>

                            <SecondaryButton v-if="isActive" @click="cancelNote">
                                <X class="w-5 text-gray-400" />
                                Anuluj
                            </SecondaryButton>
                        </span>

                        <span>
                            <Button v-if="isActive" @click="submitNote" class="ml-2">
                                <svg class="mr-1.5 -ml-0.5 size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                                    <path
                                        fill-rule="evenodd"
                                        d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                Zapisz notatkę
                            </Button>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <Panel v-if="notes.length > 0" toggleable v-model:collapsed="isPanelCollapsed">
            <template #header>
                <div class="order-1 my-4 flex items-center gap-2">
                    <SecondaryButton v-if="!isPanelCollapsed" @click="expandNotes" variant="outlined">
                        <ArrowDownFromLine class="w-4" />
                        {{ isExpanded ? 'Zwiń treści' : 'Rozwiń treści' }}
                    </SecondaryButton>

                    <SecondaryButton v-if="notes.length > 3 && !isPanelCollapsed" @click="showAllNotes = !showAllNotes">
                        <Expand class="w-4" />
                        {{ showAllNotes ? 'Pokaż mniej' : `Pokaż wszystkie (${notes.length - 3} więcej)` }}
                    </SecondaryButton>
                </div>
            </template>
            <div class="grid grid-cols-3 gap-6">
                <Panel v-for="note in showAllNotes ? notes : notes.slice(0, 3)" :key="note.id" class="flex h-auto flex-col justify-between">
                    <template #header>
                        <div class="flex items-center gap-2 py-4">
                            <Avatar :image="note.user.avatar_url" shape="circle" />
                            <span class="font-bold">{{ note.user.name }}</span>
                        </div>
                    </template>
                    <template #footer>
                        <div class="mt-4 flex flex-wrap items-center justify-between gap-4">
                            <div class="flex items-center gap-2">
                                <DangerButton
                                    v-if="note.user_id === user.id && noteToEdit !== note.id"
                                    variant="destructive"
                                    size="small"
                                    @click="openDeleteDialog(note.id)"
                                >
                                    <Delete class="w-4" />
                                    Usuń
                                </DangerButton>

                                <Button v-if="noteToEdit === note.id" size="small" @click="saveEditedNote" variant="default"> Zapisz zmiany </Button>
                            </div>
                            <div class="flex flex-col">
                                <span v-if="note.edited_at !== null" class="text-sm text-surface-500 dark:text-surface-400"
                                    >Edytowana {{ dayjs(note.edited_at).format('DD.MM.YYYY o HH:mm') }}</span
                                >
                                <span class="text-sm text-surface-500 dark:text-surface-400"
                                    >Dodana {{ dayjs(note.created_at).format('DD.MM.YYYY o HH:mm') }}</span
                                >
                            </div>
                        </div>
                    </template>
                    <template #icons>
                        <Button v-if="!noteToEdit" size="small" @click="startEditingNote(note)">
                            <Pencil class="w-4" />
                        </Button>

                        <Button v-if="noteToEdit" size="small" @click="cancelEditingNote">
                            <X class="w-4" />
                        </Button>
                    </template>

                    <p
                        v-if="noteToEdit !== note.id"
                        :class="['overflow-wrap-anywhere !m-0 break-words hyphens-auto whitespace-pre-line', isExpanded ? '' : 'line-clamp-2']"
                    >
                        {{ note.content }}
                    </p>

                    <Textarea v-if="noteToEdit === note.id" v-model="editForm.content" class="w-full" :rows="5" />
                </Panel>
            </div>
        </Panel>

        <div v-else class="mt-4 flex flex-col gap-4 text-xl font-semibold text-gray-500">
            Brak notatek
            <Divider />
        </div>
    </div>
    <Dialog v-model:visible="showDialog" modal header="Usunąć notatkę?" class="w-9/10 sm:w-100">
        <span class="mb-8 block text-surface-500 dark:text-surface-400"
            >Notatka zostanie bezpowrotnie usunięta z bazy danych. Jesteś pewien, że chcesz ją usunąć?</span
        >

        <div class="flex justify-end gap-2">
            <SecondaryButton type="button" label="Anuluj" @click="toggleDialog" />
            <DangerButton type="button" label="Usuń notatkę" @click="deleteNote" />
        </div>
    </Dialog>
</template>
