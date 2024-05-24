<template lang="">
    <div class="px-5 my-6">
        <h2>Saved</h2>
        <div class="flex justify-content-start overflow-auto flex-wrap flex-row gap-3">
            <Albums v-for="(album, index) in albums"
                :key="index"
                :name="album.name"  
                :numberOfCourses="album.numberOfCourses"  
            />
        </div>
    </div>
</template>
<script>
import Albums from '../components/saved/Albums.vue';
export default {
    components:{
        Albums,
    },
    data(){
        return {
            albums:[
                {
                    name: "All",
                    numberOfCourses: 12
                },
                {
                    name: "Photography",
                    numberOfCourses: 110
                },
                {
                    name: "UI/UX",
                    numberOfCourses: 58
                },
                {
                    name: "Cooking",
                    numberOfCourses: 9
                },
                {
                    name: "Physics",
                    numberOfCourses: 21
                }
            ]
        };
    }
}
</script>
<style lang="">
    
</style>