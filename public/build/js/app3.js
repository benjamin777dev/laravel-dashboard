(function(e){var i=localStorage.getItem("language"),c="en";function l(t){document.getElementById("header-lang-img")&&(t=="en"?document.getElementById("header-lang-img").src="/build/images/flags/us.jpg":t=="sp"?document.getElementById("header-lang-img").src="/build/images/flags/spain.jpg":t=="gr"?document.getElementById("header-lang-img").src="/build/images/flags/germany.jpg":t=="it"?document.getElementById("header-lang-img").src="/build/images/flags/italy.jpg":t=="ru"&&(document.getElementById("header-lang-img").src="/build/images/flags/russia.jpg"),localStorage.setItem("language",t),i=localStorage.getItem("language"),o())}function o(){i==null&&l(c),e.getJSON("/build/lang/"+i+".json",function(t){e("html").attr("lang",i),e.each(t,function(s,a){s==="head"&&e(document).attr("title",a.title),e("[key='"+s+"']").text(a)})})}function d(){e("#side-menu").metisMenu()}function m(){e("#vertical-menu-btn").on("click",function(t){t.preventDefault(),e("body").toggleClass("sidebar-enable"),e(window).width()>=992?e("body").toggleClass("vertical-collpsed"):e("body").removeClass("vertical-collpsed")})}function p(){e("#sidebar-menu a").each(function(){var t=window.location.href.split(/[?#]/)[0];this.href==t&&(e(this).addClass("active"),e(this).parent().addClass("mm-active"),e(this).parent().parent().addClass("mm-show"),e(this).parent().parent().prev().addClass("mm-active"),e(this).parent().parent().parent().addClass("mm-active"),e(this).parent().parent().parent().parent().addClass("mm-show"),e(this).parent().parent().parent().parent().parent().addClass("mm-active"))})}function u(){e(document).ready(function(){if(e("#sidebar-menu").length>0&&e("#sidebar-menu .mm-active .active").length>0){var t=e("#sidebar-menu .mm-active .active").offset().top;t>300&&(t=t-300,e(".vertical-menu .simplebar-content-wrapper").animate({scrollTop:t},"slow"))}})}function h(){e(".navbar-nav a").each(function(){var t=window.location.href.split(/[?#]/)[0];this.href==t&&(e(this).addClass("active"),e(this).parent().addClass("active"),e(this).parent().parent().addClass("active"),e(this).parent().parent().parent().addClass("active"),e(this).parent().parent().parent().parent().addClass("active"),e(this).parent().parent().parent().parent().parent().addClass("active"),e(this).parent().parent().parent().parent().parent().parent().addClass("active"))})}function f(){e('[data-bs-toggle="fullscreen"]').on("click",function(s){s.preventDefault(),e("body").toggleClass("fullscreen-enable"),!document.fullscreenElement&&!document.mozFullScreenElement&&!document.webkitFullscreenElement?document.documentElement.requestFullscreen?document.documentElement.requestFullscreen():document.documentElement.mozRequestFullScreen?document.documentElement.mozRequestFullScreen():document.documentElement.webkitRequestFullscreen&&document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT):document.cancelFullScreen?document.cancelFullScreen():document.mozCancelFullScreen?document.mozCancelFullScreen():document.webkitCancelFullScreen&&document.webkitCancelFullScreen()}),document.addEventListener("fullscreenchange",t),document.addEventListener("webkitfullscreenchange",t),document.addEventListener("mozfullscreenchange",t);function t(){!document.webkitIsFullScreen&&!document.mozFullScreen&&!document.msFullscreenElement&&(console.log("pressed"),e("body").removeClass("fullscreen-enable"))}}function g(){e(".right-bar-toggle").on("click",function(t){e("body").toggleClass("right-bar-enabled")}),e(document).on("click","body",function(t){e(t.target).closest(".right-bar-toggle, .right-bar").length>0||e("body").removeClass("right-bar-enabled")})}function b(){if(document.getElementById("topnav-menu-content")){for(var t=document.getElementById("topnav-menu-content").getElementsByTagName("a"),s=0,a=t.length;s<a;s++)t[s].onclick=function(n){n.target.getAttribute("href")==="#"&&(n.target.parentElement.classList.toggle("active"),n.target.nextElementSibling.classList.toggle("show"))};window.addEventListener("resize",k)}}function k(){for(var t=document.getElementById("topnav-menu-content").getElementsByTagName("a"),s=0,a=t.length;s<a;s++)t[s].parentElement.getAttribute("class")==="nav-item dropdown active"&&(t[s].parentElement.classList.remove("active"),t[s].nextElementSibling!==null&&t[s].nextElementSibling.classList.remove("show"))}function w(){var t=[].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));t.map(function(n){return new bootstrap.Tooltip(n)});var s=[].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));s.map(function(n){return new bootstrap.Popover(n)});var a=[].slice.call(document.querySelectorAll(".offcanvas"));a.map(function(n){return new bootstrap.Offcanvas(n)})}function v(){e(window).on("load",function(){e("#status").fadeOut(),e("#preloader").delay(350).fadeOut("slow")})}function y(){if(window.sessionStorage){var t=sessionStorage.getItem("is_visited");t?(e(".right-bar input:checkbox").prop("checked",!1),e("#"+t).prop("checked",!0)):e("html").attr("dir")==="rtl"&&e("html").attr("data-bs-theme")==="dark"?(e("#dark-rtl-mode-switch").prop("checked",!0),e("#light-mode-switch").prop("checked",!1),sessionStorage.setItem("is_visited","dark-rtl-mode-switch"),r(t)):e("html").attr("dir")==="rtl"?(e("#rtl-mode-switch").prop("checked",!0),e("#light-mode-switch").prop("checked",!1),sessionStorage.setItem("is_visited","rtl-mode-switch"),r(t)):e("html").attr("data-bs-theme")==="dark"?(e("#dark-mode-switch").prop("checked",!0),e("#light-mode-switch").prop("checked",!1),sessionStorage.setItem("is_visited","dark-mode-switch"),r(t)):sessionStorage.setItem("is_visited","light-mode-switch")}e("#light-mode-switch, #dark-mode-switch, #rtl-mode-switch, #dark-rtl-mode-switch").on("change",function(s){r(s.target.id)}),e("#password-addon").on("click",function(){e(this).siblings("input").length>0&&(e(this).siblings("input").attr("type")=="password"?e(this).siblings("input").attr("type","input"):e(this).siblings("input").attr("type","password"))})}function r(t){e("#light-mode-switch").prop("checked")==!0&&t==="light-mode-switch"?(e("html").removeAttr("dir"),e("#dark-mode-switch").prop("checked",!1),e("#rtl-mode-switch").prop("checked",!1),e("#dark-rtl-mode-switch").prop("checked",!1),e("#bootstrap-style").attr("href")!="/build/css/bootstrap.min.css"&&e("#bootstrap-style").attr("href","/build/css/bootstrap.min.css"),e("html").attr("data-bs-theme","light"),e("#app-style").attr("href")!="/build/css/app.min.css"&&e("#app-style").attr("href","/build/css/app.min.css"),sessionStorage.setItem("is_visited","light-mode-switch")):e("#dark-mode-switch").prop("checked")==!0&&t==="dark-mode-switch"?(e("html").removeAttr("dir"),e("#light-mode-switch").prop("checked",!1),e("#rtl-mode-switch").prop("checked",!1),e("#dark-rtl-mode-switch").prop("checked",!1),e("html").attr("data-bs-theme","dark"),e("#bootstrap-style").attr("href")!="/build/css/bootstrap.min.css"&&e("#bootstrap-style").attr("href","/build/css/bootstrap.min.css"),e("#app-style").attr("href")!="/build/css/app.min.css"&&e("#app-style").attr("href","/build/css/app.min.css"),sessionStorage.setItem("is_visited","dark-mode-switch")):e("#rtl-mode-switch").prop("checked")==!0&&t==="rtl-mode-switch"?(e("#light-mode-switch").prop("checked",!1),e("#dark-mode-switch").prop("checked",!1),e("#dark-rtl-mode-switch").prop("checked",!1),e("#bootstrap-style").attr("href")!="/build/css/bootstrap.min.rtl.css"&&e("#bootstrap-style").attr("href","/build/css/bootstrap.min.rtl.css"),e("#app-style").attr("href")!="/build/css/app.min.rtl.css"&&e("#app-style").attr("href","/build/css/app.min.rtl.css"),e("html").attr("dir","rtl"),e("html").attr("data-bs-theme","light"),sessionStorage.setItem("is_visited","rtl-mode-switch")):e("#dark-rtl-mode-switch").prop("checked")==!0&&t==="dark-rtl-mode-switch"&&(e("#light-mode-switch").prop("checked",!1),e("#rtl-mode-switch").prop("checked",!1),e("#dark-mode-switch").prop("checked",!1),e("#bootstrap-style").attr("href")!="/build/css/bootstrap.min.rtl.css"&&e("#bootstrap-style").attr("href","/build/css/bootstrap.min.rtl.css"),e("#app-style").attr("href")!="/build/css/app.min.rtl.css"&&e("#app-style").attr("href","/build/css/app.min.rtl.css"),e("html").attr("dir","rtl"),e("html").attr("data-bs-theme","dark"),sessionStorage.setItem("is_visited","dark-rtl-mode-switch"))}function S(){i!=null&&i!==c&&l(i),e(".language").on("click",function(t){l(e(this).attr("data-lang"))})}function E(){e("#checkAll").on("change",function(){e(".table-check .form-check-input").prop("checked",e(this).prop("checked"))}),e(".table-check .form-check-input").change(function(){e(".table-check .form-check-input:checked").length==e(".table-check .form-check-input").length?e("#checkAll").prop("checked",!0):e("#checkAll").prop("checked",!1)})}function C(){e('input[name="daterange"]').daterangepicker(),e('input[name="daterange"]').on("apply.daterangepicker",function(t,s){e(this).val(s.startDate.format("DD-MM-YYYY")+" - "+s.endDate.format("DD-MM-YYYY"))})}function I(){d(),m(),p(),u(),h(),f(),g(),b(),C(),w(),y(),S(),v(),Waves.init(),E()}I()})(jQuery);
