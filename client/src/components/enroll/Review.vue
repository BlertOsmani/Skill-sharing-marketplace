<template>
    <div class="comment-form surface-card border-round-md w-10">
        <form @submit.prevent="submitForm">
            <div class="pt-3 px-3">
                <div>
                    <Textarea v-model="form.comment" placeholder="Leave a comment" autoResize rows="1" cols="30" />
                </div>
                <div class="py-3 pb-0 flex-row flex justify-content-between align-items-center">
                    <div class="flex-row flex gap-3">
                        <span>Rate this course: </span>
                        <Rating class="p-0" v-model="form.rating" :cancel="false" />
                    </div>
                    <div>
                        <Button label="Submit" type="submit"></Button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="w-12 flex flex-column justify-content-center align-items-center pt-3">
        <div class="w-10 flex flex-column">
            <div v-for="(review, index) in reviews" :key="index" class="mb-3">
                <Fieldset>
                    <template #legend>
                        <div class="flex align-items-center pl-2">
                            <Avatar :image="authorAvatar" shape="circle" />
                            <span class="font-bold">{{ authorName }}</span>
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
import Button from 'primevue/button';
import AuthServices from '@/services/AuthServices';
import Rating from 'primevue/rating';
import Textarea from 'primevue/textarea';

export default {
    name: 'Review',
    components: {
        Fieldset,
        Avatar,
        Button,
        Rating,
        Textarea
    },
    data() {
        return {
            reviews: [],
            authorAvatar: 'https://www.gravatar.com/avatar/placeholder?d=mp',
            authorName: '',
            form: {
                comment: '',
                rating: null,
                user_id: null,
                course_id: this.$route.query.courseId
            }
        };
    },
    async mounted() {
        await this.fetchUserProfile();
        this.fetchCourseDetails();
    },
    methods: {
        async fetchUserProfile() {
            try {
                const user = await AuthServices.getProfile();
                if (user && user.data) {
                    this.form.user_id = user.data.id;
                    this.authorName = user.data.username || 'Anonymous';
                    this.authorAvatar = user.data.profile_picture|| 'https://www.gravatar.com/avatar/placeholder?d=mp';
                } else {
                    console.error('User data is not available');
                }
            } catch (error) {
                console.error('Error fetching user profile:', error);
            }
        },
        async fetchCourseDetails() {
            const courseId = this.$route.query.courseId || this.form.course_id;
            try {
                const response = await axios.get(`http://127.0.0.1:8000/api/reviews/${courseId}`);
                const data = response.data;
                this.reviews = data.reviews;
            } catch (error) {
                console.error('Error fetching course details:', error);
            }
        },
        async submitForm() {
            try {
                const response = await axios.post('http://localhost:8000/api/reviews', this.form);
                console.log('Form submitted with:', response.data);
                const newReview = {
                    review_text: this.form.comment,
                    rating: this.form.rating
                };
                this.reviews.unshift(newReview);
                this.form.comment = '';
                this.form.rating = null;
            } catch (error) {
                console.error('Error submitting form:', error);
            }
        }
    }
};
</script>

<style scoped>
.comment-form {
    margin: 0 auto;
}

.comment-form label {
    display: block;
    margin-bottom: 5px;
}

.comment-form input,
.comment-form textarea,
.comment-form select {
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
}

.comment-form button {
    padding: 10px 20px;
    background-color: #42b983;
    color: white;
    border: none;
    cursor: pointer;
}

.comment-form button:hover {
    background-color: #38a171;
}
</style>
