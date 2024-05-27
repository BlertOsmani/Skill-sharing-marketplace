<template>
  <div
    class="flex justify-content-start overflow-auto flex-wrap flex-row gap-3"
  >
    <MyCourse
      v-for="(card, index) in cards"
      :key="index"
      :id="card.id"
      :thumbnail="card.coverPhoto"
      :title="card.title"
      :category="card.category"
      :author="card.author"
      :info="card.courseInfo"
      :duration="card.duration"
      :enrolled="card.numberOfEnrollments"
      @requireConfirmation="requireConfirmation"
      @openEditDialog="openEditDialog"
      @viewLessons="viewLessons"
    />
    <Toast ref="toast" />
    <ConfirmDialog group="headless">
      <template #container="{ message, acceptCallback, rejectCallback }">
        <div
          class="flex flex-column align-items-center p-5 surface-overlay border-round"
        >
          <div
            class="border-circle surface-ground inline-flex justify-content-center align-items-center h-6rem w-6rem -mt-8"
          >
            <i class="pi pi-question text-5xl"></i>
          </div>
          <span class="font-bold text-2xl block mb-2 mt-4">{{
            message.header
          }}</span>
          <p class="mb-0">{{ message.message }}</p>
          <div class="flex align-items-center gap-2 mt-4">
            <Button
              severity="danger"
              label="Yes"
              @click="acceptCallback"
            ></Button>
            <Button
              severity="secondary"
              label="No"
              outlined
              @click="rejectCallback"
            ></Button>
          </div>
        </div>
      </template>
    </ConfirmDialog>
    <Dialog
      header="Course Information"
      :visible.sync="showDialog"
      modal
      :style="{ width: '80vw' }"
    >
      <div class="course-information p-5">
        <div class="course-header mb-4">
          <h2>Course Information</h2>
        </div>
        <div class="course-content flex justify-content-between">
          <div class="left-section flex-1 mr-5">
            <div class="field mb-4">
              <label for="title">Title</label>
              <InputText
                type="text"
                id="title"
                v-model="selectedCourse.title"
                placeholder="e.g. Introduction to Data Analysis"
                class="input-field w-full p-2 border-1 border-solid border-round-md"
              />
            </div>
            <div class="field-group flex gap-3 justify-content-between">
              <div class="field mb-4 w-12">
                <label for="category">Category</label>
                <Dropdown
                  id="category"
                  v-model="selectedCourse.category_id"
                  :options="categories"
                  optionLabel="name"
                  class="input-field w-full p-2"
                />
              </div>
              <div class="field mb-4 w-12">
                <label for="level">Level</label>
                <Dropdown
                  id="level"
                  v-model="selectedCourse.level_id"
                  :options="levels"
                  optionLabel="name"
                  class="input-field w-full p-2"
                />
              </div>
            </div>
            <div class="field mb-4">
              <label for="description">Description</label>
              <Textarea
                id="description"
                v-model="selectedCourse.description"
                class="textarea-field w-full p-2 border-1 border-solid border-round-md h-10rem"
              />
            </div>
            <div class="field mb-4">
              <label for="price">Price</label>
              <FloatLabel>
                <InputNumber
                  id="price"
                  v-model="selectedCourse.price"
                  mode="currency"
                  currency="EUR"
                  placeholder="e.g. 99.99"
                  class="w-full"
                  step="0.01"
                />
              </FloatLabel>
            </div>
            <div class="field mb-4">
              <label for="tags">Tags</label>
              <InputText
                type="text"
                id="tags"
                v-model="selectedCourse.tags"
                placeholder="e.g. data, analysis"
                class="input-field w-full p-2 border-1 border-solid border-round-md"
              />
            </div>
          </div>
          <div class="right-section flex-1">
            <div class="upload-field mb-4 flex flex-column">
              <label for="coverImage" class="mb-2">Cover Image</label>
              <div class="">
                <FileUpload
                  mode="basic"
                  name="coverImage"
                  accept="image/*"
                  customUpload
                  @select="handleFileSelect('thumbnail', $event)"
                />
              </div>
            </div>
            <div class="upload-field mb-4 flex flex-column">
              <label for="salesVideo" class="mb-2">Sales Video</label>
              <div class="">
                <FileUpload
                  mode="basic"
                  name="salesVideo"
                  accept="video/*"
                  customUpload
                  @select="handleFileSelect('video', $event)"
                />
              </div>
            </div>
          </div>
        </div>
        <div class="button-group flex justify-content-between mt-4">
          <Button
            label="Cancel"
            icon="pi pi-times"
            @click="showDialog = false"
            class="p-button-text p-mr-2"
          />
          <Button
            class="button-primary py-2 px-3 text-sm border-none border-round-md cursor-pointer"
            @click="updateCourse"
            label="Save"
          />
        </div>
      </div>
    </Dialog>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import MyCourse from "../components/course/myCourse/myCourse.vue";
import AuthServices from "@/services/AuthServices";
import ConfirmDialog from "primevue/confirmdialog";
import Toast from "primevue/toast";
import { useConfirm } from "primevue/useconfirm";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import Dropdown from "primevue/dropdown";
import Textarea from "primevue/textarea";
import InputNumber from "primevue/inputnumber";
import FileUpload from "primevue/fileupload";
import FloatLabel from "primevue/floatlabel";
import { useRouter } from "vue-router";

const cards = ref([]);
const selectedCourse = ref({
  title: "",
  category_id: null,
  level_id: null,
  description: "",
  price: null,
  tags: "",
  thumbnail: null,
  video: null,
});
const toast = ref(null);
const confirm = useConfirm();
const showDialog = ref(false);
const categories = ref([]);
const levels = ref([]);
const router = useRouter();

const getCourses = async () => {
  const user = await AuthServices.getProfile();
  try {
    const response = await fetch(
      `http://127.0.0.1:8000/api/users/${user.data.id}/courses`,
      {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
          "Access-Control-Allow-Origin": "*",
        },
        mode: "cors",
      }
    );
    if (!response.ok) {
      throw new Error(
        "Something went wrong fetching the courses you have created. Please refresh the page!"
      );
    }

    const data = await response.json();
    cards.value = data.map((course) => ({
      id: course.course_id,
      coverPhoto: course.course_thumbnail,
      title: course.course_title,
      author: course.author,
      courseInfo: course.course_tags,
      progressValue: 10,
      numberOfEnrollments: course.number_of_enrollments,
      category: course.category_name,
      duration: formatDuration(course.duration),
    }));
  } catch (error) {
    console.error("Error fetching learning data:", error);
  }
};

const formatDuration = (totalMinutes) => {
  const hours = Math.floor(totalMinutes / 60);
  const minutes = Math.floor(totalMinutes % 60);
  const seconds = Math.round((totalMinutes - Math.floor(totalMinutes)) * 60);
  return `${hours.toString().padStart(2, "0")}:${minutes
    .toString()
    .padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`;
};

const requireConfirmation = (course) => {
  selectedCourse.value = course;
  confirm.require({
    group: "headless",
    header: "Are you sure?",
    message: "Please confirm to proceed.",
    accept: () => {
      confirmDelete();
    },
    reject: () => {
      toast.value.add({
        severity: "error",
        summary: "Rejected",
        detail: "You have rejected",
        life: 3000,
      });
    },
  });
};
const viewLessons = (course) => {
  router.push({
    name: "CourseLessons",
    query: { courseId: course.id },
  });
};
const confirmDelete = async () => {
  try {
    const response = await fetch(
      `http://127.0.0.1:8000/api/course/delete/${selectedCourse.value.id}`,
      {
        method: "DELETE",
        headers: {
          "Content-Type": "application/json",
        },
      }
    );
    if (!response.ok) throw new Error("Failed to delete the course");
    cards.value = cards.value.filter(
      (course) => course.id !== selectedCourse.value.id
    );
    toast.value.add({
      severity: "success",
      summary: "Success",
      detail: "Course deleted",
      life: 3000,
    });
    getCourses(); // Refresh the courses list
  } catch (error) {
    toast.value.add({
      severity: "error",
      summary: "Error",
      detail: "Failed to delete course",
      life: 3000,
    });
  }
};
const openEditDialog = (course) => {
  selectedCourse.value = { ...course };
  showDialog.value = true;
};
const handleFileSelect = (type, event) => {
  if (type === "thumbnail") {
    selectedCourse.value.thumbnail = event.files[0];
  } else if (type === "video") {
    selectedCourse.value.video = event.files[0];
  }
};
const fetchCategories = async () => {
  const response = await fetch("http://127.0.0.1:8000/api/categories/get", {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
      "Access-Control-Allow-Origin": "*",
    },
    mode: "cors",
  });
  categories.value = await response.json();
};
const fetchLevels = async () => {
  const response = await fetch("http://127.0.0.1:8000/api/levels/get", {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
      "Access-Control-Allow-Origin": "*",
    },
    mode: "cors",
  });
  levels.value = await response.json();
};
const updateCourse = async () => {
  // Add your save logic here
  try {
    const formData = new FormData();
    formData.append("title", selectedCourse.value.title);
    formData.append("category_id", selectedCourse.value.category_id.id);
    formData.append("level_id", selectedCourse.value.level_id.id);
    formData.append("description", selectedCourse.value.description);
    formData.append("price", selectedCourse.value.price);
    formData.append("tags", selectedCourse.value.tags);
    if (selectedCourse.value.thumbnail) {
      formData.append("thumbnail", selectedCourse.value.thumbnail);
    }
    if (selectedCourse.value.video) {
      formData.append("video", selectedCourse.value.video);
    }

    const response = await fetch(
      `http://127.0.0.1:8000/api/course/update/${selectedCourse.value.id}`,
      {
        method: "POST",
        body: formData,
      }
    );

    if (!response.ok) {
      throw new Error("Failed to update the course");
    }

    toast.value.add({
      severity: "success",
      summary: "Success",
      detail: "Course updated",
      life: 3000,
    });

    getCourses(); // Refresh the courses list
    showDialog.value = false;
  } catch (error) {
    toast.value.add({
      severity: "error",
      summary: "Error",
      detail: "Failed to update course",
      life: 3000,
    });
  }
};

onMounted(() => {
  if(!localStorage.getItem('authToken')){
    router.push('/');
  }
  getCourses();
  fetchCategories();
  fetchLevels();
});
</script>
