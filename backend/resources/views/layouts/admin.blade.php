<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title') {{ config('app.name', '') }}</title>

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="{{ asset('css/site.css') }}" rel="stylesheet">
  @yield('css')
</head>

<body>
  <div class="wrapper">
    <div class="d-flex">
      <nav class="sidebar">
        <div class="sidebar-content">
          <a class="sidebar-brand" href="/admin.cms">
            <span class="align-middle">AppStack</span>
          </a>

          <ul class="sidebar-nav" id="nav" v-cloak>
            <li class="sidebar-header">
              Main
            </li>
            
            <li class="sidebar-item" v-for="item in navItem">
              <a
                :href="item.href!='#' ? item.href : 'javascript:void(0)'"
                :data-toggle="typeof item.child=='object' ? 'collapse' : ''"
                class="sidebar-link"
                :class="{'collapsed':item.open==false}"
                @click="openChild(item)">
                <i v-if="item.icon!=''" :class="item.icon"></i>
                <span class="align-middle">@{{item.label}}</span>
                <span class="sidebar-badge badge badge-primary" v-if="item.badge!=''">@{{item.badge}}</span>
              </a>

              <ul
                class="sidebar-dropdown list-unstyled collapse"
                :class="{'show': item.open==true}"
                v-if="typeof item.child=='object'">
                <li class="sidebar-item" :class="{'active':child.href==currentUri}" v-for="child in item.child">
                  <a class="sidebar-link" :href="child.href!='#' ? child.href : 'javascript:void(0)'">
                    <i v-if="child.icon!=''" :class="child.icon"></i>
                    @{{child.label}}
                    <span class="sidebar-badge badge badge-primary" v-if="child.badge!=''">@{{child.badge}}</span>
                  </a>
                </li>
              </ul>
            </li>
          </ul>

        </div>
      </nav>

      <div class="main-admin w-100">
        <nav class="navbar navbar-expand navbar-light bg-white">
          <a class="sidebar-toggle d-flex mr-2">
            <i class="hamburger align-self-center"></i>
          </a>

          <form class="form-inline d-none d-sm-inline-block">
            <input class="form-control mr-sm-2" type="text" placeholder="Search projects" aria-label="Search">
          </form>

          <div class="navbar-collapse collapse">
            <ul class="navbar-nav ml-auto">
              <li class="nav-item dropdown">
                <form action="{{ route('logout') }}" method="POST">
                  @csrf
                  <button type="submit" class="btn btn-link logout text-dark">Sign out ({{ Auth::user()->name }})</button>
                </form>
              </li>
            </ul>
          </div>
        </nav>

        <main style="padding: 0.7rem" id="app" v-cloak>
          <div class="container-fluid p-0">
            @yield('content')
          </div>
        </main>
      </div>
      
    </div>
  </div>

  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}"></script>
  <script>
    Vue.prototype.moment = moment;
    var navs = @json(config('admin.nav'));
    const nav = new Vue({
      el: '#nav',
      data() {
        return {
          navItem: navs,
          currentUri: window.location.pathname
        };
      },
      methods: {
        openChild(item) {
          if (typeof item.open == 'undefined') {
            item.open = true;
          } else {
            item.open = !item.open;
          }
        }
      },
      created() {
        var _this = this;
        this.navItem.forEach(row => {
          if (typeof row.child == 'object') {
            row.child.forEach(child => {
              if (child.href == _this.currentUri) {
                row.open = true;
              }
            });
          }
        });
      }
    });

    (function ($) {
      /**
       * Button for loading
       * @param {string} text
       * 
       * @example $(event).loading(); / $(event).loading('reset');
       */
      var loadingDefaultValue = '';
      $.fn.loading = function (text = '') {
        text == '' ? text = 'loading...' : text;
        if (text == 'reset') {
          this.removeClass('disabled').attr('disabled', false).html(loadingDefaultValue);
        } else {
          loadingDefaultValue = this[0].innerHTML;
          this.addClass('disabled').attr('disabled', true).html(text);
          
          return this;
        }
      };
    })(jQuery);
    /**
     * 
     * @param {string} currentUrl
     * @param {object} params
     * 
     * @return {string} url
     */
    var getUrl = function(currentUrl, params) {
      var url = new URL(currentUrl);
      
      for (const key in params) {
        if (params[key] != '') {
          url.searchParams.append(key, params[key]);
        }
      }

      return url.href;
    }
  </script>
  @yield('script')
</body>
</html>