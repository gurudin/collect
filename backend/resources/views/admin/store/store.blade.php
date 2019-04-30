@extends('layouts.admin')

@section('css')
@endsection

@section('content')
<div class="card">
  <div class="card-body">
    <h4 class="card-title mb-1">交换列表
      <a href="{{route('admin.store.save')}}" class="btn btn-outline-success float-right">
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
      {{$page}}
    </div>
  </div>
</div>
@endsection

@section('script')
<script src="{{asset('js/vue-toggle-button.min.js')}}"></script>
<script>
Vue.component('toggle-button', VueToggleButton.toggleButton);
const vm = new Vue({
  el: '#app',
  data() {
    return {
      init: {
        cards: @json($cards),
        headings: {
          id: '#',
          fk_member_id: '所属用户',
          title: '名称',
          exchange: '售卖类型 / 数量',
          swop: '购买类型 / 数量',
          status: '状态',
          buyer_id: '购买人',
          created_at: '上架时间',
          action: '上架 / 下架'
        },
        settings: {
          'exchange': {
            type: 'callback',
            func: this.exchange
          },
          'swop': {
            type: 'callback',
            func: this.swop
          },
          'status': {
            type: 'text',
            options: {
              '0': '<span class="text-muted"><i class="fas fa-circle"></i> 未上架</span>',
              '1': '<span class="text-muted"><i class="fas fa-circle text-success"></i> 上架中</span>',
              '2': '<span class="text-success">交易成功</span>'
            },
          },
          'created_at': {
            type: 'callback',
            func: this.formatDate
          },
          'action': {
            type: 'action',
            actions: {
              'status': {icon: 'fas fa-sort-amount-up', text: '上架 / 下架', class: 'btn-info btn-sm', func: this.changeStatus},
            }
          },
        },
        options: @json($stores),
      },
    };
  },
  methods: {
    exchange(obj) {
      if (obj.item.exchange == 1) {
        let cardNmae = '';
        this.init.cards.forEach((row) =>{
          if (row.id == obj.item.exchange_crad_id) {
            cardNmae = row.name;
          }
        });
        return obj.item.exchange_text + ' / ' + obj.item.exchange_number + '<br>'
          + '<small class="text-info">' + cardNmae + '</small>';
      } else {
        return obj.item.exchange_text + ' / ' + obj.item.exchange_number;
      }
    },
    swop(obj) {
      if (obj.item.swop == 1) {
        let cardNmae = '';
        this.init.cards.forEach((row) =>{
          if (row.id == obj.item.swop_crad_id) {
            cardNmae = row.name;
          }
        });
        return obj.item.swop_text + ' / ' + obj.item.swop_number + '<br>'
          + '<small class="text-info">' + cardNmae + '</small>';
      } else {
        return obj.item.swop_text + ' / ' + obj.item.swop_number;
      }
    },
    formatDate(obj) {
      return moment(obj.value).format('YYYY/MM/DD HH:mm');
    },
    changeStatus(obj) {
      if (obj.item.status == 2) {
        alert('交易已经完成，不能再做操作!');
        return false;
      }

      let text = obj.item.status == 0 ? '确认上架？' : '确认下架？';
      if (!confirm(text)) {
        return false;
      }
      
      var $btn = $(obj.event.currentTarget).loading('loading...');
      axios.post("{{route('admin.store.index')}}", {
        action: 'status',
        data: {
          id: obj.item.id,
          status: obj.item.status == 0 ? 1 : 0
        },
      }).then((resp) => {
        return resp.data;
      }).then(function (resp) {
        if (resp.status) {
          obj.item.status = obj.item.status == 0 ? 1 : 0;
        } else {
          alert(resp.msg);
        }
        
        $btn.loading('reset');
      });
    },
  },
});
</script>
@endsection
