<template lang="">
    <div class="relative">
        <Button severity="secondary" text class="p-1 px-3" @click="toggleMenu" :class="{'hovered-link': !hidden}">
            <Avatar :image="image" label="B" class="mr-2" style="background-color:var(--indigo-400); color: #2a1261" shape="circle" />
            <p class="username">blert_osmani</p>
            <i class="pi pi-angle-down ml-1"></i>
        </Button>
        <Dropdown class="flex flex-row w-14 right-0" v-if="!hidden" badge="2" :dropdownItems="profileDropdownItems"/>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";

const hidden = ref(true);

const toggleMenu = () => {
    hidden.value = !hidden.value;
};
const handleClickOutside = (event) => {
    if (!event.target.closest('.relative')) {
        hidden.value = true;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>

<script>
import Avatar from 'primevue/avatar';
import Button from 'primevue/button';
import Dropdown from '../Dropdown.vue';
export default {
    components:{
        Avatar,
        Button,
        Dropdown,
    },
    props:{
        label: {
            type:String,
            required: false,
        },
        image:{
            type: String,
            required:false,
        }
    },
    setup() {
        return {
            hidden,
            toggleMenu
        };
    },
    data() {
        return {
            profileDropdownItems: [
                { // Width of the first column
                    width:"100%",
                    groups: [
                        {
                            title: 'Learning',
                            items: [
                                { to: '/mycourses', label: 'My courses', icon: 'pi pi-graduation-cap', badge: '' },
                                { to: '/favorites', label: 'Favorites', icon: 'pi pi-heart', badge: '' },
                            ],
                        },
                        {
                            title: 'Profile',
                            items: [
                                { to: '/messages', label: 'Messages', icon: 'pi pi-comments', badge: '2' },
                                { to: '/settings', label: 'Account', icon: 'pi pi-user', badge: '' },
                                { to: '/settings', label: 'Settings', icon: 'pi pi-cog', badge: '' },
                            ],
                        },
                    ],
                },
            // Add more columns as needed
        ],
        };
    },
}
</script>

<style lang="css">
    .username, i{
        font-size:14px;
        color:#a1a1aa;
    }
    .hovered-link{
        background-color: var(--surface-hover);
    }
</style>