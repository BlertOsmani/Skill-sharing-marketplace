<template lang="">
    <div>
        <Courses
            :id = "id"
            :thumbnail =  "thumbnail"
            :title = "title"
            :category = "category"
            :author = "author"
            :info = "info"
            :duration = "duration"
            :enrolled = "enrolled"
        />
    </div>
</template>
<script>
import Courses from '../Courses.vue';
export default {
    components:{
        Courses,
    },
    props:{
        id: Number,
        thumbnail: String,
        title:String,
        category: String,
        author: String,
        info:String,
        duration: String,
        enrolled: String,
    },
}
</script>
<style lang="">
    
</style>