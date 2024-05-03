<template lang="">
  <div id="app">
      <Navbar v-if="showNavbar"/>
      <router-view/>
  </div>
</template>
<script>
import Navbar from './components/navbar/Navbar.vue';
export default {
    name: 'App',
    components:{
      Navbar,
    },
    computed: {
      showNavbar() {
        // Define paths where the Navbar should not be displayed
        const excludedPaths = ['/login', '/register'];
        
        // Check if the current route's path is in the excluded paths
        return !excludedPaths.includes(this.$route.path);
      },
    },
}
</script>
<style lang="">
  
</style>
