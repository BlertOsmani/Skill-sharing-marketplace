<script setup>

</script>
<template lang="">
     <div class="register-with-google-button-container">
        <Button outlined severity="secondary" icon="pi pi-google" label="Sign up with Google"></Button>
    </div>
</template>

<script>
import Button from 'primevue/button';
export default {
    components:{
        Button,
    }
}
</script>
<style lang="scss">
    .register-with-google-button-container{
        width: 100%;
        display:flex;
        justify-content:center;
        margin: 0.5rem 0;
    }
    .register-with-google-button-container Button{
        width:60%;
        padding:12px;
    }
    @media screen and (max-width: 1200px) {
        .register-with-google-button-container Button{
            width:80%;
        }
    }
    @media screen and (max-width: 576px) {
        .register-with-google-button-container Button{
            width:100%;
        }
    }
</style>