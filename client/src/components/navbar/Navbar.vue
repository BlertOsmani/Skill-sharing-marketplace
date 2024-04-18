<template lang="">
    <div class="surface-card flex flex-row justify-content-between align-items-center p-2 gap-1">
        <div class="logo w-1 flex flex-row align-items-center">
            <Logo/>
        </div>
        <div class="links flex flex-row align-items-center w-7 justify-content-start mx-5">
            <Links/>
        </div>
        <div class="links flex flex-row align-items-center justify-content-end w-4 relative">
            <Button severity="primary" label="Create course"></Button>
        </div>
        <div class="w-auto flex justify-content-end">
            <Profile/>
        </div>
    </div>
</template>
<script>
import Links from './Links.vue';
import Button from 'primevue/button';
import Logo from './Logo.vue';
import Profile from './Profile.vue';
export default {
    components:{
        Logo,
        Button,
        Links,
        Profile,
    }
}
</script>
<style lang="css">
</style>