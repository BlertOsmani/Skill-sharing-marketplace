<template lang="">
    <div class="w-custom-tutors-card mb-4">
        <Link :to="`/@${username}`" class="flex flex-column bg-none w-12 align-items-center">
            <div class="flex flex-column align-items-center w-12">
                <p class="hidden">{{id}}</p>
                <Avatar class="w-6 h-full" :image="profile_picture" alt=""/>
                <div class="flex w-12 flex-column">
                    <p class="font-semibold text-900 text-sm mb-0">{{fullName}}</p>
                    <div class="flex align-items-center justify-content-center mt-1 mb-3">
                        <p class="font-semibold text-400 text-xs m-0 mb-2">{{formattedFollowers}} followers</p>
                    </div>
                </div>
                <div class="flex w-12 flex-column justify-content-center align-items-center">
                    <Button severity="secondary" label="Follow" class="w-8"></Button>
                </div>
            </div>
        </Link>
    </div>
</template>
<script>
import Avatar from 'primevue/avatar';
import Button from 'primevue/button';
import Link from '../Link.vue';
import Dot from '../Dot.vue';
export default {
    components:{
        Link,
        Avatar,
        Button,
        Dot,
    },
    props:{
        id: Number,
        profile_picture: String,
        fullName: String,
        followers: String,
        category: String,
        username: String,
    },
    computed: {
        formattedFollowers() {
            if (this.followers >= 1000 && this.followers < 1000000) {
            const formatted = (this.followers / 1000).toFixed(1);
            return formatted.endsWith('.0') ? formatted.slice(0, -2) + 'k' : formatted + 'k';
            } else if (this.followers >= 1000000) {
                const formatted = (this.followers / 1000000).toFixed(1);
                return formatted.endsWith('.0') ? formatted.slice(0, -2) + 'M' : formatted + 'M';
            } else {
                return this.followers.toString();
            }
        },
    },
}
</script>
<style lang="css">
.w-custom-tutors-card{
    width:calc(16.6667%);
    max-width:calc(16.6667%);
}

@media (max-width: 1200px) {
    .w-custom-tutors-card {
      width:calc(25%); 
      max-width: calc(25%);/* Ensure max-width of cards */
    }
}
@media (max-width: 768px) {
    .w-custom-tutors-card {
      width:calc(33.33%);
      max-width: calc(33.33%);/* Ensure max-width of cards */
    }
}
@media (max-width: 576px) {
    .w-custom-tutors-card {
        width:calc(50%);
        max-width: calc(50%);;/* Ensure max-width of cards */
    }
}
</style>