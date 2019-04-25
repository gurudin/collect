/**
 * Created by gaoxiang on 26/03/2019.
 */
Vue.component('vueForm', {
  template: '<form @submit="checkForm">'
    + '<div class="form-group" v-for="(opt,key) in options" v-if="opt.type != \'slot\' && opt.type != \'submit\'" :class="{\'has-error\':errors[key]}">'
      + '<label class="control-label">{{opt.label}}</label>'
      
      /** Input text/email */
      + '<input type="text" v-if="isShow(\'input\', opt)" :required="opt.required" v-model.trim="modelData[key]" :class="opt.class" :style="opt.style" :disabled="opt.disabled" :maxlength="opt.maxlength" :minlength="opt.minlength" :placeholder="opt.placeholder" class="form-control">'

      /** Input textarea */
      + '<textarea v-if="isShow(\'textarea\', opt)" :required="opt.required" v-model.trim="modelData[key]" :class="opt.class" :maxlength="opt.maxlength" :rows="opt.rows ? opt.rows : 3" :minlength="opt.minlength" :placeholder="opt.placeholder" class="form-control"></textarea>'
      
      /** Input number */
      + '<input type="number" v-if="isShow(\'number\', opt)" :min="opt.min" :max="opt.max" :required="opt.required" v-model.number="modelData[key]" :class="opt.class" :style="opt.style" :disabled="opt.disabled" :maxlength="opt.maxlength" :minlength="opt.minlength" :placeholder="opt.placeholder" class="form-control">'

      /** Radio */
      + '<div class="radio" v-if="isShow(\'radio\', opt)" v-for="(radio,radioKey) in opt.options">'
        + '<label>'
          + '<input type="radio" :name="key" :value="radioKey" v-model="modelData[key]">'
          + '{{radio}}'
        + '</label>'
      + '</div>'

      /** Select */
      + '<select class="form-control" v-if="isShow(\'select\', opt)" v-model="modelData[key]" :multiple="opt.multiple" :class="opt.class" :style="opt.style">'
        + '<option v-for="item in opt.options" :value="item[opt[\'k-field\']]">{{item[opt[\'v-field\']]}}</option>'
      + '</select>'

      /** Checkbox */
      + '<div class="checkbox" v-if="isShow(\'checkbox\', opt)" v-for="(check,checkKey) in opt.options">'
        + '<label>'
          + '<input type="checkbox" :value="check[opt[\'k-field\']]" v-model="modelData[key]">'
          + '{{check[opt[\'v-field\']]}}'
        + '</label>'
      + '</div>'

      /** File */
      + '<vue-upload-picker v-if="isShow(\'file\', opt)" v-model="modelData[key]" :title="opt.dataName" :post-uri="opt.dataUri" data-picker-zise="btn" :icon="opt.dataIcon"/>'

      + '<small class="text-danger">{{errors[key]}}</small>'
    + '</div>'

    /** Slot */
    + '<span v-else-if="opt.type == \'slot\'"><slot :name="opt.name"></slot></span>'

    + '<button v-else-if="opt.type == \'submit\'" type="submit" class="btn" :class="opt.class" v-html="opt.label" ref="submit"></button>'
  + '</form>',
  props: {
    model: {
      type: Object,
      required: true,
    },
    options: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      types: ['text', 'email', 'number', 'radio', 'select', 'checkbox', 'file', 'textarea'],
      modelData: this.model,
      errors: {},
      validator: {
        number: '^[0-9]*$',
        email: "^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$",
      }
    };
  },
  methods: {
    isShow(type, opt) {
      switch (true) {
        case type == 'input' && ['text', 'email'].indexOf(opt.type) > -1:
            return true;
          break;
        case type == 'number' && ['number'].indexOf(opt.type) > -1:
            return true;
          break;
        case type == 'radio' && ['radio'].indexOf(opt.type) > -1:
            return true;
          break;
        case type == 'select' && ['select'].indexOf(opt.type) > -1:
            return true;
          break;
        case type == 'checkbox' && ['checkbox'].indexOf(opt.type) > -1:
            return true;
          break;
        case type == 'file' && ['file'].indexOf(opt.type) > -1:
            return true;
          break;
        case type == 'textarea' && ['textarea'].indexOf(opt.type) > -1:
            return true;
          break;
        default:
          break;
      }
    },
    getMsg(option, key, len) {
      if (typeof option.errors == 'undefined') {
        if (key == 'required') {
          var msg = 'The ' + option.label + ' field is required.';
        }
        if (key == 'maxlength') {
          var msg = 'The ' + option.label + ' may not be greater than ' + len + ' characters.';
        }
        if (key == 'minlength') {
          var msg = 'The ' + option.label + ' must be at least ' + len + ' characters.';
        }
        if (key == 'email') {
          var msg = 'The ' + option.label + ' must be a valid email address.';
        }
      } else {
        var msg = typeof option.errors[key] != 'undefined' ? option.errors[key] : option.errors['*'];
      }

      return msg;
    },
    checkForm(e) {
      this.errors = {};

      var options = this.options;
      var _this = this;
      for (const key in options) {
        if (this.types.indexOf(options[key].type) > -1) {
          // required
          if (options[key].required) {
            if (_this.modelData[key] == '') {
              _this.errors[key] = _this.getMsg(options[key], 'required');
            }
          }

          // maxlength
          if (options[key].maxlength) {
            if (_this.modelData[key].length > options[key].maxlength) {
              _this.errors[key] = _this.getMsg(options[key], 'maxlength', options[key].maxlength);
            }
          }

          // minlength
          if (options[key].minlength) {
            if (_this.modelData[key].length < options[key].minlength) {
              _this.errors[key] = _this.getMsg(options[key], 'minlength', options[key].minlength);
            }
          }

          // email
          if (_this.modelData[key] != '' && options[key].type == 'email') {
            if (!new RegExp(_this.validator.email).test(_this.modelData[key])) {
              _this.errors[key] = _this.getMsg(options[key], 'email');
            }
          }
          
          // refresh
          if (typeof _this.errors[key] != 'undefined') {
            _this.$set(_this.errors, key, _this.errors[key]);
          }
        }
      }
      
      if (JSON.stringify(this.errors) === '{}') {
        this.options['btnSubmit'].func(this.$refs.submit);
      }

      e.preventDefault();
    }
  }
});
