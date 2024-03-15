<template>
  <ButtonToolbarWithModal :formData="umkmformdata" targetURL="/api/v1/add-umkm" contentType="multipart/form-data" buttonTitle="Tambahkan UMKM" tiIcon="ti-user" modalTitle="Formulir Tambah Rumah" />
    <Title titleText="Dashboard" />
  
    <div class="row">
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <p class="card-title text-md-center text-xl-left">Total Active UMKM</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0">{{ dashboard_data.total_umkm }}</h3>
              <i class="ti-home icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
            </div>  
            <!-- <p class="mb-0 mt-2 text-danger">0.12% <span class="text-black ms-1"><small>(30 days)</small></span></p> -->
          </div>
        </div>
      </div>
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <p class="card-title text-md-center text-xl-left">Total Product</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0">{{ dashboard_data.total_product }}</h3>
              <i class="ti-package icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
            </div>  
            <!-- <p class="mb-0 mt-2 text-danger">0.47% <span class="text-black ms-1"><small>(30 days)</small></span></p> -->
          </div>
        </div>
      </div>
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <p class="card-title text-md-center text-xl-left">Total Active Admin</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0">{{ dashboard_data.total_admin }}</h3>
              <i class="ti-user icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
            </div>  
            <!-- <p class="mb-0 mt-2 text-danger">0.47% <span class="text-black ms-1"><small>(30 days)</small></span></p> -->
          </div>
        </div>
      </div>
    </div>
  </template>
<script>
import axios from '@/utils/axios'; 
import Title from './components/Title.vue';
import ButtonToolbarWithModal from './components/ButtonToolbarWithModal.vue';

export default {
  name: 'Dashboard',
  components: {
    Title,
    ButtonToolbarWithModal,
  },
  data() {
    return {
      umkmformdata: {
        nama_umkm: {
          type: 'text',
          label: 'Nama Umkm',
          is_required: true
        },
        nama_pemilik: {
          type: 'text',
          label: 'Nama Pemilik',
          is_required: true
        },
        personal_kontak: {
          type: 'text',
          label: 'Personal Kontak (e-mail / whatsapp)',
          is_required: true
        },
        deskripsi: {
          type: 'text',
          label: 'Deskripsi'
        },
        alamat: {
          type: 'text',
          label: 'Alamat',
          is_required: true
        },
        kota: {
          type: 'select',
          label: 'Kota',
          is_required: true
        },
        foto_1: {
          type: 'file',
          file_type: 'image',
          label: 'Foto UMKM 1',
          is_required: true,
        },
        foto_2: {
          type: 'file',
          file_type: 'image',
          label: 'Foto UMKM 2',
        },
        foto_3: {
          type: 'file',
          file_type: 'image',
          label: 'Foto UMKM 3',
        },
      },
      dashboard_data: [] 
    };
  },
  mounted() {
    const adminToken = localStorage.getItem('adminToken');

    this.takeDashboardData(adminToken);
  },
  methods: {
    async takeDashboardData(adminToken) {
      try {
        const response = await axios.post('/api/v1/data-dashboard', {}, {
          headers: {
            Authorization: `Bearer ${adminToken}`
          }
        });

        this.dashboard_data = response.data;
      } catch (error) {
        this.$router.push('/login');
      }
    }
  }
}
</script>