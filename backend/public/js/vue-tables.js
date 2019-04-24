/**
 * Created by gaoxiang on 26/03/2019.
 * 
 * @param {object|required} headings {id: 'ID', name: '名称'}
 * @param {array|required} options data source
 * @param {object} settings
 * 
 * @template
 *  <vue-tables
 *   :headings="headings"
 *   :settings="settings"
 *   :options="options">
 *
 *   <form class="form-inline">
 *    <div class="form-group">
 *     <label>是否可用</label>
 *     <select class="form-control input-sm">
 *      <option value="1">仅可用</option>
 *      <option value="0">已停用</option>
 *     </select>
 *    </div> 
 *   </form>
 *  </vue-tables>
 * 
 * @example options: {
 *  'msgUrl': {
 *    type: 'image',
 *    target: '_blank',
 *  },
 *  'data.type': {
 *    type: 'text',
 *    options: {'normal': '常规礼物', 'lucky gift': '幸运礼物'},
 *  },
 *  'data.values': {
 *    type: 'join',
 *    text: '%s MCion'
 *  },
 *  'region': {
 *    type: 'array',
 *    text: '<p style="margin-bottom: 2px;"><span class="label label-info">%s</span></p>'
 *  },
 *  'updated_at': {
 *    type: 'callback',
 *    func: this.updateCall,
 *  },
 *  'enabled': {
 *    type: 'toggle-button',
 *    options: [
 *      {label: "Enable", value: 1, "checked": "success"},
 *      {label: "Disable", value: 0, checked: "warning"}
 *    ],
 *    func: this.enabledCall,
 *  },
 *  'action': {
 *    type: 'action',
 *    actions: {
 *      'copy': {text: '复制', class: 'btn-primary', func: this.actionCall},
 *      'edit': {icon: 'fa fa-pencil-square-o', class: 'btn-warning', func: this.actionCall},
 *    }
 *  },
 * };
 */
Vue.component('vueTables', {
  template: '<table class="table table-hover">'
              + '<caption><slot></slot></caption>'
              + '<thead>'
                + '<tr>'
                  + '<th v-for="(value,key) in headings">{{value}}</th>'
                + '</tr>'
              + '</thead>'
              + '<tbody>'
                + '<tr v-for="(opt,inx) in options">'
                  + '<td v-for="(value,key) in headings">'
                    + '<span v-if="tdIsShow(key)" v-html="getValue(opt, key, inx)"></span>'
                    + '<span v-else-if="settings[key].type == \'toggle-button\'">'
                      + '<div class="btn-group btn-group-sm">'
                        + '<button type="button" @click="toggle(opt, settings[key].options[0].value, key)" class="btn" :class="getValue(opt, key, inx) == settings[key].options[0].value ? \'btn-\' + settings[key].options[0].checked : \'btn-secondary\'">{{settings[key].options[0].label}}</button>'
                        + '<button type="button" @click="toggle(opt, settings[key].options[1].value, key)" class="btn" :class="getValue(opt, key, inx) == settings[key].options[1].value ? \'btn-\' + settings[key].options[1].checked : \'btn-secondary\'">{{settings[key].options[1].label}}</button>'
                      + '</div>'
                    + '</span>'
                    + '<span v-else-if="settings[key].type == \'action\'">'
                      + '<button v-for="(value, k) in settings[key].actions" :disabled="value.disabled == \'true\' ? true : false" @click="action(opt, k, inx)" class="btn btn-xs" :class="value.class"><i :class="value.icon"></i>{{value.text}}</button>'
                    + '</span>'
                  + '</td>'
                + '</tr>'
              + '</tbody>'
          + '</table>',
  props: {
    headings: {
      type: Object,
      required: true,
    },
    settings: {
      type: Object,
      required: true,
    },
    options: {
      type: Array,
      required: true,
    },
  },
  computed: {

  },
  methods: {
    tdIsShow(key) {
      if (typeof this.settings[key] == 'undefined') {
        return true;
      }

      return this.settings[key].type == 'toggle-button' || this.settings[key].type == 'action' ? false : true;
    },
    getValue(item, key, inx) {
      var value = '';
      if (key.indexOf(".") == -1) {
        value = item[key];
      } else {
        let arrKey = key.split(".");
        let tmp = item;
        arrKey.forEach(k =>{
          tmp = tmp[k];
        });
        
        value = tmp;
      }

      if (typeof this.settings != 'undefined' && typeof this.settings[key] != 'undefined') {
        switch (this.settings[key].type) {
          case 'image':
              value = this.image(value, this.settings[key]);
            break;
          case 'text':
              value = this.text(value, this.settings[key]);
            break;
          case 'join':
              value = this.join(value, this.settings[key]);
            break;
          case 'array':
              value = this.array(value, this.settings[key]);
            break;
          case 'callback':
              value = this.settings[key].func({item: item, inx: inx, value: value});
            break;
          default:
            break;
        }
      }
      
      return value;
    },
    image(value, setting) {
      if (value == '' || value == null) {
        return '';
      }

      if (typeof setting.class == 'undefined') {
        setting.class = 'img-rounded';
      }
      if (typeof setting.width == 'undefined') {
        setting.width = '40';
        setting.height = '40';
      }

      let result = '<img src="' + value + '" ';
      for (const key in setting) {
        result += key + "='" + setting[key] + "' ";
      }
      result += '>';
      
      if (typeof setting.target != 'undefined') {
        result = '<a href="' + value + '" target="_blank">' + result + '</a>';
      }

      return result;
    },
    text(value, setting) {
      return typeof setting.options[value] == 'undefined'
        ? value
        : setting.options[value];
    },
    join(value, setting) {
      if (value == '') {
        return '';
      }

      if (setting.text.indexOf('%s') == -1) {
        return value + setting.text;
      } else {
        return setting.text.replace(/%s/g, value);
      }
    },
    array(value, setting) {
      if (value.length == 0) {
        return '';
      }

      let result = '';
      value.forEach(row =>{
        if (typeof setting.text == 'undefined') {
          result += '<p style="margin-bottom: 2px;">' + row + '</p>';
        } else {
          result += setting.text.replace(/%s/g, row);
        }
      });

      return result;
    },
    toggle(item, value, key) {
      if (item[key] == value) {
        return false;
      }
      
      this.settings[key].func({item: item, key: key, value: value});
    },
    action(item, key, inx) {
      this.settings.action.actions[key].func({item: item, type: key, inx: inx});
    },
  },
  created() {
    
  }
});
