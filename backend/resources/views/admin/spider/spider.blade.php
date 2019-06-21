@extends('layouts.admin')

@section('css')
@endsection

@section('content')
<div class="card">
  <div class="card-body">
    <h4 class="card-title mb-1">Spider List
      <a href="{{route('admin.spider.save')}}" class="btn btn-outline-success float-right">
        <i class="fas fa-file-signature"></i> Create
      </a>
    </h4>
    <br>

    <vue-tables
      :headings="init.headings"
      :settings="init.settings"
      :options="init.options">
    </vue-tables>

    <div class="float-right">

    </div>
  </div>
</div>
@endsection

@section('script')
<script src="{{asset('js/vue-toggle-button.min.js')}}"></script>
<script>
const vm = new Vue({
  el: '#app',
  data() {
    return {
      init: {
        options: @json($rules),
        headings: {
          id: '#',
          name: '标题',
          type: '类型',
          enable: '是否采集',
          action: '操作'
        },
        settings: {
          type: {
            type: 'text',
            options: {1: 'HTML', 2: 'JSON'}
          },
          enable: {
            type: 'toggle-button',
            func: this.enabledCall,
            options: [
              {label: "未采集", value: 0, checked: "danger"},
              {label: "正则采集", value: 1, checked: "success"},
            ]
          },
          action: {
            type: 'action',
            actions: {
              edit: {icon: 'fas fa-edit', class: 'btn-warning btn-sm', func: this.toEdit},
              remove: {icon: 'fas fa-trash-alt', class: 'btn-danger btn-sm', func: this.remove}
            }
          }
        }
      },
    };
  },
  methods: {
    enabledCall(obj) {
      axios.post("{{route('admin.spider')}}", {
        action: 'enabled',
        id: obj.item.id,
        value: obj.value
      }).then((resp) => {
        return resp.data;
      }).then(function (resp) {
        obj.item[obj.key] = obj.value;
      });
    },
    toEdit(obj) {
      var url = new URL("{{route('admin.spider.save')}}");
      url.searchParams.append('id', obj.item.id);

      window.location = url;
    },
    remove(obj) {
      console.log(obj);
      if (!confirm('确认删除?')) {
        return false;
      }

      axios.post("{{route('admin.spider')}}", {
        action: 'delete',
        id: obj.item.id,
      }).then((resp) => {
        return resp.data;
      }).then(function (resp) {
        if (resp.status) {
          window.location.reload();
        } else {
          alert(resp.msg);
        }
      });
    }
  },
});
</script>
@endsection