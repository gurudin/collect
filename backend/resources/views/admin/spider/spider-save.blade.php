@extends('layouts.admin')

@section('css')
@endsection

@section('content')
<div class="card">
  <div class="card-body">
    <h4 class="card-title mb-1">{{isset($m['id']) ? 'Update' : 'Create'}} <small class="text-muted">(Spider Rule)</small>
      <a href="{{route('admin.spider')}}" class="btn btn-light float-right">
        <i class="fas fa-arrow-left"></i> Back
      </a>
    </h4>
    <hr>

    <div class="row">
      <div class="col">
        <h6 class="text-info">采集规则</h6>
        <hr class="mt-1">

        <vue-form :model='init.m' :options='init.options' ref="form">
          <div class="form-group" slot="ruleBtn">
            <button type="button" class="btn btn-primary btn-block" @click="testRule">测试采集</button>
          </div>
        </vue-form>
      </div>

      <div class="col">
        <h6 class="text-info">测试结果</h6>
        <hr class="mt-1">

        <textarea class="form-control"
          style="height: 880px"
          placeholder="采集结果"
          :value="resultRule == '' ? '' : JSON.stringify(resultRule, null, 2)"></textarea>
      </div>
    </div>
    
  </div>
</div>
@endsection

@section('script')
<script>
const vm = new Vue({
  el: '#app',
  data() {
    return {
      init: {
        m: @json($m),
        options: {
          type: {label: '采集类型', type: 'select', 'k-field': 'key', 'v-field': 'value', options: [{key: 1, value: 'Html'}, {key: 2, value: 'Json'}]},
          name: {label: '采集标题', type: 'text', required: true, placeholder: '采集标题'},
          url: {label: 'URL', type: 'text',  placeholder: 'URL'},
          slice: {label: '切片', type: 'text', placeholder: '切片选择器'},
          rule: {label: '采集规则 (Json)', type: 'textarea', placeholder: '采集规则', rows: 10},
          filed_rule: {label: '字段规则 (Json)', type: 'textarea', placeholder: '字段规则', rows: 5},
          ruleBtn: {type: 'slot', name: 'ruleBtn'},
          btnSubmit: {label: "提交", type: 'submit', class: 'btn-success btn-block', func: this.save}
        },
      },
      resultRule: '',
    };
  },
  methods: {
    testRule(event) {
      if (this.init.m.type == 1) {
        if (this.init.m.url == ''
          || this.init.m.rule == ''
        ) {
          alert('信息不全');
          return false;
        }
      }

      var _this = this;
      var $btn = $(event.target).loading('<i class="fas fa-spinner fa-spin"></i> loading');
      this.resultRule = '';
      axios.post("{{route('admin.spider.save')}}", {
        action: 'testRule',
        data: this.init.m
      }).then((resp) => {
        return resp.data;
      }).then(function (resp) {
        $btn.loading('reset');
        _this.resultRule = resp;
      });
    },
    save(event) {
      if (this.init.m.type == 1) {
        if (this.init.m.url == ''
          || this.init.m.rule == ''
        ) {
          alert('信息不全');
          return false;
        }
      }

      var $btn = $(event).loading('<i class="fas fa-spinner fa-spin"></i> loading');
      axios.post("{{route('admin.spider.save')}}", {
        action: 'save',
        data: this.init.m
      }).then((resp) => {
        return resp.data;
      }).then(function (resp) {
        if (resp.status) {
          window.location = "{{route('admin.spider')}}";
        } else {
          $btn.loading('reset');
        }
      });
    }
  }
});
</script>
@endsection
