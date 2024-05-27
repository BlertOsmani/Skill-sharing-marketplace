<template lang="">
    <div class="top-instructors-container mb-5 flex flex-column justify-content-center align-items-center">
        <h2>Tutors of the week</h2>
        <div class="flex flex-row justify-content-center align-items-center gap-3 overflow-auto w-12">
            <TutorsCard v-for="(tutor,index) in tutors" 
                :key="index"
                :id="tutor.id"
                :name="tutor.name"
                :category="tutor.category"
                :image="tutor.image"
                :rating="tutor.rating"
                :followers="tutor.followers"
                :username="tutor.username"
            />
        </div>
    </div>
</template>
<script>
import TutorsCard from './TutorsCard.vue';
export default {
    components:{
        TutorsCard,
    },
    data(){
        return {
            tutors:[],
        };
    },
    methods:{
        async getTopTutors(){
            try {
          const response = await fetch(`http://127.0.0.1:8000/api/user/toptutors`, {
            method: 'GET',
            headers: {
              'Content-Type': 'application/json',
              'Access-Control-Allow-Origin': '*',
            },
            mode: 'cors'
          });
          if (!response.ok) {
            throw new Error('Something went wrong fetching the courses you are enrolled in. Please refresh the page!');
          }
          const data = await response.json();
          // Assuming the response data structure matches the card data structure
          this.tutors = data.map(tutors => ({
            id: tutors.tutor_id,
            name: tutors.tutor_name,
            image: tutors.profile_picture,
            rating: tutors.average_rating,
            followers: 150000,
            username: tutors.tutor_username,
            category: "UI/UX Design"
          }));
        } catch (error) {
          console.error('Error fetching learning data:', error);
        }
        }
    },
    mounted(){
      this.getTopTutors();
    }
}   
</script>
<style lang="css">
</style>