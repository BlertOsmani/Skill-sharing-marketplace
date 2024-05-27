<template>
    <div>
      <ul class="flex flex-row align-items-center list-none gap-1">
        <li>
          <Link to="/" class="border-round-md" label="Discover" severity="secondary" />
        </li>
        <li>
          <div class="relative">
            <Link 
              to="" 
              class="border-round-md" 
              severity="secondary" 
              :class="{'hovered-link': !hidden}" 
              @click="toggleMenu" 
              iconPos="right" 
              label="Categories" 
              icon="pi pi-angle-down" 
            />
            <Dropdown 
              class="flex flex-row left-0" 
              v-if="!hidden" 
              badge="2" 
              :dropdownItems="profileDropdownItems" 
            />
          </div>
        </li>
      </ul>
    </div>
  </template>
  
  
  <script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import Dropdown from '../Dropdown.vue';
import Link from '../Link.vue';
import axios from 'axios';

const hidden = ref(true);
const profileDropdownItems = ref([
  {
    width: '100%',
    groups: [
      {
        title: '',
        items: [] // This will be filled dynamically
      }
    ],
  },
]);

const route = useRoute();

const fetchCategories = () => {
  axios.get('categories/get')
    .then(response => {
      const categories = response.data;
      profileDropdownItems.value[0].groups[0].items = categories.map(category => ({
        to: { path: '/categories', query: { name: category.name, id: category.id } },
        label: category.name,
        icon: '',
        badge: ''
      }));
    })
    .catch(error => {
      console.error("There was an error fetching the categories!", error);
    });
};

const toggleMenu = () => {
  hidden.value = !hidden.value;
};

const handleClickOutside = (event) => {
  if (!event.target.closest('.relative')) {
    hidden.value = true;
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
  fetchCategories();
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});

watch(route, fetchCategories);
</script>

  
  <script>
  import Dropdown from '../Dropdown.vue';
  import Link from '../Link.vue';
  
  export default {
    components: {
      Link,
      Dropdown,
    },
  };
  </script>
  
  <style lang="css">
  .hovered-link {
    background: var(--surface-hover);
  }
  .relative {
    position: relative;
  }
  </style>
  