@extends('layouts.admin')

@section('css')
@endsection

@section('content')
<div class="card">
  <div class="card-body">
    <h5 class="card-title mb-1">
      @{{action == 'logs' ? '用户日志' : '用户财务'}} 
      <small class="text-muted h6">昵称: {{$member['nick_name']}} (ID: {{$member['id']}})</small>

      <a href="{{route('admin.member.index')}}" class="btn btn-light float-right">
        <i class="fas fa-arrow-left"></i> Back
      </a>
    </h5>
    <br>

    {{-- Logs --}}
    <vue-tables
      v-if="action == 'logs'"
      :headings="init.logs.headings"
      :settings="init.logs.settings"
      :options="init.options">
    </vue-tables>

    {{-- Accounts --}}
    <vue-tables
      v-if="action == 'accounts'"
      :headings="init.accounts.headings"
      :settings="init.accounts.settings"
      :options="init.options">
    </vue-tables>

    <div class="float-right">
      {{$result->links()}}
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
      action: '{{$action}}',
      init: {
        logs: {
          headings: {
            id: '#',
            log_type: '类别',
            created_at: '操作时间'
          },
          settings: {
            'log_type': {
              type: 'text',
              options: @json(config('admin.log_type'))
            },
          }
        },
        accounts: {
          headings: {
            id: '#',
            income: '财务',
            balance: '余额',
            remark: '描述',
            created_at: '发生时间'
          },
          settings: {
            
          }
        },
        options: @json($result).data,
      }
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
