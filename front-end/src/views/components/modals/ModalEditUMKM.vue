<template>
    <div :id="'modal-'+data.id+'-edit-umkm'" class="modal" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Profil usaha {{ data.nama_umkm}}, {{ data.nama_pemilik}}</h5>
                    <button type="button" class="close" @click="closeModal('modal-'+data.id+'-edit-umkm')" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="edit-data-umkm">
                        <div class="form-group">
                            <label for="nama_umkm">Nama Umkm</label>
                            <input name="nama_umkm" type="text" id="nama_umkm" class="form-control" placeholder="Nama Umkm" :value="data.nama_umkm" required="">
                            <!--v-if-->
                        </div>
                        <div class="form-group">
                            <label for="nama_pemilik">Nama Pemilik</label>
                            <input name="nama_pemilik" type="text" id="nama_pemilik" class="form-control" placeholder="Nama Pemilik" :value="data.nama_pemilik" required="">
                            <!--v-if-->
                        </div>
                        <div class="form-group">
                            <label for="personal_kontak">Personal Kontak (e-mail / whatsapp)</label>
                            <input name="personal_kontak" type="text" id="personal_kontak" class="form-control" placeholder="Personal Kontak (e-mail / whatsapp)" :value="data.personal_kontak" required="">
                            <!--v-if-->
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <input name="deskripsi" type="text" id="deskripsi" class="form-control" :value="data.deskripsi" placeholder="Deskripsi">
                            <!--v-if-->
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <input name="alamat" type="text" id="alamat" class="form-control" placeholder="Alamat" :value="data.alamat" required="">
                            <!--v-if-->
                        </div>
                        <div class="form-group">
                            <label for="kota-selectized">Kota <small>(Jika tidak ingin mengganti maka biarkan kosong)</small></label>
                            <select name="kota" id="kota" class="form-control selectized" tabindex="-1" style="display: none;">
                                <option value="" selected="selected"></option>
                            </select>
                            <div class="form-control selectize-control single">
                                <div class="selectize-input items required invalid not-full has-options">
                                    <input type="select-one" autocomplete="new-password" autofill="no" tabindex="" id="kota-selectized" placeholder="Pilih kota" required="" style="width: 60.75px;">
                                </div>
                                <div class="single selectize-dropdown form-control" style="display: none; width: 420.8px; top: 49.6px; left: 22px;">
                                    <div class="selectize-dropdown-content" tabindex="-1"></div>
                                </div>
                            </div>
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
                            <input name="foto_2" type="file" id="foto_2" accept="image/*" class="form-control" placeholder="Foto UMKM 2" >
                            <div class="preview-container"><!--v-if-->
                            </div>
                        </div>
                        <div class="form-group">
                           <label for="foto_3">Foto 3</label>
                           <input name="foto_3" type="file" id="foto_3" accept="image/*" class="form-control" placeholder="Foto UMKM 3" >
                            <div class="preview-container">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal('modal-'+data.id+'-edit-umkm')" data-dismiss="modal">Tutup</button>
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
            name: 'ModalEditUMKM',
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
                    const formElement = document.getElementById('edit-data-umkm');
                    const formData = new FormData(formElement);
    
                    const formObject = {};
                    formData.forEach((value, key) => {
                        formObject[key] = value;
                    });
    
                    formObject['id'] = this.data.id;
    
                    axios.post('/api/v1/edit-umkm', formObject, {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                            Authorization: `Bearer ${localStorage.getItem('adminToken')}`
                        }
                    })
                    .then(response => {
                        this.closeModal('modal-'+this.data.id+'-edit-umkm');
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
    