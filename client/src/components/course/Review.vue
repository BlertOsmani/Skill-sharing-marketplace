<template>
    <div class="w-12 flex flex-column justify-content-center align-items-center">
        <div class="w-8 flex flex-column">
            <h2 class="mb-5">Course Rating</h2>
            <div v-for="review in reviews" :key="review.user_id" class="mb-3">
                <Fieldset>
                    <template #legend>
                        <div class="flex align-items-center pl-2">
                            <Avatar :image="authorAvatar" shape="circle" />
                            <span class="font-bold">{{ review.user }}</span>
                        </div>
                    </template>
                    <div class="flex flex-row justify-content-between">
                        <div>
                            <p class="m-0">{{ review.review_text }}</p>
                        </div>
                        <div>
                            <div>
                                <span class="text-base mr-1">{{ review.rating }}</span>
                                <i class="pi pi-star text-base mb-2"></i>
                            </div>
                        </div>
                    </div>
                </Fieldset>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Fieldset from 'primevue/fieldset';
import Avatar from 'primevue/avatar';

export default {
    name: 'Review',
    components: {
        Fieldset,
        Avatar,
    },
    data() {
        return {
            reviews: [],
            authorAvatar: 'https://www.gravatar.com/avatar/placeholder?d=mp',
        };
    },
    mounted() {
        this.fetchCourseDetails();
    },
    methods: {
        async fetchCourseDetails() {
            var course_id = this.$route.query.course_id
            try {
                const response = await axios.get(`http://127.0.0.1:8000/api/course/details?course_id=${course_id}`, {
                    
                });
                this.reviews = response.data.reviews;
            } catch (error) {
                console.error('Error fetching course details:', error);
            }
        },
    },
};
</script>

<style>
/* Add any necessary styles here */
</style>
