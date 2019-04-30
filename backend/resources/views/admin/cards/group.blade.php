@extends('layouts.admin')

@section('css')
@endsection

@section('content')
<div class="card">
  <div class="card-body">
    <h4 class="card-title mb-1">卡片组 
      <a href="{{route('admin.card.group.save')}}" class="btn btn-outline-success float-right">
        <i class="fas fa-file-signature"></i> 添加
      </a>
    </h4>
    <br>

    <vue-tables
      :headings="init.headings"
      :settings="init.settings"
      :options="init.options">
    </vue-tables>

    <div class="float-right">
      {{ $groups->links() }}
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
        headings: {
          id: '#',
          name: '名称',
          number: '总数',
          cover: '封面',
          status: '状态',
          created_at: '创建时间',
          action: '操作'
        },
        settings: {
          'cover': {
            type: 'image',
            target: '_blank',
          },
          'created_at': {
            type: 'callback',
            func: this.formatDate,
          },
          'status': {
            type: 'toggle-button',
            options: [
              {label: "上线", value: 1, checked: "success"},
              {label: "下线", value: 0, checked: "warning"},
            ],
            func: this.changeStatus,
          },
          'action': {
            type: 'action',
            actions: {
              'edit': {icon: 'fas fa-edit', class: 'btn-warning btn-sm text-dark', func: this.toEdit},
            }
          },
        },
        options: @json($groups).data,
      },
    };
  },
  methods: {
    formatDate(obj) {
      return moment(obj.value).format('YYYY/MM/DD HH:mm');
    },
    toEdit(obj) {
      var url = new URL("{{route('admin.card.group.save')}}");
      url.searchParams.append('id', obj.item.id);

      window.location = url;
    },
    changeStatus(obj) {
      obj.item[obj.key] = obj.value

      axios.post("{{route('admin.card.group.index')}}", {
        action: 'status',
        data: {
          id: obj.item.id,
          status: obj.value
        },
      }).then((resp) => {
        return resp.data;
      }).then(function (resp) {
        if (!resp.status) {
          alert(resp.msg);
        }
      });
    },
  },
});
</script>
@endsection
