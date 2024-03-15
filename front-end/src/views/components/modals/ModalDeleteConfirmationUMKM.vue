<template>
    <div id="modal-delete-confirmation-umkm" class="modal" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Penghapusan</h5>
                    <button type="button" class="close" @click="closeModal('modal-delete-confirmation-umkm')" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data <strong>{{ data.nama_umkm }}</strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal('modal-delete-confirmation-umkm')" data-dismiss="modal">Tutup</button>
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
    name: 'ModalDeleteConfirmationUMKM',
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

            axios.post('/api/v1/delete-umkm', param, {
                headers: {
                    'Content-Type': 'application/json',
                    Authorization: `Bearer ${localStorage.getItem('adminToken')}`
                }
            })
            .then(response => {
                this.closeModal('modal-delete-confirmation-umkm');
                Swal.fire({
                    icon: "success",
                    title: "Data Deleted"
                });
                
                window.location.href = '/list-umkm';
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    }
}
</script>
