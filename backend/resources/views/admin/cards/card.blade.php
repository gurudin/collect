@extends('layouts.admin')

@section('css')
@endsection

@section('content')
<div class="card">
  <div class="card-body">
    <h4 class="card-title mb-1">卡片列表 
      <a href="{{route('admin.card.save')}}" class="btn btn-outline-success float-right">
        <i class="fas fa-file-signature"></i> 添加
      </a>
    </h4>
    <br>
    
    <form class="form-inline mb-1 text-muted">
      <div class="form-group">
        <label>组：</label>
        <select class="form-control form-control-sm" v-model="groupId" @change="changeGroup">
          <option value="">不限</option>
          <option v-for="item in init.groups" :value="item.id">@{{item.name}} @{{item.status == 0 ? '(已下线)' : ''}}</option>
        </select>
      </div> 
    </form>

    <vue-tables
      :headings="init.headings"
      :settings="init.settings"
      :options="init.options"/>

    <div class="float-right">
        {{ $cards->links() }}
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
        groups: @json($groups),
        headings: {
          'id': '#',
          'name': '名称',
          'fk_group_id': '组',
          'total_cards': '总数/已发放',
          'cover': '封面',
          'status': '状态',
          'created_at': '时间',
          'action': '操作'
        },
        settings: {
          'fk_group_id': {
            type: 'callback',
            func: this.groupsText
          },
          'total_cards': {
            type: 'callback',
            func: this.issueStatus,
          },
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
        options: @json($cards).data,
      },
      groupId: '{{$group_id}}',
    };
  },
  methods: {
    groupsText(obj) {
      let text = obj.value;

      this.init.groups.forEach(row =>{
        if (row.id == obj.value) {
          text = row.status == 1
            ? row.name
            : row.name + '<small class="text-danger">(已下线)</small>';
        }
      });

      return text;
    },
    issueStatus(obj) {
      return obj.item.total_cards + '/' + obj.item.issued;
    },
    formatDate(obj) {
      return moment(obj.value).format('YYYY/MM/DD HH:mm');
    },
    toEdit(obj) {
      var url = new URL("{{route('admin.card.save')}}");
      url.searchParams.append('id', obj.item.id);

      window.location = url;
    },
    changeGroup() {
      var url = new URL("{{route('admin.card.index')}}");
      url.searchParams.append('group_id', this.groupId);

      window.location = url;
    },
    changeStatus(obj) {
      obj.item[obj.key] = obj.value

      axios.post("{{route('admin.card.index')}}", {
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
