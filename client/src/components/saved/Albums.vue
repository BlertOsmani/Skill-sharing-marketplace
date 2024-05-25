<template lang="">
    <div class="flex border-round-md w-custom-card mb-4">
        <Link :to="{ path: '/saved/album', query: { name: title, id: id } }" class="bg-none flex flex-column align-items-start p-0">
            <p class="hidden">{{id}}</p>
            <div class="border-50 border-1 border-round-md border-solid flex flex-column relative">
                <img :src="image" class="border-round-md w-12" alt="">
            </div>
            <div>
                <p class="text-base text-900 text-semibold">{{title}} <span class="text-sm text-400">({{numberOfCourses}} saved)</span></p>
            </div>
        </Link>
    </div>
</template>
<script>
import uiUxCourse from '../../assets/images/ui-ux-course-3.jpeg';
import Link from '../Link.vue';
export default {
    components:{
        Link,
    },
    data(){
        return{
            image: uiUxCourse
        };
    },
    props:{
        id: Number,
        title: String,
        userId: Number,
        numberOfCourses:Number,

    },
}
</script>
<style lang="css">
.album-image{
    width:90%;
}
</style>