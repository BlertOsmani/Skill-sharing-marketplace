<script setup>
import { ref, watch } from "vue";
import { useToast } from 'primevue/usetoast';
import AuthServices from "@/services/AuthServices";

const toast = useToast();
const albumTitle = ref('');
const localVisible = ref(false);

const props = defineProps({
    visible: {
        type: Boolean,
        required: true
    }
});

const emit = defineEmits(['update:visible', 'albumCreated']);

async function createAlbum() {
    const user = await AuthServices.getProfile();
    const userId = user.data.id;
    try {
        const response = await fetch("http://127.0.0.1:8000/api/album/create", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Access-Control-Allow-Origin': '*'
            },
            mode: 'cors',
            body: JSON.stringify({
                userId: userId,
                title: albumTitle.value
            })
        });
        if (!response.ok) {
            throw new Error('Something went wrong. Please try again!');
        }
        const data = await response.json();
        toast.add({ severity: 'success', summary: 'Success', detail: data.message, life: 4000 });
        closeDialog();
        emit('albumCreated', data.album); // Emit event with the newly created album data
    } catch (error) {
        toast.add({ severity: 'error', summary: 'Error', detail: error.message, life: 4000 });
        console.error("There was a problem with the fetch operation: ", error);
    }
}

const closeDialog = () => {
    localVisible.value = false;
};

watch(() => props.visible, (newVal) => {
    localVisible.value = newVal;
});

watch(localVisible, (newVal) => {
    emit('update:visible', newVal);
});
</script>

<template>
    <div>
        <Toast/>
        <Dialog v-model:visible="localVisible" modal header="Create new album" class="w-3 flex flex-column gap-3">
            <form @submit.prevent="createAlbum">
                <div class="flex flex-column gap-2">
                    <label for="albumName">Name</label>
                    <InputText id="albumName" class="w-12 album-name-input" v-model="albumTitle"/>
                </div>
                <div class="flex justify-content-between gap-2 mt-5">
                    <Button type="button" label="Cancel" severity="secondary" outlined @click="closeDialog"></Button>
                    <Button type="submit" label="Create album"></Button>
                </div>
            </form>
        </Dialog>
    </div>
</template>

<script>
import Toast from 'primevue/toast';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Dialog from 'primevue/dialog';

export default {
    components: {
        Dialog,
        Button,
        InputText,
        Toast,
    }
};
</script>

<style scoped>
/* Add any component-specific styles here */
</style>
