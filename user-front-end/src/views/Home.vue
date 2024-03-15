<template>
    <div class="container">
      <div class="row">
        <div v-for="umkm in umkms" :key="umkm.id" class="col-lg-4">
          <div class="item">
            <div class="thumb">
              <div class="hover-content">
                <ul>
                  <li><a :href="'overview/' + umkm.id"><i class="fa fa-eye"></i></a></li>
                </ul>
              </div>
              <img :src="'http://localhost:8000/storage/'+umkm.foto_1" alt="UMKM Image" style="width:200px">
            </div>
            <div class="down-content">
              <h4>{{ umkm.nama_umkm }}</h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
    import axios from '@/utils/axios'; 
  export default {
    name: 'Home',
    data() {
    return {
        umkms: []
        };
    },
    mounted() {
        this.fetchUMKMs();
    },
    methods: {
        async fetchUMKMs() {
        try {
            const response = await axios.post('/api/v1/fetch-umkm-public');
            this.umkms = response.data.umkm;
        } catch (error) {
            console.error('Error fetching UMKMs:', error);
        }
        }
    }
  }
  </script>
  