/**
 * Vue upload picker.
 * 
 * template:
 * <vue-upload-picker
 *  v-model="init.m.pic"
 *  post-uri="/category/ajax-upload"
 *  title="Upload file"
 *  icon='<i class="fas fa-file-import"></i>'
 *  class-name="btn btn-primary btn-sm"></vue-upload-picker>
 */
Vue.component('vue-upload-picker', {
  template: '\
    <div class="vue-upload-picker">\
      <button type="button" :class="className" :disabled="isDisabled" @click="transfer"><span v-html="iconData"></span> {{title}}</button>\
      <input type="file" style="display: none;" @change="upload">\
      <p style="display: block; margin: 10px 0; position: relative; width: 200px;">\
        <a :href="value" target="_blank">\
          <img v-if="isImages" :src="isImages==true ? valueData : \'\'" class="rounded" style="max-width: 200px;">\
          <span v-if="!isImages">{{valueData}}</span>\
        </a>\
        <button type="button" v-if="valueData!=\'\'" @click="close" :disabled="isDisabled" class="close bg-white" aria-label="Close" style="position: absolute; top: 0; right: 0;">\
          <span aria-hidden="true">&times;</span>\
        </button>\
      </p>\
      <input type="hidden" :value="value">\
    </div>\
  \
  ',
  props: {
    value: {
      type: String,
      required: true,
    },
    postUri: {
      type: String,
      required: true,
      default: ''
    },
    title: {
      type: String,
      required: false,
      default: 'Upload file'
    },
    icon: {
      type: String,
      required: false,
      default: '<i class="fas fa-upload"></i>'
    },
    className: {
      type: String,
      required: false,
      default: 'btn btn-outline-primary'
    },
    disabled: {
      type: Boolean,
      required: false,
      default: false
    }
  },
  data() {
    return {
      status: 0, //0:done 1:loading
      extension: ['jpg', 'png', 'jpeg', 'gif', 'bmp', 'webp'],
      isImages: false,
    };
  },
  computed: {
    isDisabled() {
      return this.disabled ? 'disabled' : false;
    },
    iconData() {
      return this.status == 0 ? this.icon : '<i class="fas fa-spin fa-spinner"></i>';
    },
    valueData() {
      if (this.value == '' || this.value == null) {
        return '';
      }

      let ext = this.value.toLowerCase().substr(this.value.lastIndexOf(".") + 1);
      if (this.extension.indexOf(ext) == -1) {
        this.isImages = false;

        return this.value.substr(this.value.lastIndexOf("/") + 1);
      } else {
        this.isImages = true;

        return this.value;
      }
    },
  },
  methods: {
    transfer(event) {
      event.currentTarget.nextElementSibling.click();
    },
    upload(event) {
      var _this = this;
      var file = event.target.files[0];

      
      _this.status = 1;
      var data = new FormData();
      data.append('file', file);
      
      axios.post(_this.postUri, data, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      .then(function (response) {
        if (response.data.status) {
          _this.$emit('input', response.data.path);
        } else {
          alert(response.data.msg);
        }

        _this.status = 0;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    close() {
      this.$emit('input', '');
    }
  }
});
