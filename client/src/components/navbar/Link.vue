<template lang="">
    <router-link :to="to">
        <Button :label="label" :icon="icon" text severity="secondary" :class="class" :badge="badge" :iconPos="iconPos"></Button>
    </router-link>
</template>
<script>
import Button from 'primevue/button';
export default {
    name: 'Link',
    components:{
        Button
    },
    props:{
        to:{
            type:String,
            required: true,
        },
        label:{
            type: String,
            required: false,
        },
        icon:{
            type:String,
            required:false,
        },
        class:{
            type:String,
            required:false,
        },
        badge:{
            type:String,
            required:false,
        },
        iconPos:{
            type:String,
            required:false,
        },
    }
}
</script>
<style lang="css">
    
</style>