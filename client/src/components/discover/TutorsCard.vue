<template lang="">
        <div class="instructor-card surface-card justify-content-center flex flex-column align-items-center border-100 border-solid border-1 border-round-md">
            <p class="hidden">{{id}}</p>
            <Link :to="`/@${username}`" class="w-12 bg-none m-0 flex flex-column align-items-center">
                <Avatar :image="image" class="m-0 instructor-avatar" size="xlarge" />
                <div>
                    <div class="flex flex-column align-items-center m-3">
                        <p class="name m-0 text-md text-white">{{ name }}</p>
                        <p class="category my-1 text-xs text-400">{{ category }}</p>
                    </div>
                </div>
            </Link>
            <div class="flex justify-content-center align-items-center">
                <p class="followers text-xs text-400">{{formattedFollowers}} followers</p>
                <Dot/>
                <p class="rating text-xs text-400">{{rating}} <i class="text-xs pi pi-star-fill text-yellow-300"></i></p>
            </div>
            <div class="flex justify-content-center">
                <Button label="Follow" severity="secondary" class="follow-button"></Button>
            </div>
        </div>
</template>
<script>
import Avatar from 'primevue/avatar';
import Button from 'primevue/button';
import Dot from '../Dot.vue';
import Link from '../Link.vue';
export default {
    components:{
        Avatar,
        Button,
        Dot,
        Link,
    },
    props:{
        id:Number,
        image: String,
        name: String,
        category:String,
        rating: Number,
        followers:Number,
        username:String,
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
 .instructor-card{
    width:250px;
    height:280px;
 }
 .follow-button{
    width: 10rem;
 }
</style>