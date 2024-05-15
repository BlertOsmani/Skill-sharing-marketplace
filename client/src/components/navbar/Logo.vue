<template lang="">
    <div class="flex flex-row justify-content-center align-items-center gap-1">
        <img :src="logoUrl" alt="Image" width="40" height="40"/>
        <div class="flex flex-column justify-content-center">
                <h4 class="brandName text-primary">Skill</h4>
                <h4 class="brandName text-primary">Sphere</h4>
        </div>
    </div>
</template>
<script>
import logo from '../../assets/images/skillsphere-logo.png';
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