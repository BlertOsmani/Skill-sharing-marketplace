<script setup>
import { ref } from "vue";
const firstName = ref();
const lastName = ref();
const email = ref();
const username = ref();
const rememberMe = ref(false);
const password = ref();
const confirmPassword = ref();
var firstNameValid = ref(true);
var lastNameValid = ref(true);
var emailValid = ref(true);
var usernameValid = ref(true);
var passwordValid = ref(true);
var confirmPasswordValid = ref(true);

async function handleSubmit(){
    if(validateForm()){
        try {
            const response = await fetch("127.0.0.1:8000/api/user/create", {
                method: 'POST',
                headers:{
                    'Content-Type': 'application/json',
                    'Access-Control-Allow-Origin': '*'
                },
                mode: 'cors',
                body: JSON.stringify({
                    firstName: firstName.value,
                    lastName: lastName.value,
                    email: email.value,
                    username: username.value,
                    password: password.value,
                    confirmPassword: confirmPassword.value
                })
            });
            if(!response.ok){
                throw new Error('Something went wrong. Please try again!');
            }
            else{
                const responseData = await response.json();
                console.log("Response from the server: ", responseData);
            }


        } catch (error) {
            console.log("There was a problem with the fetch operation: ", error);
        }
    }
}
function validateEmail(email){
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
}
function validateUsername(username){
        const regex = /^[a-zA-Z0-9_-]+$/;
        return regex.test(username) && !!username;
}
function validateForm(){
            firstNameValid.value = firstName.value;
            lastNameValid.value = lastName.value;
            emailValid.value = validateEmail(email.value);
            usernameValid.value = validateUsername(username.value);
            passwordValid.value = !!password.value;
            confirmPasswordValid.value = password.value == confirmPassword.value;

            return(
                firstNameValid.value &&
                lastNameValid.value &&
                emailValid.value &&
                usernameValid.value &&
                passwordValid.value &&
                confirmPasswordValid.value 
            );
};

</script>

<template lang="">
    <form class="register-form" @submit.prevent="handleSubmit">
        <div class="register-form-fields-container">
            <div class="register-form-first-last-name-container">
                <InputText v-model="firstName" type="text" placeholder="First name" />
                <span v-if="!firstNameValid" class="error-message">First name is required.</span>
            </div>
            <div class="register-form-first-last-name-container">
                <InputText v-model="lastName" type="text" placeholder="Last name"/>
                <span v-if="!lastNameValid" class="error-message">Last name is required.</span>
            </div>
        </div>
        <div class="register-form-fields-container">
            <InputText v-model="email" type="email" placeholder="Email address"/>
            <span v-if="!emailValid" class="error-message">Please enter a valid email.</span>
        </div>
        <div class="register-form-fields-container">
            <InputText v-model="username" type="text" placeholder="Username"/>
            <span v-if="!usernameValid" class="error-message">Username is required and can contain letters, numbers, -, _ only.</span>
        </div>
        <div class="register-form-fields-container">
            <Password v-model="password" toggleMask placeholder="Password"/>
            <span v-if="!passwordValid" class="error-message">Password is required.</span>
        </div>
        <div class="register-form-fields-container">
            <Password v-model="confirmPassword" toggleMask placeholder="Confirm password"/>
            <span v-if="!confirmPasswordValid" class="error-message">Passwords do not match.</span>
        </div>
        <div class="register-form-submit-button-container">
            <Button class="register-submit-button" label="Sign up" @click="handleSubmit"></Button>
        </div>
    </form>
</template>

<script>
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import Checkbox from 'primevue/checkbox';
export default {
    components:{
        Button,
        InputText,
        Password,
        Checkbox
    },
    setup(){
        return {handleSubmit};
    },
}
</script>


<style lang="scss">
    .error-message{
        font-size:12px;
        color: var(--red-400);
        margin-left:5px;
    }
    .register-form{
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 2rem;
        padding:1rem 0 0 0;
    }
    .register-form-fields-container{
        display: flex;
        flex-direction: column;
        gap:5px;
        width: 60%;
    }
    .register-form-fields-container:first-child{
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }
    .register-form-first-last-name-container{
        width: 50%;
    }
    .register-form-submit-button-container{
        width:60%;
    }
    .register-form-submit-button-container button{
        width:100%;
        padding:12px;
    }
    .register-form-fields-container input{
        padding:12px;
        width:100%;
    }
    @media screen and (max-width: 1200px) {
        .register-form-fields-container {
            width:80%;
        }
        .register-form-submit-button-container{
            width:80%;
        }
    }
    @media screen and (max-width: 576px) {
        .register-form-fields-container {
            width:100%;
        }
        .register-form-submit-button-container{
            width:100%;
        }
    }
</style>

