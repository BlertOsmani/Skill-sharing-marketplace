<template>
    <div class="relative">
        <Button 
            severity="secondary" 
            text 
            class="p-1 px-3 border-round-md" 
            @click="toggleMenu" 
            :class="{'hovered-link': !hidden}"
        >
            <Avatar 
                :image="image" 
                :label="first_letter" 
                class="mr-2" 
                style="background-color:var(--indigo-400); color: #2a1261" 
                shape="circle" 
            />
            <p class="username">{{username}}</p>
            <i class="pi pi-angle-down ml-1"></i>
        </Button>
        <Dropdown 
            v-if="!hidden" 
            :dropdownItems="profileDropdownItems"
            class="flex flex-row w-14 right-0"
        />
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, defineEmits } from 'vue';
import {useRouter} from 'vue-router';
import AuthServices from '@/services/AuthServices';
import Avatar from 'primevue/avatar';
import Button from 'primevue/button';
import Dropdown from '../Dropdown.vue';

const emit = defineEmits(['logout']);
const router = useRouter();

const hidden = ref(true);
const username = ref(null);
const first_letter = ref(null);
const image = ref(null);

const toggleMenu = () => {
    hidden.value = !hidden.value;
};

const handleClickOutside = (event) => {
    if (!event.target.closest('.relative')) {
        hidden.value = true;
    }
};

onMounted(async () => {
    const user = await AuthServices.getProfile();
    username.value = user.data.username;
    first_letter.value = user.data.first_name.charAt(0);
    image.value = user.data.profile_picture; // Assuming you have a profile picture URL
// Assuming you have a profile picture URL
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});

const logout = async () => {
    await AuthServices.logout();
    toggleMenu();
    window.location.reload();   // Add any additional logout handling logic here
};

const profileDropdownItems = ref([
    {
        width: "120%",
        groups: [
            {
                title: 'Learning',
                items: [
                    { to: '/mycourses', label: 'My courses', icon: 'pi pi-graduation-cap', badge: '' },
                    { to: '/saved', label: 'Saved', icon: 'pi pi-bookmark', badge: '' },
                ],
            },
            {
                title: 'Profile',
                items: [
                    { to: '/messages', label: 'Messages', icon: 'pi pi-comments', badge: '2', badgeSeverity: 'contrast' },
                    { to: '/settings', label: 'Account', icon: 'pi pi-user', badge: '' },
                    { to: '/settings', label: 'Settings', icon: 'pi pi-cog', badge: '' },
                    { clickHandler: logout, label: 'Logout', icon: 'pi pi-sign-out', badge: '' },
                ],
            },
        ],
    }
]);
</script>

<style lang="css">
.username, i {
    font-size: 14px;
    color: #a1a1aa;
}
.hovered-link {
    background-color: var(--surface-hover);
}
</style>
