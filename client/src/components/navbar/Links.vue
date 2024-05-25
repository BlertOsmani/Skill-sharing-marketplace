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
  import { ref, onMounted, onUnmounted } from 'vue';
  import Dropdown from '../Dropdown.vue';
  import Link from '../Link.vue';
  
  const hidden = ref(true);
  
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
  });
  
  onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
  });
  
  // Define dropdown items
  const profileDropdownItems = [
    {
      width: '100%',
      groups: [
        {
          title: '',
          items: [
            { 
              to: { path: '/categories', query: { name: 'Technology', id: 1 } }, 
              label: 'Programming', 
              icon: '', 
              badge: '' 
            },
            { to: '/favorites', label: 'Favorites', icon: 'pi pi-heart', badge: '' },
          ],
        },
        {
          title: 'Profile',
          items: [
            { to: '/messages', label: 'Messages', icon: 'pi pi-comments', badge: '2' },
            { to: '/settings', label: 'Account', icon: 'pi pi-user', badge: '' },
            { to: '/settings', label: 'Settings', icon: 'pi pi-cog', badge: '' },
          ],
        },
      ],
    },
  ];
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
  