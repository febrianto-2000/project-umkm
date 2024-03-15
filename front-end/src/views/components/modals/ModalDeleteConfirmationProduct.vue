<template>
    <div id="modal-delete-confirmation-product" class="modal" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Penghapusan</h5>
                    <button type="button" class="close" @click="closeModal('modal-delete-confirmation-product')" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus produk ini <strong>{{ data.nama_produk }}</strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal('modal-delete-confirmation-product')" data-dismiss="modal">Tutup</button>
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
    name: 'ModalDeleteConfirmationAdmin',
    props: {
        data: Object
    },
    methods: {
        closeModal(id) {
            const modalId = id;
            $('#'+modalId).modal('hide');
        },
        submitForm() {
            let param = {};
            param['id'] = this.data.id;

            axios.post('/api/v1/delete-product', param, {
                headers: {
                    'Content-Type': 'application/json',
                    Authorization: `Bearer ${localStorage.getItem('adminToken')}`
                }
            })
            .then(response => {
                this.closeModal('modal-delete-confirmation-product');
                Swal.fire({
                    icon: "success",
                    title: "Data Deleted"
                });
                console.log(response)
                // window.location.href = '/product-overview/'+response.data.produk.id_umkm;
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    }
}
</script>
