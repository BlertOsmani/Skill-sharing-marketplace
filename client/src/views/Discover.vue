<template lang="">
    <div class="my-6 px-5">
        <ContinueLearning/>
        <EnrolledCourses/>
        <TopTutors/>
        <FeaturedCourses/>
        <RecommendedCourses/>
    </div>
</template>
<script>
import Navbar from '../components/navbar/Navbar.vue';
import ContinueLearning from '../components/discover/ContinueLearning.vue';
import EnrolledCourses from '../components/discover/EnrolledCourses.vue';
import TopTutors from '../components/discover/TopTutors.vue';
import RecommendedCourses from '../components/discover/RecommendedCourses.vue';
import FeaturedCourses from '../components/discover/FeaturedCourses.vue';
export default {
    name: 'Discover',
    components:{
        Navbar,
        ContinueLearning,
        EnrolledCourses,
        TopTutors,
        RecommendedCourses,
        FeaturedCourses,
    }
}
</script>
<style lang="">
    
</style>