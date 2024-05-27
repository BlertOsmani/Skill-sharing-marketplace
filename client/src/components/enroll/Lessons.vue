<template>
    <div class="w-12">
        <div class="card w-12 flex my-6 flex-column align-items-center">
            <h2>Lessons</h2>
            <Accordion :activeIndex="0" class="custom-accordion w-10">
                <AccordionTab 
                    v-for="(lesson, index) in lessons" 
                    :key="lesson.id" 
                    :header="`${index + 1}. ${lesson.title}`">
                    <div class="video-content flex flex-row ">
                        <video controls class="lesson-video pr-4">
                            <source :src="lesson.video_url" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <div>
                            <h3>Description</h3>
                            <p class="m-0">{{ lesson.content }}</p>
                        </div>
                    </div>
                </AccordionTab>
            </Accordion>
        </div>
    </div>
</template>


<script setup>
import { ref, onMounted } from 'vue';
import Accordion from 'primevue/accordion';
import AccordionTab from 'primevue/accordiontab';
import axios from 'axios';
import { useRoute } from 'vue-router';

const route = useRoute();

const lessons = ref([]);

const fetchLessons = async () => {
    try {
        const courseId = route.query.courseId; // Replace with the actual course ID
        const response = await axios.get(`courses/${courseId}/lessons`);
        lessons.value = response.data;
    } catch (error) {
        console.error("Error fetching lessons:", error);
    }
};

onMounted(() => {
    fetchLessons();
});
</script>


<style lang="css">
.custom-accordion .p-accordion-header {
    width: 100%; /* Ensure headers take the full width of the accordion */
}

.custom-accordion .p-accordion-content {
    width: 100%; /* Ensure content sections take the full width of the accordion */
}

.p-accordion-content {
    transition: height 0.3s ease; /* Smooth transition for height changes */
}

.video-content {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.lesson-video {
    width: 100%;
    max-width: 600px; /* Adjust the max width as needed */
    margin-bottom: 10px;
}
</style>

