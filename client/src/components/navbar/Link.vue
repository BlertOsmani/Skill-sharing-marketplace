<template lang="">
    <router-link :to="to" exact>
        <Button :label="label" :icon="icon" text severity="secondary" :class="computeClass()" :badge="badge" :badgeSeverity="badgeSeverity" :iconPos="iconPos"></Button>
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
        badgeSeverity:{
            type:String,
            required:false,
        }
    },
    methods: {
        computeClass() {
            return [
                this.class,
                this.$route.path === this.to ? 'active-link' : ''
            ];
        }
    }
}
</script>
<style lang="css">
.active-link {
    /* Add your styles to highlight the active link */
    /* For example: */
   background-color: var(--surface-hover);
   color: var(--primary-color);
}
</style>