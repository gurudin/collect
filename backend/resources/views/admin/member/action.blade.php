@extends('layouts.admin')

@section('css')
<link href="{{ asset('css/vue-datepicker-local.css') }}" rel="stylesheet">
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

    <form class="form-inline mb-1 text-muted">
      <div class="form-group">
        <label>时间：</label>
        <vue-datepicker-local v-model="searchKey.date" clearable></vue-datepicker-local>
      </div>

      <div class="form-group ml-1" v-if="action == 'logs'">
        <label>类别：</label>
        <select class="form-control form-control-sm" v-model="searchKey.type">
          <option value="">全部</option>
          <option v-for="(item,inx) in init.logs.settings['log_type'].options" :value="inx">@{{item}}</option>
        </select>
      </div>

      <div class="form-group ml-1" v-if="action == 'accounts'">
        <label>类别：</label>
        <select class="form-control form-control-sm" v-model="searchKey.type">
          <option value="">全部</option>
          <option value="1">收入</option>
          <option value="2">支出</option>
        </select>
      </div>

      <button type="button" class="btn btn-primary btn-sm ml-1" @click="search"><i class="fas fa-search"></i> 检索</button>
    </form>

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
<script src="{{asset('js/vue-datepicker-local.js')}}"></script>
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
      },
      searchKey: @json($search_key),
    };
  },
  methods: {
    search(event) {
      $(event.currentTarget).loading('loading...');
      let fullUrl = new URL("{{URL::full()}}");
      let url = new URL(fullUrl.origin + fullUrl.pathname);
      if (this.searchKey.date.length == 2) {
        url.searchParams.append('start', moment(this.searchKey.date[0]).format('YYYY-MM-DD'));
        url.searchParams.append('end', moment(this.searchKey.date[1]).format('YYYY-MM-DD'));
      }
      if (this.searchKey.type != '') {
        url.searchParams.append('type', this.searchKey.type);
      }
      
      window.location = url;
    },
  },
});
</script>
@endsection
