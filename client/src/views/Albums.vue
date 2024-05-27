<template lang="">
    <div class="px-5 my-6">
        <h2>Saved</h2>
        <div class="flex justify-content-start overflow-auto flex-wrap flex-row gap-3">
            <Albums v-for="(album, index) in albums"
                :key="index"
                :title="album.title"  
                :id="album.id"
                :userId = "album.userId"
                :numberOfCourses="album.numberOfCourses"  
            />
        </div>
    </div>
</template>
<script>
import Albums from '../components/saved/Albums.vue';
export default {
    components:{
        Albums,
    },
    data(){
        return {
            albums:[]
        };
    },
    methods:{
        async getAlbums(){
            try{
                const response = await fetch(`http://127.0.0.1:8000/api/album/get`, {
                    method: 'GET',
                    headers: {
                    'Content-Type': 'application/json',
                    'Access-Control-Allow-Origin': '*',
                    },
                    mode: 'cors'
                });
                if (!response.ok) {
                    throw new Error('Something went wrong fetching the courses you are enrolled in. Please refresh the page!');
                }
                const data = await response.json();
                const albums = data.albums;
                // Assuming the response data structure matches the card data structure
                this.albums = albums.map(album => ({
                    id: album.id,
                    userId: album.user_id,
                    title: album.title,
                    numberOfCourses : album.favorites_count
                }));
            }catch(error){
            }
        },
    },
    mounted(){
        this.getAlbums();
    }
}
</script>
<style lang="">
    
</style>