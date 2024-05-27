<script setup>
import { ref, watch, onMounted } from 'vue';
import { useToast } from 'primevue/usetoast';
import AuthServices from '@/services/AuthServices';
import { useRouter } from 'vue-router';
const toast = useToast();

const props = defineProps({
    visible: {
        type: Boolean,
        required: true,
    },
    title: {
        type: String,
        required: true,
    },
    id: {
        type: Number,
        required: true,
    },
});
const router = useRouter();

const emit = defineEmits(['update:visible', 'select-album']);

const localVisible = ref(props.visible);
const createAlbumVisible = ref(false);
const albums = ref([]);
const selectedAlbumId = ref(null);
const defaultAlbumId = ref(1);

const getAlbums = async () => {
    if(!AuthServices.getProfile()){
        router.push('/login');
    }
    const user = await AuthServices.getProfile();
    const userId = user.data.id;
    try {
        const response = await fetch(`http://127.0.0.1:8000/api/album/get?userId=${userId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });
        if (!response.ok) {
            throw new Error('Something went wrong. Please try again!');
        }
        const data = await response.json();
        albums.value = data.albums.map((album) => ({
            id: album.id,
            title: album.title,
            description: album.favorites_count,
        }));
        setDefaultAlbum();
    } catch (error) {
        console.error('There was a problem with the fetch operation: ', error);
    }
};

const setDefaultAlbum = () => {
    const defaultAlbum = albums.value.find((album) => album.id === defaultAlbumId.value);
    if (defaultAlbum) {
        selectedAlbumId.value = defaultAlbum.id;
        emit('select-album', defaultAlbum.id);
    }
};

const updateSelectedAlbumId = (albumId) => {
    console.log('Selected album ID:', albumId); // Debug log
    selectedAlbumId.value = albumId;
};

const updateCreateAlbumVisible = (visible) => {
    createAlbumVisible.value = visible;
};

const handleNewAlbumCreated = () => {
    getAlbums(); // Refresh the album list after a new album is created
};

async function saveCourse() {
    if (!selectedAlbumId.value) {
        console.error('No album selected');
        return;
    }
    try {
        const response = await fetch('http://127.0.0.1:8000/api/course/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                courseId: props.id,
                albumId: selectedAlbumId.value,
            }),
        });
        if (!response.ok) {
            throw new Error('Something went wrong. Please try again!');
        }
        const data = await response.json();
        toast.add({ severity: 'success', summary: 'Success', detail: data.message, life: 4000 });
        closeDialog();
    } catch (error) {
        console.error('There was a problem with the fetch operation: ', error);
    }
};

const closeDialog = () => {
    localVisible.value = false;
};

const showCreateAlbumDialog = () => {
    createAlbumVisible.value = true;
};

watch(() => props.visible, (newVal) => {
    localVisible.value = newVal;
    if (localVisible.value) {
        getAlbums();
    }
});

watch(localVisible, (newVal) => {
    emit('update:visible', newVal);
});

onMounted(() => {
    if (localVisible.value) {
        getAlbums();
    }
});
</script>

<template>
    <Toast/>
    <Dialog v-model:visible="localVisible" modal header="Save this course to an album" class="w-4">
        <form @submit.prevent="saveCourse">
            <p class="hidden">{{ id }}</p>
            <span class="text-900 text-base block mb-5">{{ title }}</span>
            <div class="flex flex-column align-items-center gap-2 mb-3 w-12">
                <DialogAlbumCard
                    :albums="albums"
                    :defaultAlbumId="defaultAlbumId"
                    @update:selectedAlbumId="updateSelectedAlbumId"
                />
            </div>
            <div class="flex justify-content-between gap-2 flex-wrap">
                <div>
                    <Button type="button" label="Create new album" severity="secondary" outlined @click="showCreateAlbumDialog"></Button>
                    <CreateAlbumDialog
                        :visible="createAlbumVisible"
                        @update:visible="updateCreateAlbumVisible"
                        @albumCreated="handleNewAlbumCreated"
                    />
                </div>
                <Button type="submit" label="Save"></Button>
            </div>
        </form>
    </Dialog>
</template>
<script>
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import CreateAlbumDialog from './CreateAlbumDialog.vue';
import DialogAlbumCard from './DialogAlbumCard.vue';
import Toast from 'primevue/toast';
export default {
    components:{
        Button,
        Dialog,
        CreateAlbumDialog,
        DialogAlbumCard,
        Toast,
    }
}
</script>

<style lang="css">
.select-dialog-button .p-button {
    width: 100%;
}
.p-button-group .p-button {
    border-radius: 0.375rem !important;
}
</style>
