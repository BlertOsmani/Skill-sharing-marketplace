<template lang="">
    <div class="flex flex-row justify-content-center align-items-center gap-1">
        <img :src="logoUrl" alt="Image" width="60" height="60"/>
        <div class="flex flex-column justify-content-center">
                <h3 class="brandName text-primary">Skill</h3>
                <h3 class="brandName text-primary">Sphere</h3>
        </div>
    </div>
</template>
<script>
import logo from '../../assets/images/skill-sphere-logo.png';
export default {
    data(){
        return{
            logoUrl : logo
        }
    }
}
</script>
<style lang="css">
@font-face {
    font-family: 'Futura';
    src: url('fonts/Futura.ttf') format('truetype');
    font-weight: normal;
    font-style: normal;
}
.brandName{
   text-transform: uppercase;
   letter-spacing:2px;
   margin:0;
   font-family: 'Futura', sans-serif;
}
</style>