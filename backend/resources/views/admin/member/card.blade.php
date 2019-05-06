@extends('layouts.admin')

@section('css')
<style>
.right {
  right: 0;
  z-index: 1;
}
.delete-img {
  filter:brightness(70%);
}
.fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>
@endsection

@section('content')
<div class="card">
  <div class="card-body">
    <h5 class="card-title mb-1">
      用户卡片 
      <small class="text-muted h6">昵称: {{$member['nick_name']}} (ID: {{$member['id']}})</small>
      <a href="{{route('admin.member.index')}}" class="btn btn-light float-right">
        <i class="fas fa-arrow-left"></i> Back
      </a>
    </h5>
    <br>

    <form class="form-inline text-muted">
      <div class="form-group mr-3">
        <label>是否删除：</label>
        <select class="form-control form-control-sm" v-model="searchKey.delete">
          <option value="">全部</option>
          <option value="1">是</option>
          <option value="0">否</option>
        </select>
      </div>
      
      <div class="form-group mr-3">
        <label>排序：</label>
        <select class="form-control form-control-sm" v-model="searchKey.order">
          <option value="asc">正序</option>
          <option value="desc">倒序</option>
        </select>
      </div>

    </form>
    <hr>

    <div class="card-deck"
      v-if="init.cards.length > 0"
      v-for="p in Math.ceil(init.cards.length / 6)">

      <div class="card col-2 p-0 mr-0" :class="{'delete-img':getCard(p, i).delete}" v-for="i in 6" v-if="getCard(p, i)">
        <span class="badge badge-danger position-absolute right" v-if="getCard(p, i).delete">已删除</span>
        <img :src="getCard(p, i).card_cover" class="card-img-top">
        <div class="card-body p-2">
          <p class="card-title">
            @{{getCard(p, i).card_name}}
            <small class="text-muted" v-if="getCard(p, i).delete">(@{{getCard(p, i).delete_remark}})</small>
          </p>
          <p class="card-text text-right">
            <small class="text-muted">
              -- @{{moment(getCard(p, i).created_at).format('YYYY.MM.DD HH:mm:ss')}} 获得
            </small>
          </p>
        </div>
      </div>

    </div>

    <h4 class="text-muted text-center p-3" v-if="init.cards.length == 0"><i class="far fa-frown"></i> 用户还未获得卡片...</h4>
  </div>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/velocity/1.2.3/velocity.min.js"></script>
<script>
const vm = new Vue({
  el: '#app',
  data() {
    return {
      init: {
        cards: @json($cards),
      },
      searchKey: {
        delete: '',
        order: 'asc',
      },
    };
  },
  computed: {
    cardsData() {
      let cards = [];
      
      cards = this.init.cards.filter((row) =>{
        if (this.searchKey.delete == '') {
          return true;
        }
        
        return row.delete == this.searchKey.delete ? true : false;
      });

      return cards.sort((a, b) =>{
        return this.searchKey.order == 'asc'
          ? a.id - b.id
          : b.id - a.id;
      });
    }
  },
  methods: {
    getCard(p, i) {
      return this.cardsData[(p-1) * 6 + i -1];
    },
  },
});
</script>
@endsection
