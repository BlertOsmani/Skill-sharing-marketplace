<template lang="">
    <div>
        <IconField iconPosition="left">
                <InputIcon class="pi pi-search"></InputIcon>
                <InputText v-model="data" placeholder="Search for courses, skills, channels" class="w-7 h-2point5rem border-round-md"/>
        </IconField>
    </div>
</template>
<script>
import InputText from 'primevue/inputtext';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
export default {
    components:{
        InputIcon,
        InputText,
        IconField,
    },
}
</script>
<style lang="">
    
</style>