<template>
  <div class="modal" :id="modalId" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ modalTitle }}</h5>
          <button type="button" class="close" :id="closeButtonId" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form :id="formId">
            <div v-for="(value, key) in formData" :key="key" class="form-group">
              <template v-if="value.type === 'text' || value.type === 'password' || value.type === 'email' || value.type=== 'number'">
                <label :for="key">{{ value.label }}</label>
                <input :name="key" :type="value.type" :id="key" class="form-control" :placeholder="value.label" :required="value.is_required"/>
              </template>
              <template v-if="value.type === 'file'">
                <label :for="key">{{ value.label }}</label>
                <input @change="handleFileChange($event, key)" :name="key" :type="value.type" :id="key" :accept="value.file_type+'/'+'*'" class="form-control" :placeholder="value.label" :required="value.is_required" />
                <div class="preview-container">
                  <img :src="previewImages[key]" v-if="previewImages[key]" :key="key" alt="Preview" class="preview-image" style="width:100px;margin-top:20px"/>
                </div>
              </template>
              <template v-else-if="value.type === 'checkbox'">
                <label :for="key">{{ value.label }}</label> : 
                <input :name="key" :type="value.type" :id="key" class="form-check-input" :required="value.is_required"/>
              </template>
              <template v-else-if="value.type === 'select'">
                <label :for="key">{{ value.label }}</label>
                <select :name="key"  :id="key" class="form-control" :required="value.is_required"> 
                  <option v-for="option in value.options" :value="option.name">{{ option.value }}</option>
                </select>
              </template>
              <template v-else-if="value.type === 'textarea'">
                <label :for="key">{{ value.label }}</label>
                <textarea :name="key" :id="key" class="form-control" :placeholder="value.label" :required="value.is_required"></textarea>
              </template>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" :id="closeButtonIdFooter" data-dismiss="modal">Tutup</button>
          <button type="button"  class="btn btn-primary" @click="validateAndSubmit">Lanjutkan</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from '@/utils/axios'; 
import Swal from '@/utils/swal'; 

export default {
  components : {
  },
  name: 'ButtonToolbarWithModal',
  props: {
    modalTitle: String,
    buttonTitle: String,
    tiIcon: String,
    targetURL: String,
    contentType: String,
    formData: Object
  },
  data() {
    return {
      modalId: '',
      formId: '',
      buttonId: '',
      closeButtonId: '',
      closeButtonIdFooter: '',
      previewImages: [],
      cities: [
        {
          "id": "AX", "name": "Åland Islands", "continent": "Europe", "currencyId": "EUR", "phoneCode": "35818", "flag": "🇦🇽"
        }
      ],
      provinces: [        
        {
          "id": "AX", "name": "Åland Islands", "continent": "Europe", "currencyId": "EUR", "phoneCode": "35818", "flag": "🇦🇽"
        }]
    }
  },
  mounted() {
    const customToolbar = document.getElementById('custom-toolbar');
    const spacing = '\u00A0\u00A0\u00A0';
    const modalId = this.buttonTitle.replace(/\s+/g, '-').toLowerCase() + '-modal';
    const formId = this.buttonTitle.replace(/\s+/g, '-').toLowerCase() + '-form';
    const buttonId = this.buttonTitle.replace(/\s+/g, '-').toLowerCase() + '-button';
    const closeButtonId = this.buttonTitle.replace(/\s+/g, '-').toLowerCase() + '-close-button';
    const closeButtonIdFooter = this.buttonTitle.replace(/\s+/g, '-').toLowerCase() + '-close-button-footer';


    this.modalId = modalId;
    this.formId = formId;
    this.buttonId = buttonId;
    this.closeButtonId = closeButtonId;
    this.closeButtonIdFooter = closeButtonIdFooter;


    async function fetchCitiesByProvince() {
      const token = localStorage.getItem('adminToken');
    const resp = await axios.post('/api/v1/get-city-provence', null, {
        headers: {
            'Authorization': `Bearer ${token}`
        }
    });
    return resp.data.cities_and_provinces.map(city => ({
        value: city.city_id,
        text: city.city_name + ', ' + city.prov_name
    }));
    }

    if (customToolbar) {
      const buttonHtml = `
          <li>
          <button type="button" id="${this.buttonId}" class="btn btn-outline-primary btn-icon-text" style="margin-left:20px">
              <i class="${this.tiIcon} btn-icon-prepend"></i>${spacing}${this.buttonTitle}
          </button>
          </li>
      `;

      customToolbar.innerHTML = buttonHtml;
    }

    $('#' + buttonId).on('click', async function() {
      $('#' + modalId).modal('show');
      
      
      const kotaInput = $('#'+formId+' select[name="kota"]');


      if (kotaInput.length > 0) {
        const cities = await fetchCitiesByProvince();
        kotaInput.selectize({
          options: cities,
          valueField: 'value',
          labelField: 'text',
          searchField: 'text',
          create: false, 
          placeholder: 'Pilih kota'
        });
      }

      $('#' + closeButtonId).on('click', function() {
          $('#' + modalId).modal('hide');
      });
  
      $('#' + closeButtonIdFooter).on('click', function() {
          $('#' + modalId).modal('hide');
      });
      
    });

    
  },
  methods: {
    handleFileChange(event, key) {
      const selectedFile = event.target.files[0];
      const imageURL = URL.createObjectURL(selectedFile);
      this.previewImages[key] = imageURL;
    },
    validateAndSubmit() {
      const formElement = document.getElementById(this.formId);
      if (!formElement.checkValidity()) {
        alert('Mohon lengkapi semua kolom yang dibutuhkan.');
        return;
      }
      
      const form_params = new FormData(formElement);
      for (const [key, value] of form_params.entries()) {
        const inputElement = formElement.querySelector(`[name="${key}"]`);
        if (inputElement && inputElement.type === 'checkbox') {
          form_params.set(key, inputElement.checked ? 1 : 0);
        }
      }
      axios.post(this.targetURL, form_params, {
        headers: {
          'Content-Type': this.contentType,
          Authorization: `Bearer ${localStorage.getItem('adminToken')}`
        }
      })
      .then(response => {
        window.location.reload();
        Swal.fire({
          icon: "success",
          title: "Data Inserted"
        });
      })
      .catch(error => {
        console.error('Error:', error);
      });
    },
    showModal(id) {
      const modalId = id;
      $('#'+modalId).modal('show');
    },
    closeModal(id) {
      const modalId = id;
      $('#'+modalId).modal('hide');
    },
  }
}
</script>