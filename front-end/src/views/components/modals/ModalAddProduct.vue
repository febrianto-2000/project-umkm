<template>
    <div :id="'modal-add-product'" class="modal" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambahkan Produk</h5>
                    <button type="button" class="close" @click="closeModal('modal-add-product')" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="add-product">
                        <div class="form-group">
                            <label for="nama_produk">Nama Produk</label>
                            <input name="nama_produk" type="text" id="nama_produk" class="form-control" placeholder="Nama Produk" required="">
                            <!--v-if-->
                        </div>
                        <div class="form-group">
                            <label for="kode_produk">Kode</label>
                            <input name="kode_produk" type="text" id="kode_produk" class="form-control" placeholder="Kode" required="">
                            <!--v-if-->
                        </div>
                        <div class="form-group">
                            <label for="harga">Harga</label>
                            <input name="harga" type="number" id="harga" class="form-control" placeholder="Harga" required="">
                            <!--v-if-->
                        </div>
                        <div class="form-group">
                            <!--v-if-->
                            <label for="foto_1">Foto 1</label>
                            <input name="foto_1" type="file" id="foto_1" accept="image/*" class="form-control" placeholder="Foto UMKM 1" required="">
                            <div class="preview-container"><!--v-if-->
                            </div>
                        </div>
                        <div class="form-group">
                            <!--v-if-->
                            <label for="foto_2">Foto 2</label>
                            <input name="foto_2" type="file" id="foto_2" accept="image/*" class="form-control" placeholder="Foto UMKM 1" >
                            <div class="preview-container"><!--v-if-->
                            </div>
                        </div>
                        <div class="form-group">
                            <!--v-if-->
3                           <label for="foto_3">Foto 3</label>
3                           <input name="foto_3" type="file" id="foto_3" accept="image/*" class="form-control" placeholder="Foto UMKM 1" >
                            <div class="preview-container"><!--v-if-->
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal('modal-add-product')" data-dismiss="modal">Tutup</button>
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
            name: 'ModalAddProduct',
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
                    const formElement = document.getElementById('add-product');
                    const formData = new FormData(formElement);
    
                    const formObject = {};
                    formData.forEach((value, key) => {
                        formObject[key] = value;
                    });
    
                    formObject['id_umkm'] = this.data.id;
    
                    axios.post('/api/v1/add-product', formObject, {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                                Authorization: `Bearer ${localStorage.getItem('adminToken')}`
                        }
                    })
                    .then(response => {
                        this.closeModal('modal-add-product');
                        Swal.fire({
                            icon: "success",
                            title: "Data Added"
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
    