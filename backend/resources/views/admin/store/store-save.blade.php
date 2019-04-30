@extends('layouts.admin')

@section('css')
@endsection

@section('content')
<div class="card">
  <div class="card-body">
    <h4 class="card-title mb-1">{{isset($m['id']) ? '修改' : '添加'}} <small class="text-muted">(交换)</small>
      <a href="{{route('admin.store.index')}}" class="btn btn-light float-right">
        <i class="fas fa-arrow-left"></i> Back
      </a>
    </h4>
    <hr>

    <vue-form :model='init.m' :options='init.options' ref="form">

      <div class="form-group" slot="exchange_crad_id">
        <span v-if="init.m.exchange == 1">
          <label class="control-label">售卖方卡片</label>
          <v-select label="name" v-model="exchangeCard" :options="init.cards" placeholder="选择售卖卡片..."></v-select>
        </span>
        <hr>
      </div>

      <div class="form-group" slot="swop_crad_id" v-if="init.m.swop == 1">
        <label class="control-label">购买方卡片</label>
        <v-select label="name" v-model="swopCard" :options="init.cards" placeholder="选择购买方卡片..."></v-select>
      </div>
      
    </vue-form>
  </div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/vue-select.js')}}"></script>
<script>
Vue.component('v-select', VueSelect.VueSelect);
const vm = new Vue({
  el: '#app',
  data() {
    return {
      init: {
        m: @json($m),
        cards: @json($cards),
        options: {
          'title': {label: '售卖标题', type: 'text', required: true, placeholder: '售卖标题'},
          'remark': {label: '描述', type: 'textarea', placeholder: '售卖描述'},
          'exchange': {label: '售卖方类型', type: 'select', 'k-field': 'code', 'v-field': 'title', options: @json($exchange_type)},
          'exchange_crad_id': {type: 'slot', name: 'exchange_crad_id'},
          'exchange_number': {label: '售卖方数量', type: 'number', required: true, placeholder: '售卖方数量'},
          'swop': {label: '购买方类型', type: 'select', 'k-field': 'code', 'v-field': 'title', options: @json($exchange_type)},
          'swop_crad_id': {type: 'slot', name: 'swop_crad_id'},
          'swop_number': {label: '购买方数量', type: 'number', required: true, placeholder: '购买方数量'},
          'btnSubmit': {label: "提交", type: 'submit', class: 'btn-success', func: this.save}
        }
      },
      exchangeCard: null,
      swopCard: null,
    };
  },
  watch: {
    'exchangeCard': function(value) {
      this.init.m.exchange_crad_id = value === null ? '' : value.id;
    },
    'swopCard': function(value) {
      this.init.m.swop_crad_id = value === null ? '' : value.id;
    },
  },
  methods: {
    save(event) {
      if (this.init.m.exchange == 1 && this.init.m.exchange_crad_id == '') {
        alert('请选择售卖卡片');
        return false;
      }
      if (this.init.m.swop == 1 && this.init.m.swop_crad_id == '') {
        alert('请选择购买方卡片');
        return false;
      }

      if (!confirm('请仔细检查，发布后不可修改，只能下架。')) {
        return false;
      }

      var $btn = $(event).loading('<i class="fas fa-spinner fa-spin"></i> loading');
      
      axios.post("{{route('admin.store.save')}}", {
        action: 'save',
        data: this.init.m
      }).then((resp) => {
        return resp.data;
      }).then(function (resp) {
        console.log(resp);
        
        if (resp.status) {
          window.location.href = "{{route('admin.store.index')}}";
        } else {
          alert(resp.msg);
          $btn.loading('reset');
        }
      });
    }
  }
});
</script>
@endsection
