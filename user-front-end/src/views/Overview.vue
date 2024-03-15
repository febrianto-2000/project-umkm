<template>
  <div class="container" style="margin-bottom:100px">
    <div class="row">
        <div class="col-lg-6">
            <div class="left-content">
                <h2>{{ data.nama_umkm }}</h2>
                <span>{{ data.alamat }}, {{ data.kota }}, {{ data.provinsi }}</span>
                <p>
                  {{ data.deskripsi }}
                </p>
            </div>
        </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="section-heading">
                <h2>Our Latest Products</h2>
                <span>Check out all of our products.</span>
            </div>
        </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div v-for="(product, index) in products" :key="index" class="col-lg-4">
        <div class="item">
          <div class="thumb">
            <img :src="currentImageUrl(product)" alt="" style="width:300px">
          </div>
          <div class="down-content">
            <h4>{{ product.nama_produk }}</h4>
            <span>{{ formatCurrency(product.harga) }}</span>
            <ul class="stars">
              <li v-for="n in 5" :key="n" :class="{ 'fa fa-star': n <= product.rating, 'fa fa-star-o': n > product.rating }"></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="section-heading">
                <h2>Our Latest Products</h2>
                <span>Check out all of our products.</span>
            </div>
        </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div v-for="(product, index) in products" :key="index" class="col-lg-4">
        <div class="item">
          <div class="thumb">
            <img :src="currentImageUrl(product)" alt="" style="width:300px">
          </div>
          <div class="down-content">
            <h4>{{ product.nama_produk }}</h4>
            <span>{{ formatCurrency(product.harga) }}</span>
            <ul class="stars">
              <li v-for="n in 5" :key="n" :class="{ 'fa fa-star': n <= product.rating, 'fa fa-star-o': n > product.rating }"></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>

  </template>
  
  <script>
    import axios from '@/utils/axios'; 
  export default {
    name: 'Overview',
    data() {
    return {
          data: [],
          products: [],
          currentImageIndex: 0,
        };
    },
    mounted() {
        const currentURL = window.location.href;
        const urlParts = currentURL.split('/');
        const id = urlParts[urlParts.length - 1];
        this.data['id'] = id;
        this.takeUMKM(id);
        this.fetchProduct(id);
    },
    methods: {
        getImageUrl(product) {
          if (product.foto_1) {
            return 'http://localhost:8000/storage/' + product.foto_1;
          } else if (product.foto_2) {
            return 'http://localhost:8000/storage/' + product.foto_2;
          } else if (product.foto_3) {
            return 'http://localhost:8000/storage/' + product.foto_3;
          } else {
            // Jika tidak ada foto yang tersedia, ganti dengan gambar default
            return 'http://localhost:8000/storage/default.jpg';
          }
        },
        async takeUMKM(id) {
          try {
            const response = await axios.post('/api/v1/take-umkm-public', { id }, {
                headers: {
                  headers: {
                    'Content-Type': 'application/json'
                  }
                }
              });
              this.data = response.data.umkm;
          } catch (error) {
              console.error('Error fetching UMKMs:', error);
          }
        },
        async fetchProduct(id) {
            try {
              const response = await axios.post('/api/v1/fetch-product-by-umkm-id', { id }, {
                  headers: {
                    headers: {
                      'Content-Type': 'application/json'
                    }
                  }
                });
                this.products = response.data.products;
                console.log(response)
            } catch (error) {
                console.error('Error fetching UMKMs:', error);
            }
        },
        formatCurrency(amount) {
          // Mengubah nilai float menjadi format mata uang
          return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount);
        },
        currentImageUrl(product) {
          // Fungsi ini akan mengembalikan URL gambar saat ini sesuai dengan indeks yang diatur
          const availableImages = ['foto_1', 'foto_2', 'foto_3'].filter(key => product[key]);
          if (availableImages.length === 0) {
            return 'http://localhost:8000/storage/default.jpg';
          }
          return 'http://localhost:8000/storage/' + product[availableImages[this.currentImageIndex % availableImages.length]];
        },
    }
    
  }
  </script>
  