<template lang="">
    <div>
        <form @submit.prevent="submitSearch" method="POST">
            <IconField iconPosition="left">
                    <InputIcon class="pi pi-search"></InputIcon>
                    <InputText v-model="query" placeholder="Search for courses, categories, tutors" class="w-7 h-2point5rem border-round-md"/>
            </IconField>
        </form>
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
    data(){
        return {
            query: '',
        };
    },
    methods:{
        submitSearch(){
            if(this.query.trim()){
                this.$router.push({path: '/search', query:{q:this.query}});
            }
        }
    }
}
</script>
<style lang="">
    
</style>