(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-36f78526"],{2642:function(e,t,u){"use strict";u("5d57")},"5d57":function(e,t,u){},"7a38":function(e,t,u){"use strict";u("c729")},8882:function(e,t,u){"use strict";u.r(t);var a=function(){var e=this,t=e.$createElement,u=e._self._c||t;return u("div",[u("sidebar",{attrs:{menus:e.submenus,"module-name":e.moduleName}}),u("router-view",{attrs:{name:"subRouter"}})],1)},r=[],n=u("978a"),s={components:{sidebar:n["a"]},data:function(){return{moduleName:"充值套餐",submenus:[{title:"对话套餐",routeName:"ModuleChargeGoods",routeQuery:{}},{title:"GPT4套餐",routeName:"ModuleChargeGpt4",routeQuery:{}},{title:"绘画套餐",routeName:"ModuleChargeDraw",routeQuery:{}},{title:"vip套餐",routeName:"ModuleChargeVip",routeQuery:{}}]}},methods:{}},o=s,i=(u("2642"),u("3427")),l=Object(i["a"])(o,a,r,!1,null,"7fa08cc2",null);t["default"]=l.exports},"978a":function(e,t,u){"use strict";var a=function(){var e=this,t=e.$createElement,u=e._self._c||t;return u("div",{staticClass:"submenu",class:{collapsed:e.isCollapse},style:"width:"+e.width+"px;"},[u("div",{staticClass:"module-name"},[e._v(e._s(e.moduleName))]),u("ul",{staticClass:"list-group"},e._l(e.menus,(function(t,a){return u("li",{key:a,staticClass:"list-item",class:{active:e.routeName===t.routeName},on:{click:function(u){return e.linkto(t.routeName,t.routeQuery)}}},[e._v(" "+e._s(t.title))])})),0)])},r=[],n=u("4a60"),s=(u("1a7c"),u("5f17"),u("c36d"),u("19e4"),u("6db4")),o={props:{menus:{type:Array,default:null},moduleName:{type:String,default:""},width:{type:Number,default:110}},computed:Object(n["a"])(Object(n["a"])({},Object(s["b"])(["sidebar"])),{},{isCollapse:function(){return!this.sidebar.opened},routeName:function(){return this.$route.name}}),methods:{linkto:function(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};this.$router.replace({name:e,query:t})}}},i=o,l=(u("7a38"),u("3427")),c=Object(l["a"])(i,a,r,!1,null,"69562c9a",null);t["a"]=c.exports},c729:function(e,t,u){}}]);