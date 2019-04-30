@extends('layouts.admin')

@section('css')
@endsection

@section('content')
<div class="card">
  <div class="card-body">
    <h4 class="card-title mb-1">用户</h4>
    <br>

    <form class="form-inline mb-1 text-muted">
      <div class="form-group">
        <label>昵称：</label>
        <input type="text" class="form-control form-control-sm" placeholder="用户昵称" v-model.trim="search.nick">
      </div>
      &nbsp;
      
      <div class="form-group">
        <label>状态：</label>
        <select class="form-control form-control-sm" v-model="search.status">
          <option value="">全部</option>
          <option value="1">正常</option>
          <option value="0">禁用</option>
        </select>
      </div>
      &nbsp;

      <div class="form-group">
        <label>余额：</label>
        <select class="form-control form-control-sm" v-model="search.order">
          <option value="">排序</option>
          <option value="asc">从少到多</option>
          <option value="desc">从多到少</option>
        </select>
      </div>
      &nbsp;

      <button type="button" class="btn btn-primary btn-sm" @click="searchItem"><i class="fas fa-search"></i> 搜索</button>
    </form>
    
    <vue-tables
      :headings="init.headings"
      :settings="init.settings"
      :options="init.options">
    </vue-tables>

    <div class="float-right">
      {{$members->links()}}
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
        avatars: @json($avatars),
        headings: {
          'id': '#',
          'nick_name': '昵称',
          'gender': '性别',
          'avatar': '头像',
          'balance': '余额',
          'status': '状态',
          'created_at': '创建时间',
          'action': '操作'
        },
        settings: {
          'gender': {
            type: 'callback',
            func: this.genderText
          },
          'avatar': {
            type: 'callback',
            func: this.avatarText
          },
          'status': {
            type: 'toggle-button',
            options: [
              {label: "正常", value: 1, checked: "success"},
              {label: "禁用", value: 0, checked: "danger"},
            ],
            func: this.changeStatus,
          },
          'created_at': {
            type: 'callback',
            func: this.formatDate
          },
          'action': {
            type: 'action',
            actions: {
              'log': {text: '日志', class: 'btn-info btn-sm', func: this.logs},
              'account': {text: '账务', class: 'btn-info btn-sm', func: this.accounts}
            }
          }
        },
        options: @json($members).data,
      },
      search: @json($search),
    };
  },
  methods: {
    formatDate(obj) {
      return moment(obj.value).format('YYYY/MM/DD HH:mm');
    },
    genderText(obj) {
      return ['保密', '男', '女'][obj.item.gender];
    },
    avatarText(obj) {
      let avatar = obj.item.avatar;
      if (avatar == '') {
        avatar = this.init.avatars[obj.item.gender];
      }

      return '<a href="' + avatar+ '" target="_blank"><img src="' + avatar + '" width="35" class="rounded"></a>';
    },
    searchItem() {
      let url = new URL("{{route('admin.member.index')}}");
      for (const key in this.search) {
        if (this.search[key] != '') {
          url.searchParams.append(key, this.search[key]);
        }
      }
      
      window.location = url;
    },
    changeStatus(obj) {
      obj.item[obj.key] = obj.value
      
      axios.post("{{route('admin.member.index')}}", {
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
    logs(obj) {
      let url = new URL("{{route('admin.member.action')}}").href;
      url += '/logs/' + obj.item.id;

      window.location = url;
    },
    accounts(obj) {
      let url = new URL("{{route('admin.member.action')}}").href;
      url += '/accounts/' + obj.item.id;

      window.location = url;
    },
  },
});
</script>
@endsection
