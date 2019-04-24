@extends('layouts.admin')

@section('css')
@endsection

@section('content')
<div class="card">
  <div class="card-body">
    <h4 class="card-title mb-1">Create <small class="text-muted">(card group)</small>
      <a href="{{route('admin.card.group.index')}}" class="btn btn-light float-right">
        <i class="fas fa-arrow-left"></i> Back
      </a>
    </h4>
    <hr>

    @{{init.m}}
    <vue-form :model='init.m' :options='init.options' ref="form">
    </vue-form>
  </div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/vue-upload-picker.js')}}"></script>
<script>
const vm = new Vue({
  el: '#app',
  data() {
    return {
      init: {
        m: @json($m),
        options: {
          'name': {label: '名称', type: 'text', required: true, placeholder: '卡片组名称'},
          'number': {label: '下属卡片总数', type: 'number', required: true, min: 1, placeholder: '组包含卡片总数'},
          'cover': {label: '封面', type: 'file', dataName: '封面上传', dataUri: '{{route("admin.upload")}}'},
          'description': {label: '描述', type: 'textarea', placeholder: '卡片组简介'},
          'btnSubmit': {label: "提交", type: 'submit', class: 'btn-success', func: this.save}
        }
      },
    };
  },
  methods: {
    save(event) {
      var $btn = $(event).loading('<i class="fas fa-spinner fa-spin"></i> loading');
      
      axios.post("{{route('admin.card.group.save')}}", {
        action: 'save',
        data: this.init.m
      }).then((resp) => {
        return resp.data;
      }).then(function (resp) {
        if (resp.status) {
          window.location.href = "{{route('admin.card.group.index')}}";
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
