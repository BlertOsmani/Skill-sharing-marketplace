<template>
    <div class="card border-round-md surface-card w-custom flex flex-column">
      <Link to="/" :highlight="false" class="align-items-stretch border-round-md surface-card flex-shrink-0 w-12 p-0 flex flex-column" @mouseover="showIcon" @mouseleave="hideIcon">
      <p class="hidden">{{ id }}</p>
      <div class="flex flex-column relative">
        <img :src="coverphoto" class="course-image border-round-md border-noround-bottom" alt="Image" />
        <i class="pi pi-play-circle text-3xl text-900 absolute top-50 left-50 translate-5050" :class="{ hidden: !isIconVisible }"></i>
        <ProgressBar :value="progressValue"></ProgressBar>
      </div>
      <div class="col-auto flex justify-content-between my-2 px-2 align-items-center">
        <Tag :value="category" severity="secondary"></Tag>
        <span class="font-semibold text-400 text-xs">{{number_of_enrollments}} enrolled</span>
      </div>
      <div class="flex flex-column align-items-start">
        <p class="font-semibold text-900 text-base mt-3 mb-0 px-2">{{ title }}</p>
        <div class="teachers px-2 m-0">
          <p :title="authorTruncated" class="text-xs text-400 truncate-text">
            {{ authorTruncated }}
          </p>
        </div>
        <div class="flex flex-row justify-content-between align-items-center w-12 mt-5 mb-3 px-2">
          <div class="lesson flex flex-row justify-content-center align-items-center">
            <span class="text-xs text-400">{{ courseInfo }}</span>
            <Dot/>
            <span class="text-xs text-400">{{ timeLeft }}</span>
          </div>
          <div class="flex justify-content-end">
            <Button label="Resume" severity="secondary" icon="pi pi-play" iconPos="right" class="resume-course-button"></Button>
          </div>
        </div>
      </div>
    </Link>
    </div>
</template>
  
<script>
  import Link from '../Link.vue';
  import ProgressBar from 'primevue/progressbar';
  import Button from 'primevue/button';
  import Tag from 'primevue/tag';
  import Image from 'primevue/image';
  import Dot from '../Dot.vue';
  
  export default {
    props: {
      id:Number,
      coverphoto: String,
      title: String,
      author: String,
      courseInfo: String,
      number_of_enrollments: Number,
      timeLeft: String,
      progressValue: Number,
      category: String,
      maxLength: {
        type: Number,
        default: 45,
      },
    },
    computed: {
      authorTruncated() {
        if (this.author.length > this.maxLength) {
          return this.author.slice(0, this.maxLength) + '...';
        }
        return this.author;
      },
    },
    components: {
      Image,
      Tag,
      Button,
      ProgressBar,
      Dot,
      Link,
    },
    data(){
      return{
        isIconVisible: false,
      }
    },
    methods:{
      showIcon(){
        this.isIconVisible = true;
      },
      hideIcon(){
        this.isIconVisible = false;
      }
    }
  };
  </script>
  
  <style lang="css">
  .resume-course-button .p-button-icon {
    font-size: 12px;
  }
  .p-progressbar {
    height: 0.2rem;
    border-radius: 0 !important;
  }
  .p-progressbar .p-progressbar-label {
    display: none;
  }
  .truncate-text {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .course-image img{
      height: 100%;
      width: 100%;
      object-fit: cover; /* This will ensure the image covers the container without stretching */
  }
  </style>
  