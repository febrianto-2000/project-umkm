<template>
  <div>
    <ButtonToolbarWithModal :formData="umkmformdata" targetURL="/api/v1/add-umkm" contentType="multipart/form-data" buttonTitle="Tambahkan UMKM" tiIcon="ti-user" modalTitle="Formulir Tambah Rumah" />
    <Title titleText="List UMKM" />
  
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Nama</th>
                <th>Pemilik</th>
                <th>Personal Kontak</th>
                <th>Alamat</th>
              </tr>
            </thead>
            <tbody v-if="list.length > 0">
              <tr v-for="(umkm, index) in list" :key="index" :id="'umkm_' + umkm.id" @click="redirectToUMKMOverview(umkm.id)">
                <td>{{ umkm.nama_umkm }}</td>
                <td>{{ umkm.nama_pemilik }}</td>
                <td>{{ umkm.personal_kontak }}</td>
                <td>{{ umkm.alamat }}, {{ umkm.kota }}, {{ umkm.provinsi }}</td>
              </tr>
            </tbody>
            <tbody v-else>
              <tr>
                <td colspan="4" class="text-center text-muted"><em>Tidak ada data</em></td>
              </tr>
            </tbody>
          </table>
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
  name: 'ListUMKM',
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
      list: [] 
    };
  },
  mounted() {
    const adminToken = localStorage.getItem('adminToken');
    this.fetchUMKM(adminToken);
  },
  methods: {
    async fetchUMKM(adminToken) {
      try {
        const response = await axios.post('/api/v1/fetch-umkm', {}, {
          headers: {
            Authorization: `Bearer ${adminToken}`
          }
        });
        this.list = response.data.umkm;
      } catch (error) {
        this.$router.push('/login');
      }
    },
    redirectToUMKMOverview(umkmId) {
      this.$router.push(`/umkm-overview/${umkmId}`);
    }
  }
}
</script>
