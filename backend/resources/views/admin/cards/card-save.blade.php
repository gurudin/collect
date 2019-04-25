@extends('layouts.admin')

@section('css')
@endsection

@section('content')
<div class="card">
  <div class="card-body">
    <h4 class="card-title mb-1">Create <small class="text-muted">(card group)</small>
      <a href="{{route('admin.card.index')}}" class="btn btn-light float-right">
        <i class="fas fa-arrow-left"></i> Back
      </a>
    </h4>
    <hr>

    <vue-form :model='init.m' :options='init.options' ref="form">

      <div class="form-group" slot="fk_group_id">
        <label class="control-label">组</label>
        <v-select label="name" v-model="currentGroup" :options="init.groups" placeholder="选择卡片组..."></v-select>
      </div>

      <div class="form-group" slot="chance">
        <label class="control-label">获取几率</label>
        <div class="input-group col-3 row">
            <input type="text" class="form-control" v-model="init.m.chance" required placeholder="获取几率 百分比">
            <div class="input-group-append">
              <span class="input-group-text">%</span>
            </div>
        </div>
      </div>

    </vue-form>
  </div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/vue-upload-picker.js')}}"></script>
<script src="{{ asset('js/vue-select.js')}}"></script>
<script>
Vue.component('v-select', VueSelect.VueSelect);
const vm = new Vue({
  el: '#app',
  data() {
    return {
      init: {
        groups: @json($groups),
        m: @json($m),
        options: {
          'fk_group_id': {type: 'slot', name: 'fk_group_id'},
          'name': {label:'名称', type: 'text', required: true, placeholder: '卡名称'},
          'total_cards': {label: '总量', type: 'number', required: true, min: 1, placeholder: '发卡量总数'},
          'chance': {type: 'slot', name: 'chance'},
          'difficulty_level': {label: '获取难度 (从用户大于多少张卡片之后才会有获取几率)', type: 'number', required: true, placeholder: '获取难度'},
          'cover': {label: '封面', type: 'file', dataName: '封面上传', dataUri: '{{route("admin.upload")}}'},
          'description': {label: '描述', type: 'textarea', placeholder: '卡片简介'},
          'btnSubmit': {label: "提交", type: 'submit', class: 'btn-success', func: this.save}
        }
      },
      currentGroup: @json($current_group),
    };
  },
  watch: {
    'currentGroup': function(value) {
      this.init.m.fk_group_id = value === null ? '' : value.id;
    },
  },
  methods: {
    save(event) {
      if (this.init.m.fk_group_id == '') {
        alert('请选择卡片组');
        return false;
      }

      var $btn = $(event).loading('<i class="fas fa-spinner fa-spin"></i> loading');
      
      axios.post("{{route('admin.card.save')}}", {
        action: 'save',
        data: this.init.m
      }).then((resp) => {
        return resp.data;
      }).then(function (resp) {
        if (resp.status) {
          window.location.href = "{{route('admin.card.index')}}";
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
