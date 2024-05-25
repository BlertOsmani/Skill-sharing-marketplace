<template>
    <SelectButton 
        v-model="selectedAlbumId" 
        :options="albums" 
        optionLabel="title" 
        dataKey="id" 
        aria-labelledby="custom"
        class="flex flex-column gap-1 w-12"
    >
        <template #option="slotProps">
            <div class="flex flex-row border-round-md z-1 w-12">
                <p class="hidden">{{ slotProps.option.id }}</p>
                <div class="flex flex-column my-2 align-items-start w-12">
                    <p class="p-0 m-0 mx-0 text-md text-semibold text-900">{{ slotProps.option.title }}</p>
                    <p class="p-0 m-0 text-semibold text-400 text-xs">{{ slotProps.option.description }} saved</p>
                </div>
            </div>
        </template>
    </SelectButton>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import SelectButton from 'primevue/selectbutton';

const props = defineProps({
    albums: {
        type: Array,
        required: true
    },
    defaultAlbumId: {
        type: Number,
        required: false
    }
});
const emit = defineEmits(['update:selectedAlbumId']);

const selectedAlbumId = ref(null);

onMounted(() => {
    if (props.defaultAlbumId) {
        selectedAlbumId.value = props.defaultAlbumId;
    }
});

watch(selectedAlbumId, (newVal) => {// Debug log
    emit('update:selectedAlbumId', newVal.id);
});
</script>

<style scoped>
.select-dialog-button .p-button {
    width: 100%;
}
.p-button-group .p-button {
    border-radius: 0.375rem !important;
}
</style>
