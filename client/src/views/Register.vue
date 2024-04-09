<template lang="">
    <div class="register-form-main-container">
        <div class="register-form-container">
            <div class="background-image-container">
                <h1>Welcome to SkillSphere</h1>
                <p>Elevate your skills, elevate your life</p>
            </div>
            <div>
                <h2 class="create-your-account-text">Sign Up</h2>
                <RegisterForm/>
                <span style="margin:1.2rem 0 2rem 0;">Already have an account? <Button style="padding:0;" label="Sign in" link></Button></span>
                <h4 class="orRegisterOption">or</h4>
                <RegisterWithGoogle/>
            </div>  
        </div>
    </div>
</template>
<script>
import RegisterForm from '../components/register/RegisterForm.vue';
import RegisterWithGoogle from '../components/register/RegisterWithGoogle.vue';
import Button from 'primevue/button';

export default {
    components:{
        RegisterForm,
        Button,
        RegisterWithGoogle,
    },
};
</script>
<style lang="scss">
    .register-form-main-container{
        padding:2rem 5rem;
    }
    input, button, label, span{
        font-size:14px;
    }
    p{
        font-size:18px;
    }
    .orRegisterOption {
        line-height: 0px;
        width: 50%;
        display: flex;
        justify-content: center;
        position: relative;
    }
    .orRegisterOption::before,
    .orRegisterOption::after {
        content: "";
        flex-grow: 1;
        height: 0.5px;
        background-color: white;
    }
    .orRegisterOption::after{
        margin-left:10px;
    }
    .orRegisterOption::before{
        margin-right: 10px;
    }
    .register-form-container{
        display: flex;
        width:100%;
        border-radius:8px;
        background-color: var(--surface-card);
        height:100%;
        justify-content:space-between;
    }
    .register-form-container > div:last-child{
        flex:1;
        display: flex;
        flex-direction: column;
        align-items:center;
        padding:2rem;
    }   
    .register-form-container .background-image-container{
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: center;
        align-items: center;
        background-image: url('../assets/images/register-background-image.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        filter: contrast(120%) brightness(90%);
        border-radius: 8px 0 0 8px;
        position: relative;
    }
    .background-image-container::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.3); /* Black transparent curtain */
        z-index: 1; /* Ensure the curtain is behind the text */
    }
    .register-form-container > div:last-child > span{
        margin-top:1rem;
    }
    .background-image-container h1,
    .background-image-container p {
        margin: 1rem;
        z-index:2;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3); /* Add shadow to the text */
    }
    @media screen and (max-width: 992px) {
        .register-form-container {
            flex-direction: column; /* Stack elements vertically */
            align-items: center; /* Center items horizontally */
            gap: 1rem;
            padding:1rem 1rem; /* Reduce the gap between items */
        }
        .register-form-main-container{
            padding:2rem 9rem;
        }
        .register-form-container > div:first-child{
            display: none;
        }

    }
    @media screen and (max-width: 1200px) {
        .orRegisterOption {
            width:70%;
        }
    }
    @media screen and (max-width: 768px) {
        .register-form-main-container {
            padding:2rem 3rem;
        }
        .register-form-container{
            padding:1rem;
        }
    }
    @media screen and (max-width: 576px) {
        .register-form-main-container {
            padding:2rem 1rem;
        }
        .orRegisterOption{
            width:90%;
        }
    }
</style>