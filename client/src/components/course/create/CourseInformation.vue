<template>
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
            v-model="course.title"
            placeholder="e.g. Introduction to Data Analysis"
            class="input-field w-full p-2 border-1 border-solid border-round-md"
          />
        </div>
        <div class="field-group flex gap-3 justify-content-between">
          <div class="field mb-4 w-12">
            <label for="category">Category</label>
            <Dropdown
              id="category"
              v-model="course.category_id"
              :options="categories"
              optionLabel="name"
              class="input-field w-full p-2"
            >
            </Dropdown>
          </div>
          <div class="field mb-4 w-12">
            <label for="level">Level</label>
            <Dropdown
              id="level"
              v-model="course.level_id"
              :options="levels"
              optionLabel="name"
              class="input-field w-full p-2"
            >
            </Dropdown>
          </div>
        </div>
        <div class="field mb-4">
          <label for="description">Description</label>
          <Textarea
            id="description"
            v-model="course.description"
            class="textarea-field w-full p-2 border-1 border-solid border-round-md h-10rem"
          ></Textarea>
        </div>
        <div class="field mb-4">
          <label for="price">Price</label>
          <FloatLabel>
            <InputNumber
              id="price"
              v-model="course.price"
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
            v-model="course.tags"
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
    <div class="button-group flex justify-content-between mt4">
      <Button
        class="button-primary py-2 px-3 text-sm border-none border-round-md cursor-pointer"
        @click="saveCourseData"
        label="Save and Continue"
      />
    </div>
  </div>
</template>

<script>
import Button from "primevue/button";
import { ref, onMounted } from "vue";
import { useToast } from "primevue/usetoast";
import Textarea from "primevue/textarea";
import InputText from "primevue/inputtext";
import InputNumber from "primevue/inputnumber";
import Dropdown from "primevue/dropdown";
import Listbox from "primevue/listbox";
import FloatLabel from "primevue/floatlabel";
import FileUpload from "primevue/fileupload";

export default {
  props: {
    nextCallback: Function,
  },
  setup(props, { emit }) {
    const course = ref({
      title: "",
      category_id: "",
      level_id: "",
      description: "",
      price: "",
      tags: "",
      thumbnail: null,
      video: null,
      errors: {
        title: "",
        category_id: "",
        level_id: "",
        description: "",
        price: "",
        tags: "",
        thumbnail: "",
        video: "",
      },
    });

    const categories = ref([]);
    const levels = ref([]);

    const toast = useToast();

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

    onMounted(() => {
      fetchCategories();
      fetchLevels();
    });

    const handleFileSelect = (type, event) => {
      const file = event.files[0];
      if (type === "thumbnail") {
        course.value.thumbnail = file;
      } else if (type === "video") {
        course.value.video = file;
      }
    };

    const updateErrors = (errors) => {
      if (errors.title) {
        course.value.errors.title = errors.title[0];
      }
      if (errors.category_id) {
        course.value.errors.category_id = errors.category_id[0];
      }
      if (errors.level_id) {
        course.value.errors.level_id = errors.level_id[0];
      }
      if (errors.description) {
        course.value.errors.description = errors.description[0];
      }
      if (errors.thumbnail) {
        course.value.errors.thumbnail = errors.thumbnail[0];
      }
      if (errors.video) {
        course.value.errors.video = errors.video[0];
      }
      if (errors.price) {
        course.value.errors.price = errors.price[0];
      }
      if (errors.tags) {
        course.value.errors.tags = errors.tags[0];
      }
    };

    const resetErrors = () => {
      course.value.errors = {
        title: "",
        category_id: "",
        level_id: "",
        description: "",
        price: "",
        tags: "",
        thumbnail: "",
        video: "",
      };
    };

    async function saveCourseData() {
      const formData = new FormData();
      formData.append("user_id", 1); // replace with actual user ID
      formData.append("title", course.value.title);
      formData.append("category_id", course.value.category_id.id);
      formData.append("level_id", course.value.level_id.id);
      formData.append("description", course.value.description);
      formData.append("price", course.value.price);
      formData.append("tags", course.value.tags);
      if (course.value.thumbnail) {
        formData.append("thumbnail", course.value.thumbnail);
      }
      if (course.value.video) {
        formData.append("video", course.value.video);
      }

      try {
        const response = await fetch(
          "http://127.0.0.1:8000/api/course/create",
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
          const data = await response.json();
          if (data.errors) {
            updateErrors(data.errors);
          }
          throw new Error("Something went wrong. Please try again!");
        } else {
          const data = await response.json();
          toast.add({
            severity: "success",
            summary: "Success",
            detail: "Course created successfully",
            life: 4000,
          });
          emit("courseCreated", data.id);
          console.log("Course id in courseinformation:", data.id);
        }
      } catch (error) {
        toast.add({
          severity: "error",
          summary: "Error",
          detail: error.message,
          life: 4000,
        });
      }
      console.log("Next callback should be called now.");
      props.nextCallback();
    }
    return {
      course,
      categories,
      levels,
      handleFileSelect,
      saveCourseData,
    };
  },
  components: {
    Button,
    Textarea,
    InputText,
    InputNumber,
    Dropdown,
    Listbox,
    FloatLabel,
    FileUpload,
  },
};
</script>
