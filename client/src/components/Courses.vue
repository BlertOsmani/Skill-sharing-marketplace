<template lang="">
        <Link :to="{path: '/course/details', query:{course_id: id}}" :class="customClass" class="surface-card flex flex-column p-0" @mouseover="showIcon" @mouseleave="hideIcon">
            <div class="flex flex-column w-12">
                <p class="hidden">{{id}}</p>        
                <div class="flex flex-column relative">
                    <img class="w-12" :src="thumbnail" alt="">
                    <i class="pi pi-play-circle text-3xl text-900 absolute top-50 left-50 translate-5050" :class="{ hidden: !isIconVisible }"></i>
                </div>
                <div class="flex flex-column px-2">
                    <div class="flex flex-row justify-content-between my-2 align-items-center">
                        <Tag severity="secondary" value="Web development">{{category}}</Tag>
                        <p class="text-400 text-xs font-semibold">{{enrolled}}</p>
                    </div>
                    <div class="flex flex-column align-items-start">
                        <p class="text-base font-semibold text-900 mt-3 mb-0">{{title}}</p>
                        <p class="text-xs font-semibold text-400">{{author}}</p>
                    </div>
                    <div class="flex flex-row justify-content-between mt-4 mb-2">
                        <div class="flex flex-row align-items-center">
                            <p class="text-xs text-400 font-semibold">{{info}}</p>
                            <Dot v-if="showDot"/>
                            <p class="text-xs text-400 font-semibold">{{duration}}</p>
                        </div>
                        <div v-if="isAuthenticated" class="flex justify-content-end align-items-center">
                            <div @click.stop.prevent="saveCourse">
                                <Button icon="pi pi-bookmark text-lg" severity="secondary" text></Button>
                            </div>
                            <SaveCourseDialog
                                :visible="dialogVisible"
                                @update:visible="dialogVisible = $event"
                                :title="courseTitle"
                                :createAlbum="createAlbum"
                                :id="id"
                            />   
                        </div>
                    </div>
                </div>
            </div>
        </Link>
</template>
<script>
import { ref, onMounted } from 'vue';
import AuthServices from "@/services/AuthServices";
import Dot from './Dot.vue';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import Link from './Link.vue';
import SaveCourseDialog from './saved/SaveCourseDialog.vue';

export default {
    components:{
        Link,
        Tag,
        Dot,
        Button,
        SaveCourseDialog
    },
    data() {
        return {
            dialogVisible: false,
            courseTitle: this.title,
            createAlbum: false,
            id: this.id,
        };
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
        customClass: String,
    },
    computed:{
        showDot() {
            return this.info && this.duration;
        },
    },
    methods:{
      showIcon(){
        this.isIconVisible = true;
      },
      hideIcon(){
        this.isIconVisible = false;
      },
      saveCourse() {
            this.dialogVisible = true;
        }
    },
    setup() {
        const isAuthenticated = ref(localStorage.getItem('authToken') !== null);

        onMounted(async () => {
        const user = await AuthServices.getProfile();
        if (user) {
            isAuthenticated.value = true;
        } else {
            isAuthenticated.value = false;
            localStorage.removeItem('authToken');
        }
        });

        return {
        isAuthenticated,
        };
    }
}
</script>
<style lang="css">
    
</style>