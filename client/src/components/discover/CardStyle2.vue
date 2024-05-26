<template>
    <div class="card border-round-md surface-card w-custom-card flex flex-column">
      <Link :to="{path: '/course/details', query:{course_id: id}}" :highlight="false" class="align-items-stretch border-round-md surface-card flex-shrink-0 p-0 flex flex-column" @mouseover="showIcon" @mouseleave="hideIcon">
      <p class="hidden">{{ id }}</p>
      <div class="flex flex-column relative">
        <img :src="coverPhoto" class="course-image border-round-md border-noround-bottom" alt="Image" />
        <i class="pi pi-play-circle text-3xl text-900 absolute top-50 left-50 translate-5050" :class="{ hidden: !isIconVisible }"></i>
      </div>
      <div class="col-auto flex justify-content-between my-2 px-2 align-items-center">
        <Tag :value="category" severity="secondary"></Tag>
        <p class="text-xs text-400">{{numberOfEnrollments}} enrolled</p>
      </div>
      <div class="flex flex-column align-items-start">
        <p class="font-semibold mt-3 mb-0 px-2 truncate-text text-base text-900" :title="titleTruncated">{{ titleTruncated }}</p>
        <div class="teachers px-2 m-0">
          <p :title="authorsTruncated" class="text-xs text-400 truncate-text">
            {{ authorsTruncated }}
          </p>
        </div>
        <div class="flex flex-row justify-content-between align-items-center mb-3 mt-5 px-2 w-12">
          <div class="lesson flex flex-row justify-content-center align-items-center">
            <span class="text-xs text-400">{{ courseInfo }}</span>
            <Dot/>
            <span class="text-xs text-400">{{duration}}</span>
          </div>
          <div class="flex flex-row align-items-center">
            <Button icon="pi pi-bookmark text-lg" severity="secondary" @click="dialogVisible = true" text></Button>
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
    </Link>
    </div>
</template>
<script>
import uiUxCourse2 from '../../assets/images/ui-ux-course-2.jpg';
import Image from 'primevue/image';
import Button from 'primevue/button';
import Avatar from 'primevue/avatar';
import AvatarGroup from 'primevue/avatargroup';
import Tag from 'primevue/tag';
import Dot from '../Dot.vue';
import Link from '../Link.vue';
import SaveCourseDialog from '../saved/SaveCourseDialog.vue';
export default {
    props:{
        id: Number,
        coverPhoto: String,
        title: String,
        courseInfo: String,
        numberOfEnrollments: Number,
        duration: String,
        author: String,
        category: String,
        maxLength: {
            type: Number,
            default: 45,
        },
    },

    components:{
        Image,
        Avatar,
        AvatarGroup,
        Tag,
        Button,
        Dot,
        Link,
        SaveCourseDialog,
    },
    data(){
      return{
        isIconVisible: false,
        dialogVisible: false,
        courseTitle: this.title,
        createAlbum: false,
        id: this.id,
      }
    },
    computed: {
      authorsTruncated() {
        if (this.author.length > this.maxLength) {
          return this.author.slice(0, this.maxLength) + '...';
        }
        return this.author;
      },
      titleTruncated() {
        if (this.title.length > this.maxLength) {
          return this.title.slice(0, this.maxLength) + '...';
        }
        return this.title;
      },
    },
    methods:{
      showIcon(){
        this.isIconVisible = true;
      },
      hideIcon(){
        this.isIconVisible = false;
      }
    }
}
</script>
<style lang="css">
    .course-image{
      height: 100%;
      width: 100%;
      object-fit: cover;/* This will ensure the image covers the container without stretching */
    }
    .translate-5050{
      transform: translate(-50%,-50%);
    }
</style>