(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-a1201084"],{"02f3":function(t,e,a){"use strict";a.d(e,"e",(function(){return r})),a.d(e,"f",(function(){return i})),a.d(e,"c",(function(){return s})),a.d(e,"d",(function(){return o})),a.d(e,"a",(function(){return c})),a.d(e,"b",(function(){return d}));var n=a("b775");function r(t){return Object(n["a"])({url:"/reward/getShareList",method:"post",data:t})}function i(t){return Object(n["a"])({url:"/reward/getShareTongji",method:"post",data:t})}function s(t){return Object(n["a"])({url:"/reward/getInviteList",method:"post",data:t})}function o(t){return Object(n["a"])({url:"/reward/getInviteTongji",method:"post",data:t})}function c(t){return Object(n["a"])({url:"/reward/getAdList",method:"post",data:t})}function d(t){return Object(n["a"])({url:"/reward/getAdTongji",method:"post",data:t})}},"0998":function(t,e,a){var n=a("12f4"),r=a("11e4"),i=a("d409"),s=a("3c2a"),o=a("e5b5").get,c=RegExp.prototype,d=TypeError;n&&r&&s(c,"dotAll",{configurable:!0,get:function(){if(this!==c){if("RegExp"===i(this))return!!o(this).dotAll;throw d("Incompatible receiver, RegExp required")}}})},"1f81":function(t,e,a){},"24e3":function(t,e){t.exports=Object.is||function(t,e){return t===e?0!==t||1/t===1/e:t!=t&&e!=e}},"2e04":function(t,e,a){"use strict";var n=a("2d29").PROPER,r=a("968d"),i=a("a30c"),s=a("8fde"),o=a("baf9"),c=a("bf52"),d="toString",l=RegExp.prototype,u=l[d],p=o((function(){return"/a/b"!=u.call({source:"a",flags:"b"})})),f=n&&u.name!=d;(p||f)&&r(RegExp.prototype,d,(function(){var t=i(this),e=s(t.source),a=s(c(t));return"/"+e+"/"+a}),{unsafe:!0})},"44c3":function(t,e,a){var n=a("12f4"),r=a("bf5d"),i=a("b303"),s=a("95df"),o=a("2fca"),c=a("80b0"),d=a("a6bf").f,l=a("1449"),u=a("6a5e"),p=a("8fde"),f=a("bf52"),g=a("47b0"),h=a("9d67"),b=a("968d"),y=a("baf9"),v=a("5169"),m=a("e5b5").enforce,x=a("8bf1"),w=a("3192"),_=a("11e4"),E=a("f715"),S=w("match"),T=r.RegExp,R=T.prototype,C=r.SyntaxError,I=i(R.exec),j=i("".charAt),k=i("".replace),D=i("".indexOf),M=i("".slice),O=/^\?<[^\s\d!#%&*+<=>@^][^\s!#%&*+<=>@^]*>/,z=/a/g,L=/a/g,A=new T(z)!==z,$=g.MISSED_STICKY,F=g.UNSUPPORTED_Y,P=n&&(!A||$||_||E||y((function(){return L[S]=!1,T(z)!=z||T(L)==L||"/a/i"!=T(z,"i")}))),Y=function(t){for(var e,a=t.length,n=0,r="",i=!1;n<=a;n++)e=j(t,n),"\\"!==e?i||"."!==e?("["===e?i=!0:"]"===e&&(i=!1),r+=e):r+="[\\s\\S]":r+=e+j(t,++n);return r},q=function(t){for(var e,a=t.length,n=0,r="",i=[],s={},o=!1,c=!1,d=0,l="";n<=a;n++){if(e=j(t,n),"\\"===e)e+=j(t,++n);else if("]"===e)o=!1;else if(!o)switch(!0){case"["===e:o=!0;break;case"("===e:I(O,M(t,n+1))&&(n+=2,c=!0),r+=e,d++;continue;case">"===e&&c:if(""===l||v(s,l))throw new C("Invalid capture group name");s[l]=!0,i[i.length]=[l,d],c=!1,l="";continue}c?l+=e:r+=e}return[r,i]};if(s("RegExp",P)){for(var H=function(t,e){var a,n,r,i,s,d,g=l(R,this),h=u(t),b=void 0===e,y=[],v=t;if(!g&&h&&b&&t.constructor===H)return t;if((h||l(R,t))&&(t=t.source,b&&(e=f(v))),t=void 0===t?"":p(t),e=void 0===e?"":p(e),v=t,_&&"dotAll"in z&&(n=!!e&&D(e,"s")>-1,n&&(e=k(e,/s/g,""))),a=e,$&&"sticky"in z&&(r=!!e&&D(e,"y")>-1,r&&F&&(e=k(e,/y/g,""))),E&&(i=q(t),t=i[0],y=i[1]),s=o(T(t,e),g?this:R,H),(n||r||y.length)&&(d=m(s),n&&(d.dotAll=!0,d.raw=H(Y(t),a)),r&&(d.sticky=!0),y.length&&(d.groups=y)),t!==v)try{c(s,"source",""===v?"(?:)":v)}catch(x){}return s},J=d(T),K=0;J.length>K;)h(H,T,J[K++]);R.constructor=H,H.prototype=R,b(r,"RegExp",H,{constructor:!0})}x("RegExp")},"85fa":function(t,e,a){"use strict";var n=a("607c"),r=a("f65d"),i=a("a30c"),s=a("dc58"),o=a("f129"),c=a("24e3"),d=a("8fde"),l=a("a2f3"),u=a("ccba");r("search",(function(t,e,a){return[function(e){var a=o(this),r=s(e)?void 0:l(e,t);return r?n(r,e,a):new RegExp(e)[t](d(a))},function(t){var n=i(this),r=d(t),s=a(e,n,r);if(s.done)return s.value;var o=n.lastIndex;c(o,0)||(n.lastIndex=0);var l=u(n,r);return c(n.lastIndex,o)||(n.lastIndex=o),null===l?-1:l.index}]}))},"98c9":function(t,e,a){"use strict";a.r(e);var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container",staticStyle:{padding:"0"}},[a("div",{staticClass:"search"},[a("p",[a("span",{staticClass:"label"},[t._v("注册时间：")]),a("el-date-picker",{staticStyle:{width:"340px"},attrs:{align:"right",type:"datetimerange",format:"yyyy-MM-dd HH:mm:ss","default-time":["00:00:00","23:59:59"],"range-separator":"至","start-placeholder":"时间开始","end-placeholder":"时间结束",size:"mini"},on:{change:t.dateChange},model:{value:t.search.date,callback:function(e){t.$set(t.search,"date",e)},expression:"search.date"}}),a("el-radio-group",{staticStyle:{"margin-left":"10px"},attrs:{size:"mini"},on:{change:t.dateTypeChange},model:{value:t.dateType,callback:function(e){t.dateType=e},expression:"dateType"}},[a("el-radio-button",{attrs:{label:"day0"}},[t._v("今天")]),a("el-radio-button",{attrs:{label:"day1"}},[t._v("昨天")]),a("el-radio-button",{attrs:{label:"day2"}},[t._v("前天")])],1)],1),a("p",[a("span",{staticClass:"label"},[t._v("用户ID：")]),a("el-input",{staticStyle:{width:"120px"},attrs:{type:"text",size:"mini",clearable:"",placeholder:"请输入用户id"},model:{value:t.search.user_id,callback:function(e){t.$set(t.search,"user_id",e)},expression:"search.user_id"}})],1),a("p",{staticStyle:{"padding-top":"10px"}},[a("span",{staticClass:"label"}),a("el-button",{attrs:{type:"primary",icon:"el-icon-check",size:"mini"},on:{click:t.doSearch}},[t._v("筛选")]),a("el-button",{staticStyle:{"margin-left":"10px"},attrs:{type:"default",plain:"",size:"mini"},on:{click:t.doInit}},[t._v("重置")])],1)]),a("div",{staticClass:"box-panel",staticStyle:{"padding-bottom":"10px","margin-bottom":"0"}},[a("el-table",{attrs:{data:t.dataList,stripe:"",size:"medium","header-cell-class-name":"bg-gray"}},[a("el-table-column",{attrs:{prop:"create_time",label:"观看时间",width:"160"}}),a("el-table-column",{attrs:{prop:"user_id",label:"用户ID",width:"100"}}),a("el-table-column",{attrs:{prop:"reward_num",label:"奖励",width:"100"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("span",[t._v(t._s(e.row.reward_num)+" 条")])]}}])}),a("el-table-column",{attrs:{prop:"ad_unit_id",label:"广告位ID"}})],1),a("div",{staticClass:"footer"},[a("div",{staticClass:"tongji"},[a("i",{staticClass:"el-icon-s-data text-green",staticStyle:{"font-size":"20px"}}),t._v(" 共观看 "),a("span",{staticClass:"text-green"},[t._v(" "+t._s(t.tongji.adCount)+" ")]),t._v(" 次，共发放奖励 "),a("span",{staticClass:"text-danger"},[t._v(" "+t._s(t.tongji.rewardTotal)+" ")]),t._v(" 条 ")]),a("el-pagination",{attrs:{"current-page":t.page,"page-size":t.pagesize,layout:"total, prev, pager, next",total:t.dataTotal},on:{"current-change":t.pageChange}})],1)],1)])},r=[],i=(a("592a"),a("85fa"),a("e334"),a("3404"),a("44c3"),a("0998"),a("b215"),a("2e04"),a("02f3")),s={data:function(){return{dataList:[],dataTotal:0,page:1,pagesize:15,dateType:"",search:{date:[],user_id:"",keyword:""},tongji:{adCount:0,rewardTotal:0}}},created:function(){this.doInit()},methods:{doInit:function(){this.dateType="",this.search={date:[],user_id:"",keyword:""},this.doSearch()},getList:function(){var t=this,e=Object.assign(this.search,{page:this.page,pagesize:this.pagesize});Object(i["a"])(e).then((function(e){t.dataList=e.data.list,t.dataTotal=e.data.count})),1===this.page&&Object(i["b"])(e).then((function(e){t.tongji=e.data}))},tableIndex:function(t){return this.pagesize*(this.page-1)+t+1},pageChange:function(t){this.page=t,this.getList()},doSearch:function(){this.page=1,this.getList()},dateChange:function(){this.dateType=""},dateTypeChange:function(){var t="",e=this.dateFormat(new Date,"yyyy-MM-dd"),a=this.dateFormat(new Date((new Date).setDate((new Date).getDate()-1)),"yyyy-MM-dd"),n=this.dateFormat(new Date((new Date).setDate((new Date).getDate()-2)),"yyyy-MM-dd");"day0"===this.dateType?t=e:"day1"===this.dateType?t=a:"day2"===this.dateType&&(t=n),this.search.date=[t+" 00:00:00",t+" 23:59:59"]},dateFormat:function(t,e){var a={"M+":t.getMonth()+1,"d+":t.getDate(),"h+":t.getHours(),"m+":t.getMinutes(),"s+":t.getSeconds(),"q+":Math.floor((t.getMonth()+3)/3),S:t.getMilliseconds()};for(var n in/(y+)/.test(e)&&(e=e.replace(RegExp.$1,(t.getFullYear()+"").substr(4-RegExp.$1.length))),a)new RegExp("("+n+")").test(e)&&(e=e.replace(RegExp.$1,1===RegExp.$1.length?a[n]:("00"+a[n]).substr((""+a[n]).length)));return e}}},o=s,c=(a("c83c"),a("3427")),d=Object(c["a"])(o,n,r,!1,null,"b291ef4e",null);e["default"]=d.exports},b215:function(t,e,a){var n=a("12f4"),r=a("47b0").MISSED_STICKY,i=a("d409"),s=a("3c2a"),o=a("e5b5").get,c=RegExp.prototype,d=TypeError;n&&r&&s(c,"sticky",{configurable:!0,get:function(){if(this!==c){if("RegExp"===i(this))return!!o(this).sticky;throw d("Incompatible receiver, RegExp required")}}})},bf52:function(t,e,a){var n=a("607c"),r=a("5169"),i=a("1449"),s=a("4459"),o=RegExp.prototype;t.exports=function(t){var e=t.flags;return void 0!==e||"flags"in o||r(t,"flags")||!i(o,t)?e:n(s,t)}},c83c:function(t,e,a){"use strict";a("1f81")}}]);