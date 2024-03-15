<template>
  <div>
    <ButtonToolbarWithModal :formData="umkmformdata" targetURL="/api/v1/add-umkm" contentType="multipart/form-data" buttonTitle="Tambahkan UMKM" tiIcon="ti-user" modalTitle="Formulir Tambah Rumah" />
    <Title :titleText="data.nama_umkm" />

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
                  <ModalEditUMKM :data="data" />
                  <ModalDeleteConfirmationUMKM :data="data" />
                  <ModalAddProduct :data="data" />
                  <div>
                    <button type="button" @click="showModal('modal-'+data.id+'-edit-umkm')" class="btn btn-outline-secondary btn-icon-text mx-2">
                      <i class="ti-pencil btn-icon-prepend"></i>
                      Edit Data
                    </button>
                    <button type="button" @click="showModal('modal-delete-confirmation-umkm')" class="btn btn-outline-warning btn-icon-text mx-2">
                      <i class="ti-trash btn-icon-prepend"></i>
                      Delete
                    </button>
                  </div>
                  <table class="table table-borderless report-table">
                    <tbody><tr>
                      <td class="text-muted">Pemilik</td>
                      <td><h5 class="font-weight-bold mb-0">{{ data.nama_pemilik }}</h5></td>
                    </tr>
                    <tr>
                      <td class="text-muted">Nama Usaha</td>
                      <td><h5 class="font-weight-bold mb-0">{{ data.nama_umkm }}</h5></td>
                    </tr>
                    <tr>
                      <td class="text-muted">Lokasi</td>
                      <td><h5 class="font-weight-bold mb-0">{{ data.alamat }}, {{ data.kota }}, {{ data.provinsi }}</h5></td>
                    </tr>
                  </tbody></table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card mt-3">
      <div class="card-body">
        
        <button type="button" @click="showModal('modal-add-product')" class="btn btn-outline-primary btn-icon-text mx-2">
          <i class="ti-trash btn-icon-prepend"></i>
          Add Product
        </button>
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Harga</th>
              </tr>
            </thead>
            <tbody v-if="products.length > 0">
              <tr v-for="product in products" :key="product.id" @click="redirectToProductOverview(product.id)">
                <td>{{ product.kode_produk }}</td>
                <td>{{ product.nama_produk }}</td>
                <td>{{ product.harga }}</td>
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
import ModalEditUMKM from './components/modals/ModalEditUMKM.vue';
import ModalDeleteConfirmationUMKM from './components/modals/ModalDeleteConfirmationUMKM.vue';
import ModalAddProduct from './components/modals/ModalAddProduct.vue';

export default {
  name: 'OverviewUMKM',
  components: {
    Title,
    ButtonToolbarWithModal,
    ModalEditUMKM,
    ModalDeleteConfirmationUMKM,
    ModalAddProduct
  },
  data() {
    return {
      products: [],
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
      data: {
        "nama_umkm": "Loading",
        "deskripsi": "JLoading",
        "alamat": "Loading",
        "kota": "Loading",
        "provinsi": "Loading",
        "nama_pemilik": "Loading",
        "personal_kontak": "Loading",
        "foto_1": '',
        "foto_2": '',
        "foto_3": ''
      }
    };
  },
  mounted() {
    const currentURL = window.location.href;
    const urlParts = currentURL.split('/');
    const id = urlParts[urlParts.length - 1];
    this.data['id'] = id;
    const adminToken = localStorage.getItem('adminToken');
    this.fetchProducts(this.data['id'],adminToken);
    this.takeUMKM(id,adminToken);
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
    async takeUMKM(id,adminToken = '') {
      try {
        const response = await axios.post('/api/v1/take-umkm', { id }, {
              headers: {
                Authorization: `Bearer ${adminToken}`
              }
            });
        if(response.data.umkm.foto_1) {
          response.data.umkm.foto_1 = 'http://localhost:8000/storage/'+response.data.umkm.foto_1;
        }
        if(response.data.umkm.foto_2) {
          response.data.umkm.foto_2 = 'http://localhost:8000/storage/'+response.data.umkm.foto_2;
        }
        if(response.data.umkm.foto_3) {
          response.data.umkm.foto_3 = 'http://localhost:8000/storage/'+response.data.umkm.foto_3;
        }
        this.data = response.data.umkm;
        this.data['id'] = id;
      } catch (error) {
        console.log('error')
        this.$router.push('/login');
      }
    },
    async fetchProducts(id,adminToken = '') {
      try {
        const response = await axios.post('/api/v1/fetch-products', { id }, {
              headers: {
                Authorization: `Bearer ${adminToken}`
              }
            });
        console.log(response.data.products);
        this.products = response.data.products;
      } catch (error) {
        console.log('error')
        this.$router.push('/login');
      }
    },
    redirectToProductOverview(pid) {
      this.$router.push(`/product-overview/${pid}`);
    }
  }
}
</script>
