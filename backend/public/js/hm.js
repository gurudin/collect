(function(){
  var params = {};

  if (document) {
    params.url = document.URL || '';
    params.title = document.title || '';
    params.referrer = document.referrer || '';
  }

  if (window && window.screen) {
    params.width = window.screen.width || 0;
    params.height = window.screen.height || 0;
  }

  if (navigator) {
    params.useragent = navigator.userAgent || '';
    params.platform = navigator.platform || '';
    params.language = navigator.language || '';
  }

  var url = new URL('http://frontend.test/hm.gif');
  for (const i in params) {
    url.searchParams.append(i, encodeURIComponent(params[i]));
  }

  var image = new Image(1, 1);
  image.src = url.href;
})();