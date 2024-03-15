<template>
  <div>
    <ButtonToolbarWithModal :formData="adminform" targetURL="/api/v1/add-admin" contentType="application/json" buttonTitle="Tambahkan Admin" tiIcon="ti-user" modalTitle="Tambah Admin Baru" />
    <Title titleText="List UMKM" />
  
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Username</th>
              </tr>
            </thead>
            <tbody v-if="list.length > 0">
              <tr v-for="(admin, index) in list" :key="index">
                <td>
                  {{ admin.username }}
                  
                  <ModalDeleteConfirmationAdmin :data="admin" />
                  <button type="button" class="btn btn-inverse-light btn-fw" @click="showModal('modal-delete-confirmation-admin')">
                    <i class="ti-trash btn-icon-prepend"></i>       
                  </button>
                </td>
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
import ModalDeleteConfirmationAdmin from './components/modals/ModalDeleteConfirmationAdmin.vue';

export default {
  name: 'ListAdmin',
  components: {
    Title,
    ButtonToolbarWithModal,
    ModalDeleteConfirmationAdmin,
  },
  data() {
    return {
      adminform: {
        username: {
          type: 'text',
          label: 'Username',
          is_required: true
        },
        password: {
          type: 'password',
          label: 'Password',
          is_required: true
        },
      },
      list: [] 
    };
  },
  mounted() {
    const adminToken = localStorage.getItem('adminToken');
    this.fetchAdmin(adminToken);
  },
  methods: {
    async fetchAdmin(adminToken) {
      try {
        const response = await axios.post('/api/v1/fetch-admin', {}, {
          headers: {
            Authorization: `Bearer ${adminToken}`
          }
        });
        this.list = response.data.admins;
      } catch (error) {
        this.$router.push('/login');
      }
    },
    showModal(id) {
      const modalId = id;
      $('#'+modalId).modal('show');
    },
    closeModal(id) {
      const modalId = id;
      $('#'+modalId).modal('hide');
    },
  }
}
</script>
