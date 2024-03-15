<template>
    <div class="content-wrapper d-flex align-items-center auth px-0">
      <div class="row w-100 mx-0">
        <div class="col-lg-4 mx-auto">
          <div class="auth-form-light text-left py-5 px-4 px-sm-5">
            <h4>Login as an Admin</h4>
            <form class="pt-3" id="login-form">
              <div class="form-group">
                <input type="email" class="form-control form-control-lg" name="username" id="exampleInputEmail1" placeholder="Username">
              </div>
              <div class="form-group">
                <input type="password" class="form-control form-control-lg" name="password" id="exampleInputPassword1" placeholder="Password">
              </div>
              <div class="mt-3">
                <a class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" @click="submitForm">LOG IN</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
import axios from '@/utils/axios'; 
import Swal from '@/utils/swal'; 

export default {
  name: 'Login',  
  methods: {
    async submitForm() {
        const formElement = document.getElementById('login-form');
        const formData = new FormData(formElement);

        const formObject = {};
        formData.forEach((value, key) => {
            formObject[key] = value;
        });

        console.log(formObject)
        const resp = await axios.post('/api/v1/login-admin', formObject, {
            headers: {
                'Content-Type': 'application/json'
            }
        });

        console.log(resp);

        if(resp.data.status == 1) {
          localStorage.setItem('adminToken', resp.data.token);
          window.location.href = '/dashboard';
        } else {
          Swal.fire({
            icon: "error",
            title: "Login Failed"
          });
        }

        
        // .then(response => {
        //   localStorage.setItem('adminToken', token);
        //   window.location.href = '/dashboard';
        // })
        // .catch(error => {
        //     console.error('Error:', error);
        //     Swal.fire({
        //         icon: "error",
        //         title: "Login Failed"
        //     });
        // });
    }
  }
}
</script>
