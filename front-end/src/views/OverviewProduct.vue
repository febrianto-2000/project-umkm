<template>
  <div>
    <Title :titleText="'Produk ' + data.nama_produk" />

    <div class="card position-relative mb-3">
      <div class="card-body">
        <div class="row">
          <div class="col-lg-4 col-md-12 mb-4 mb-lg-0" v-if="data.foto_1">
            <div>
              <img :src="data.foto_1" alt="Gambar 1" class="w-100 shadow-1-strong rounded mb-4" style="width:100%; max-width: 150px;">
            </div>
          </div>
        
          <div class="col-lg-4 mb-4 mb-lg-0" v-if="data.foto_2">
            <div>
              <img :src="data.foto_2" alt="Gambar 2" class="w-100 shadow-1-strong rounded mb-4" style="width:100%; max-width: 150px;">
            </div>
          </div>
        
          <div class="col-lg-4 mb-4 mb-lg-0" v-if="data.foto_3">
            <div>
              <img :src="data.foto_3" alt="Gambar 3" class="w-100 shadow-1-strong rounded mb-4" style="width:100%; max-width: 150px;">
            </div>
          </div>
        </div>
      </div>
    </div>
    

    <div class="card position-relative">
      <div class="card-body">
        <div class="row"> 
          <div class="col-md-12 col-xl-9">
            <div class="row">
              <div class="col-md-6 col-xl-7">
                <div class="table-responsive mb-3 mb-md-0">
                  <ModalEditProduct :data="data" />
                  <ModalDeleteConfirmationProduct :data="data" />
                  <ModalAddProduct :data="data" />
                  <div>
                    <button type="button" @click="showModal('modal-edit-product')" class="btn btn-outline-secondary btn-icon-text mx-2">
                      <i class="ti-pencil btn-icon-prepend"></i>
                      Edit Data
                    </button>
                    <button type="button" @click="showModal('modal-delete-confirmation-product')" class="btn btn-outline-warning btn-icon-text mx-2">
                      <i class="ti-trash btn-icon-prepend"></i>
                      Delete
                    </button>
                  </div>
                  <table class="table table-borderless report-table">
                    <tbody><tr>
                      <td class="text-muted">Kode Produk</td>
                      <td><h5 class="font-weight-bold mb-0">{{ data.kode_produk }}</h5></td>
                    </tr>
                    <tr>
                      <td class="text-muted">Harga</td>
                      <td><h5 class="font-weight-bold mb-0">{{ data.harga }}</h5></td>
                    </tr>
                  </tbody></table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import axios from '@/utils/axios'; 
import Title from './components/Title.vue';
import ButtonToolbarWithModal from './components/ButtonToolbarWithModal.vue';
import ModalEditProduct from './components/modals/ModalEditProduct.vue';
import ModalDeleteConfirmationProduct from './components/modals/ModalDeleteConfirmationProduct.vue';
import ModalAddProduct from './components/modals/ModalAddProduct.vue';

export default {
  name: 'OverviewProduct',
  components: {
    Title,
    ButtonToolbarWithModal,
    ModalEditProduct,
    ModalDeleteConfirmationProduct,
    ModalAddProduct
  },
  data() {
    return {
      products: [],
      data: {
        "kode_produk": "Loading",
        "nama_produk": "Loading",
        "harga": "Loading",
      }
    };
  },
  mounted() {
    const currentURL = window.location.href;
    const urlParts = currentURL.split('/');
    const id = urlParts[urlParts.length - 1];
    this.data['id'] = id;
    const adminToken = localStorage.getItem('adminToken');
    this.takeProduct(id,adminToken);
    // console.log(this.data_product);
  },
  methods: {
    showModal(id) {
      const modalId = id;
      $('#'+modalId).modal('show');
    },
    closeModal(id) {
      const modalId = id;
      $('#'+modalId).modal('hide');
    },
    async takeProduct(id,adminToken = '') {
      try {
        const response = await axios.post('/api/v1/take-product', { id }, {
              headers: {
                Authorization: `Bearer ${adminToken}`
              }
            });

        if(response.data.produk.foto_1) {
          response.data.produk.foto_1 = 'http://localhost:8000/storage/'+response.data.produk.foto_1;
        }
        if(response.data.produk.foto_2) {
          response.data.produk.foto_2 = 'http://localhost:8000/storage/'+response.data.produk.foto_2;
        }
        if(response.data.produk.foto_3) {
          response.data.produk.foto_3 = 'http://localhost:8000/storage/'+response.data.produk.foto_3;
        }
        this.data = response.data.produk;
        this.data['id'] = id;
      } catch (error) {
        console.log('error')
        this.$router.push('/login');
      }
    },
  }
}
</script>
