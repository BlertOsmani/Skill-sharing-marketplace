<template lang="">
    <router-link :to="to" exact>
        <Button :label="label" :icon="icon" text :severity="severity" :class="computeClass()" :badge="badge" :badgeSeverity="badgeSeverity" :iconPos="iconPos">
            <slot></slot>
        </Button>
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
        type:{
            type:String, 
            required:false,
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
        },
        severity:{
            type:String,
            required:false,
        },
        highlight: {
            type: Boolean,
            default: true,
        }
    },
    methods: {
        computeClass() {
            return [
                this.class,
                this.highlight && this.$route.path === this.to ? 'active-link' : ''
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
a{
    text-decoration:none;
}
</style>