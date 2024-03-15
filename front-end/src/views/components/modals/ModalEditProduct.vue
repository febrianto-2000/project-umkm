<template>
    <div :id="'modal-edit-product'" class="modal" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Product {{ data.nama_produk}}</h5>
                    <button type="button" class="close" @click="closeModal('modal-edit-product')" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="edit-data-product">
                        <div class="form-group">
                            <label for="nama_produk">Nama Produk</label>
                            <input name="nama_produk" type="text" id="nama_produk" class="form-control" placeholder="Nama Produk" :value="data.nama_produk" required="">
                            <!--v-if-->
                        </div>
                        <div class="form-group">
                            <label for="kode_produk">Kode</label>
                            <input name="kode_produk" type="text" id="kode_produk" class="form-control" placeholder="Kode" :value="data.kode_produk" required="">
                            <!--v-if-->
                        </div>
                        <div class="form-group">
                            <label for="harga">Harga</label>
                            <input name="harga" type="number" id="harga" class="form-control" placeholder="Harga" :value="data.harga" required="">
                            <!--v-if-->
                        </div>
                        <div class="form-group">
                            <!--v-if-->
                            <label for="foto_1">Foto 1</label>
                            <input name="foto_1" type="file" id="foto_1" accept="image/*" class="form-control" placeholder="Foto Product 1" required="">
                            <div class="preview-container"><!--v-if-->
                            </div>
                        </div>
                        <div class="form-group">
                            <!--v-if-->
                            <label for="foto_2">Foto 2</label>
                            <input name="foto_2" type="file" id="foto_2" accept="image/*" class="form-control" placeholder="Foto Product 1" >
                            <div class="preview-container"><!--v-if-->
                            </div>
                        </div>
                        <div class="form-group">
                            <!--v-if-->
                           <label for="foto_3">Foto 3</label>
                          <input name="foto_3" type="file" id="foto_3" accept="image/*" class="form-control" placeholder="Foto Product 1" >
                            <div class="preview-container"><!--v-if-->
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal('modal-edit-product')" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" @click="submitForm">Lanjutkan</button>
                </div>
            </div>
        </div>
    </div>
    </template>
    <script>
        import axios from '@/utils/axios'; 
        import Swal from '@/utils/swal'; 
    
        export default {
            name: 'ModalEditProduct',
            props: {
                data: Object
            },
            mounted() {
                console.log(this.data)
            },
            methods: {
                closeModal(id) {
                    const modalId = id;
                    $('#'+modalId).modal('hide');
                },
                submitForm() {
                    const formElement = document.getElementById('edit-data-product');
                    const formData = new FormData(formElement);
    
                    const formObject = {};
                    formData.forEach((value, key) => {
                        formObject[key] = value;
                    });
    
                    formObject['id'] = this.data.id;
    
                    axios.post('/api/v1/edit-product', formObject, {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                            Authorization: `Bearer ${localStorage.getItem('adminToken')}`
                        }
                    })
                    .then(response => {
                        this.closeModal('modal-edit-product');
                        Swal.fire({
                            icon: "success",
                            title: "Data Updated"
                        });
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }
            }
        }
    </script>
    