<template lang="">
    <div class="px-5 my-6">
        <h2>Search results for "{{searchQuery}}"</h2>
        <div class="flex flex-column w-12">
            <div class="flex flex-row align-items-baseline gap-1">
                <h2>Tutors</h2>
                <p class="font-bold text-sm">({{tutors.length}} results)</p>
            </div>
            <div class="flex justify-content-start overflow-auto flex-wrap flex-row">
                <Tutors v-for="(tutor, index) in tutors"
                    :key="index"
                    :id="tutor.id"
                    :profile_picture="tutor.profile_picture"
                    :fullName = "tutor.fullName"
                    :username = "tutor.username"
                    :category = "tutor.category"
                    :followers = "tutor.followers"
                />
            </div>
        </div>
        <Divider/>
        <div class="flex flex-column w-12">
            <div class="flex flex-row align-items-baseline gap-1">
                <h2>Courses</h2>
                <p class="font-bold text-sm">({{courses.length}} results)</p>
            </div>
            <div class="flex justify-content-start overflow-auto flex-wrap flex-row gap-3">
                <Courses v-for="(course, index) in courses"
                    :key="index"
                    :id = "course.id"
                    :thumbnail =  "course.thumbnail"
                    :title = "course.title"
                    :category = "course.category"
                    :author = "course.author"
                    :info = "course.info"
                    :duration = "course.duration"
                    :enrolled = "course.enrolled"
                />
            </div>
        </div>
    </div>
</template>
<script>
import Divider from 'primevue/divider';
import Courses from '../components/search/Courses.vue';
import Tutors from '../components/search/Tutors.vue';
export default {
    components:{
        Courses,
        Tutors,
        Divider,
    },
    data(){
        return{
            searchQuery: '',
            courses: [],
            tutors: [],
        }
    },
    created(){
        this.searchQuery = this.$route.query.q || '';
    },
    watch:{
        '$route.query.q'(newQuery) {
            this.searchQuery = newQuery || '';
            this.getSearchedCourses();
            this.getSearchedTutors();
        },
    },
    methods:{
        async getSearchedCourses(){
        try{
            var response = await fetch(`http://127.0.0.1:8000/api/search?query=${this.searchQuery}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Access-Control-Allow-Origin': '*',
                },
                mode: 'cors'
            });
            if(!response.ok){
                throw new Error("Something went wrong. Try again!");
            }
            const data = await response.json();
            const courses = data.courses;
            this.courses = courses.map(course => {
                const lessons = course.number_of_lessons;
                const tags = course.course_tags;
                let info = '';

                if (!lessons || lessons.length === 0) {
                    info = tags || '';
                } else if (!tags || tags.length === 0) {
                    info = lessons;
                } else {
                    info = lessons; // Default to lessons if both are present
                }

                return {
                    id: course.course_id,
                    title: course.course_title,
                    category: course.category_name,
                    author: course.author,
                    thumbnail: course.course_thumbnail,
                    enrolled: `${course.number_of_enrollments} enrolled`,
                    lessons: lessons,
                    tags: tags,
                    info: info
                }; 
            });
        }catch(error){
            console.log('Error fetching learning data:', error);
        }
      },
      async getSearchedTutors(){
        try{
            var response = await fetch(`http://127.0.0.1:8000/api/search?query=${this.searchQuery}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Access-Control-Allow-Origin': '*',
                },
                mode: 'cors'
            });
            if(!response.ok){
                throw new Error("Something went wrong. Try again!");
            }
            const data = await response.json();
            const users = data.users;
            this.tutors = users.map(tutor => ({
                    id: tutor.id,
                    profile_picture: tutor.profile_picture,
                    fullName: tutor.first_name + ' ' + tutor.last_name,
                    username: tutor.username,
                    category: "Social media",
                    followers: "123000",
            }));
        }catch(error){
            console.log('Error fetching learning data:', error);
        }
      }
    },
    mounted(){
      this.getSearchedCourses();
      this.getSearchedTutors();
    }

}
</script>
<style lang="css">
    
</style>