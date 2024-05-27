<template>
  <div class="p-m-4">
    <Toast />
    <div class="flex flex-row justify-content-between">
      <Button
        label="New Lesson"
        icon="pi pi-plus"
        @click="showDialog = true"
        class="p-button-raised p-button-success"
      />
      <Button
        label="Complete"
        icon="pi pi-check"
        iconPos="right"
        @click="complete"
        class="p-button-raised p-button-success"
      />
    </div>

    <Dialog
      header="New Lesson"
      v-model:visible="showDialog"
      :modal="true"
      :closable="false"
      class="p-fluid w-9 new-lesson-dialog"
    >
      <div class="p-field w-8">
        <label for="videoUrl">Video</label>
        <div id="videoUrl" class="p-d-flex p-ai-center p-p-3">
          <FileUpload
            mode="basic"
            name="coverImage"
            customUpload
            @select="handleFileUpload"
          >
          </FileUpload>
        </div>
      </div>
      <div class="p-field w-8">
        <label for="name">Lesson Title</label>
        <InputText id="name" v-model="lesson.title" />
      </div>
      <div class="p-field w-8">
        <label for="content">Content</label>
        <Textarea
          id="content"
          v-model="lesson.content"
          rows="3"
          class="p-inputtextarea p-fluid h-15rem"
        ></Textarea>
      </div>
      <div class="p-d-flex p-jc-end w-4">
        <Button
          label="Cancel"
          icon="pi pi-times"
          @click="showDialog = false"
          class="p-button-text p-mr-2"
        />
        <Button
          label="Save"
          icon="pi pi-check"
          @click="saveOrUpdateLesson"
        ></Button>
      </div>
    </Dialog>

    <!-- Optional: List of lessons to show updates -->
    <DataTable :value="lessons" size="small">
      <Column header="Video" class="w-2">
        <template #body="slotProps">
          <video controls class="w-6 flex">
            <source :src="slotProps.data.video_url" type="video/mp4" />
            Your browser does not support the video tag.
          </video>
        </template>
      </Column>
      <Column field="title" header="Title"></Column>
      <Column field="duration" header="Duration"></Column>
      <Column header="Actions" class="w-2">
        <template #body="slotProps">
          <Button
            icon="pi pi-pencil"
            class="p-button-rounded p-button-success mr-2"
            @click="editLesson(slotProps.data)"
          />
          <Button
            icon="pi pi-trash"
            class="p-button-rounded p-button-danger"
            @click="requireConfirmation(slotProps.data)"
          />
        </template>
      </Column>
    </DataTable>
    <ConfirmDialog group="headless">
      <template #container="{ message, acceptCallback, rejectCallback }">
        <div
          class="flex flex-column align-items-center p-5 surface-overlay border-round"
        >
          <div
            class="border-circle bg-primary inline-flex justify-content-center align-items-center h-6rem w-6rem -mt-8"
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
    <video
      ref="hiddenVideo"
      style="display: none"
      @loadedmetadata="calculateDuration"
    ></video>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";
import { defineProps } from "vue";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import FileUpload from "primevue/fileupload";
import Textarea from "primevue/textarea";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import ConfirmDialog from "primevue/confirmdialog";
import { useConfirm } from "primevue/useconfirm";

const props = defineProps({
  courseId: {
    type: Number,
    required: true,
  },
});

const router = useRouter();
const showDialog = ref(false);
const lesson = ref({
  id: null,
  course_id: props.courseId,
  title: "",
  video_url: null,
  content: "",
  duration: "",
});
const lessons = ref([]);
const toast = useToast();
const hiddenVideo = ref(null);
const showDeleteDialog = ref(false);
const selectedLesson = ref(null);
const confirm = useConfirm();

console.log("Course id in lesson:", lesson.value.course_id);

const editLesson = (data) => {
  lesson.value = { ...data };
  showDialog.value = true;
  console.log("Editing lesson:", lesson.value);
};

const requireConfirmation = (lesson) => {
  selectedLesson.value = lesson;
  confirm.require({
    group: "headless",
    header: "Are you sure?",
    message: "Please confirm to proceed.",
    accept: () => {
      confirmDelete();
    },
    reject: () => {
      toast.add({
        severity: "error",
        summary: "Rejected",
        detail: "You have rejected",
        life: 3000,
      });
    },
  });
};

const confirmDelete = async () => {
  try {
    const response = await fetch(
      `http://127.0.0.1:8000/api/lesson/delete/${selectedLesson.value.id}`,
      {
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
      }
    );
    if (!response.ok) throw new Error("Failed to delete the lesson");

    lessons.value = lessons.value.filter(
      (lesson) => lesson.id !== selectedLesson.value.id
    );
    toast.add({
      severity: "success",
      summary: "Deleted",
      detail: "Lesson successfully deleted",
      life: 3000,
    });
  } catch (error) {
    console.error("Error deleting lesson:", error);
    toast.add({
      severity: "error",
      summary: "Error",
      detail: "Failed to delete lesson",
      life: 3000,
    });
  }
};

const getLesson = async (id) => {
  try {
    const response = await fetch(
      `http://127.0.0.1:8000/api/course/${id}/lesson`,
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
        "Something went wrong fetching the courses you are enrolled in. Please refresh the page!"
      );
    }
    const data = await response.json();
    lessons.value = data.map((lesson) => ({
      id: lesson.id,
      courseId: lesson.course_id,
      title: lesson.title,
      video_url: lesson.video_url,
      content: lesson.content,
      duration: lesson.duration,
    }));
    // Assuming the response data structure matches the card data structure
  } catch (error) {
    console.error("Error fetching learning data:", error);
  }
};
onMounted(() => {
  getLesson(props.courseId);
});

const updateLesson = async (id) => {
  const formData = new FormData();
  formData.append("title", lesson.value.title);
  console.log("Title: ", lesson.value.title);
  if (lesson.value.video_url) {
    formData.append("video_url", lesson.value.video_url);
    console.log("Video URL: ", lesson.value.video_url);
  }
  formData.append("content", lesson.value.content);
  console.log("Content: ", lesson.value.content);
  formData.append("duration", lesson.value.duration);
  console.log("Duration: ", lesson.value.duration);
  try {
    const response = await fetch(
      `http://127.0.0.1:8000/api/course/lesson/update/${id}`,
      {
        method: "POST",
        headers: {
          "Access-Control-Allow-Origin": "*",
        },
        mode: "cors",
        body: formData,
      }
    );

    if (!response.ok) {
      throw new Error("Network response was not ok " + response.statusText);
    }

    const result = await response.json();
    console.log("Lesson updated successfully:", result);

    toast.add({
      severity: "success",
      summary: "Success",
      detail: "Lesson updated successfully",
    });
    getLesson(props.courseId);

    showDialog.value = false;
    resetLesson();
  } catch (error) {
    console.error("Error updating lesson:", error);
    toast.add({
      severity: "error",
      summary: "Error",
      detail: "Failed to update lesson",
    });
  }
};

const saveLesson = async () => {
  const formData = new FormData();
  formData.append("course_id", props.courseId);
  formData.append("title", lesson.value.title);
  if (lesson.value.video_url) {
    formData.append("video_url", lesson.value.video_url);
  }
  formData.append("content", lesson.value.content);
  formData.append("duration", lesson.value.duration);
  try {
    const response = await fetch(
      "http://127.0.0.1:8000/api/course/lesson/create",
      {
        method: "POST",
        headers: { "Access-Control-Allow-Origin": "*" },
        mode: "cors",
        body: formData,
      }
    );

    if (!response.ok) {
      throw new Error("Network response was not ok " + response.statusText);
    }

    const result = await response.json();
    console.log("Lesson saved successfully:", result);

    lessons.value.push(result);

    toast.add({
      severity: "success",
      summary: "Success",
      detail: "Lesson saved successfully",
    });
    getLesson(props.courseId);

    showDialog.value = false;
    resetLesson();
  } catch (error) {
    console.error("Error saving lesson:", error);
    toast.add({
      severity: "error",
      summary: "Error",
      detail: "Failed to save lesson",
    });
  }
};

const saveOrUpdateLesson = async () => {
  if (lesson.value.id) {
    updateLesson(lesson.value.id);
  } else {
    saveLesson();
  }
};

const resetLesson = () => {
  lesson.value = {
    course_id: props.courseId,
    title: "",
    content: "",
    video_url: null,
    duration: "",
  };
};

const handleFileUpload = (event) => {
  const file = event.files[0];
  if (file && file.type.startsWith("video/")) {
    const url = URL.createObjectURL(file);
    lesson.value.video_url = file;

    hiddenVideo.value.src = url;
    hiddenVideo.value.load();
  }
};
const calculateDuration = () => {
  const videoElement = hiddenVideo.value;
  lesson.value.duration = Math.floor(videoElement.duration);
  URL.revokeObjectURL(videoElement.src); // Free up memory
};
const complete = () => {
  router.push("/my-courses");
};
</script>

<style lang="css">
.p-fluid .p-field {
  margin-bottom: 1rem;
}
.new-lesson-dialog .p-dialog-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: start;
}
</style>
