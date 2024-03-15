<template>
    <div id="modal-delete-confirmation-admin" class="modal" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Penghapusan</h5>
                    <button type="button" class="close" @click="closeModal('modal-delete-confirmation-admin')" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data <strong>{{ data.username }}</strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal('modal-delete-confirmation-admin')" data-dismiss="modal">Tutup</button>
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
            param['username'] = this.data.username;

            axios.post('/api/v1/delete-admin', param, {
                headers: {
                    'Content-Type': 'application/json',
                    Authorization: `Bearer ${localStorage.getItem('adminToken')}`
                }
            })
            .then(response => {
                this.closeModal('modal-delete-confirmation-admin');
                Swal.fire({
                    icon: "success",
                    title: "Data Deleted"
                });
                window.location.href = '/list-admin';
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    }
}
</script>
