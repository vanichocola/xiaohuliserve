(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-b12e82e6"],{"0998":function(t,e,a){var n=a("12f4"),i=a("11e4"),s=a("d409"),o=a("3c2a"),r=a("e5b5").get,l=RegExp.prototype,c=TypeError;n&&i&&o(l,"dotAll",{configurable:!0,get:function(){if(this!==l){if("RegExp"===s(this))return!!r(this).dotAll;throw c("Incompatible receiver, RegExp required")}}})},"24e3":function(t,e){t.exports=Object.is||function(t,e){return t===e?0!==t||1/t===1/e:t!=t&&e!=e}},"2e04":function(t,e,a){"use strict";var n=a("2d29").PROPER,i=a("968d"),s=a("a30c"),o=a("8fde"),r=a("baf9"),l=a("bf52"),c="toString",d=RegExp.prototype,u=d[c],g=r((function(){return"/a/b"!=u.call({source:"a",flags:"b"})})),p=n&&u.name!=c;(g||p)&&i(RegExp.prototype,c,(function(){var t=s(this),e=o(t.source),a=o(l(t));return"/"+e+"/"+a}),{unsafe:!0})},"44c3":function(t,e,a){var n=a("12f4"),i=a("bf5d"),s=a("b303"),o=a("95df"),r=a("2fca"),l=a("80b0"),c=a("a6bf").f,d=a("1449"),u=a("6a5e"),g=a("8fde"),p=a("bf52"),f=a("47b0"),h=a("9d67"),b=a("968d"),y=a("baf9"),m=a("5169"),v=a("e5b5").enforce,x=a("8bf1"),w=a("3192"),_=a("11e4"),k=a("f715"),M=w("match"),S=i.RegExp,D=S.prototype,E=i.SyntaxError,T=s(D.exec),C=s("".charAt),R=s("".replace),z=s("".indexOf),I=s("".slice),O=/^\?<[^\s\d!#%&*+<=>@^][^\s!#%&*+<=>@^]*>/,j=/a/g,L=/a/g,$=new S(j)!==j,A=f.MISSED_STICKY,P=f.UNSUPPORTED_Y,F=n&&(!$||A||_||k||y((function(){return L[M]=!1,S(j)!=j||S(L)==L||"/a/i"!=S(j,"i")}))),H=function(t){for(var e,a=t.length,n=0,i="",s=!1;n<=a;n++)e=C(t,n),"\\"!==e?s||"."!==e?("["===e?s=!0:"]"===e&&(s=!1),i+=e):i+="[\\s\\S]":i+=e+C(t,++n);return i},Y=function(t){for(var e,a=t.length,n=0,i="",s=[],o={},r=!1,l=!1,c=0,d="";n<=a;n++){if(e=C(t,n),"\\"===e)e+=C(t,++n);else if("]"===e)r=!1;else if(!r)switch(!0){case"["===e:r=!0;break;case"("===e:T(O,I(t,n+1))&&(n+=2,l=!0),i+=e,c++;continue;case">"===e&&l:if(""===d||m(o,d))throw new E("Invalid capture group name");o[d]=!0,s[s.length]=[d,c],l=!1,d="";continue}l?d+=e:i+=e}return[i,s]};if(o("RegExp",F)){for(var q=function(t,e){var a,n,i,s,o,c,f=d(D,this),h=u(t),b=void 0===e,y=[],m=t;if(!f&&h&&b&&t.constructor===q)return t;if((h||d(D,t))&&(t=t.source,b&&(e=p(m))),t=void 0===t?"":g(t),e=void 0===e?"":g(e),m=t,_&&"dotAll"in j&&(n=!!e&&z(e,"s")>-1,n&&(e=R(e,/s/g,""))),a=e,A&&"sticky"in j&&(i=!!e&&z(e,"y")>-1,i&&P&&(e=R(e,/y/g,""))),k&&(s=Y(t),t=s[0],y=s[1]),o=r(S(t,e),f?this:D,q),(n||i||y.length)&&(c=v(o),n&&(c.dotAll=!0,c.raw=q(H(t),a)),i&&(c.sticky=!0),y.length&&(c.groups=y)),t!==m)try{l(o,"source",""===m?"(?:)":m)}catch(x){}return o},B=c(S),J=0;B.length>J;)h(q,S,B[J++]);D.constructor=q,q.prototype=D,b(i,"RegExp",q,{constructor:!0})}x("RegExp")},"85fa":function(t,e,a){"use strict";var n=a("607c"),i=a("f65d"),s=a("a30c"),o=a("dc58"),r=a("f129"),l=a("24e3"),c=a("8fde"),d=a("a2f3"),u=a("ccba");i("search",(function(t,e,a){return[function(e){var a=r(this),i=o(e)?void 0:d(e,t);return i?n(i,e,a):new RegExp(e)[t](c(a))},function(t){var n=s(this),i=c(t),o=a(e,n,i);if(o.done)return o.value;var r=n.lastIndex;l(r,0)||(n.lastIndex=0);var d=u(n,i);return l(n.lastIndex,r)||(n.lastIndex=r),null===d?-1:d.index}]}))},b215:function(t,e,a){var n=a("12f4"),i=a("47b0").MISSED_STICKY,s=a("d409"),o=a("3c2a"),r=a("e5b5").get,l=RegExp.prototype,c=TypeError;n&&i&&o(l,"sticky",{configurable:!0,get:function(){if(this!==l){if("RegExp"===s(this))return!!r(this).sticky;throw c("Incompatible receiver, RegExp required")}}})},b958:function(t,e,a){"use strict";a.r(e);var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container",staticStyle:{padding:"0"}},[a("div",{staticClass:"search"},[a("p",[a("span",{staticClass:"label"},[t._v("提问时间：")]),a("el-date-picker",{staticStyle:{width:"340px"},attrs:{align:"right",type:"datetimerange",format:"yyyy-MM-dd HH:mm:ss","default-time":["00:00:00","23:59:59"],"range-separator":"至","start-placeholder":"时间开始","end-placeholder":"时间结束",size:"mini"},on:{change:t.dateChange},model:{value:t.search.date,callback:function(e){t.$set(t.search,"date",e)},expression:"search.date"}}),a("el-radio-group",{staticStyle:{"margin-left":"10px"},attrs:{size:"mini"},on:{change:t.dateTypeChange},model:{value:t.dateType,callback:function(e){t.dateType=e},expression:"dateType"}},[a("el-radio-button",{attrs:{label:"day0"}},[t._v("今天")]),a("el-radio-button",{attrs:{label:"day1"}},[t._v("昨天")]),a("el-radio-button",{attrs:{label:"day2"}},[t._v("前天")])],1)],1),a("p",[a("span",{staticClass:"label"},[t._v("用户ID：")]),a("el-input",{staticStyle:{width:"120px"},attrs:{type:"text",size:"mini",clearable:"",placeholder:"请输入用户id"},model:{value:t.search.user_id,callback:function(e){t.$set(t.search,"user_id",e)},expression:"search.user_id"}}),a("span",{staticClass:"label"},[t._v("关键词：")]),a("el-input",{staticStyle:{width:"200px"},attrs:{type:"text",size:"mini",clearable:"",placeholder:"请输入关键词"},model:{value:t.search.keyword,callback:function(e){t.$set(t.search,"keyword",e)},expression:"search.keyword"}})],1),a("p",{staticStyle:{"padding-top":"10px"}},[a("span",{staticClass:"label"}),a("el-button",{attrs:{type:"primary",icon:"el-icon-check",size:"mini"},on:{click:t.doSearch}},[t._v("筛选")]),a("el-button",{staticStyle:{"margin-left":"10px"},attrs:{type:"default",plain:"",size:"mini"},on:{click:t.doInit}},[t._v("重置")]),a("el-button",{staticStyle:{"margin-left":"50px"},attrs:{type:"text",plain:"",size:"mini",icon:"el-icon-link"},on:{click:t.toOldMsg}},[t._v("查看已归档对话")])],1)]),a("div",{staticClass:"box-panel",staticStyle:{"padding-bottom":"10px","margin-bottom":"0"}},[a("el-table",{attrs:{data:t.dataList,stripe:"",size:"medium","header-cell-class-name":"bg-gray"}},[a("el-table-column",{attrs:{prop:"id",label:"ID",width:"60"}}),a("el-table-column",{attrs:{prop:"create_time",label:"时间",width:"170"}}),a("el-table-column",{attrs:{prop:"user_id",label:"用户ID",width:"80"}}),a("el-table-column",{attrs:{label:"用户提问","min-width":"300"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("span",{domProps:{innerHTML:t._s(e.row.message)}}),e.row.message_input?a("el-button",{staticStyle:{"margin-left":"10px"},attrs:{type:"text",size:"small"},on:{click:function(a){return t.showMsgDialog(e.row.message_input)}}},[t._v("查看原文")]):t._e()]}}])}),a("el-table-column",{attrs:{label:"AI回复",width:"120"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-button",{staticStyle:{"margin-left":"10px"},attrs:{type:"text",size:"small"},on:{click:function(a){return t.showMsgDialog(e.row.response)}}},[t._v("查看回复")])]}}])}),a("el-table-column",{attrs:{prop:"total_tokens",label:"消耗tokens",width:"100"}}),a("el-table-column",{attrs:{label:"操作",width:"120"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-button",{attrs:{type:"text text-danger",size:"mini",icon:"el-icon-delete"},nativeOn:{click:function(a){return a.preventDefault(),t.doDel(e.row.id)}}},[t._v("删除")])]}}])})],1),a("div",{staticClass:"footer"},[a("div",{staticClass:"tongji"},[a("i",{staticClass:"el-icon-s-data text-green",staticStyle:{"font-size":"20px"}}),t._v(" 共 "),a("span",{staticClass:"text-green"},[t._v(" "+t._s(t.tongji.msgCount)+" ")]),t._v(" 条问题， "),a("span",{staticClass:"text-danger"},[t._v(" "+t._s(t.tongji.msgTokens)+" ")]),t._v(" tokens ")]),a("el-pagination",{attrs:{"current-page":t.page,"page-size":t.pagesize,layout:"total, prev, pager, next",total:t.dataTotal},on:{"current-change":t.pageChange}})],1),t.dialogMessage?a("div",[a("el-dialog",{attrs:{"custom-class":"my-dialog",title:"查看原文",width:"600px",visible:!0,"close-on-click-modal":!0,"before-close":t.closeMsgDialog}},[a("p",{staticStyle:{padding:"0","font-size":"14px","line-height":"24px",color:"#333"},domProps:{innerHTML:t._s(t.dialogMessage)}}),a("span",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[a("el-button",{attrs:{type:"default",icon:"el-icon-close",size:"small"},on:{click:t.closeMsgDialog}},[t._v("关 闭")])],1)])],1):t._e()],1)])},i=[],s=(a("592a"),a("85fa"),a("e334"),a("3404"),a("44c3"),a("0998"),a("b215"),a("2e04"),a("d800")),o={data:function(){return{dataList:[],dataTotal:0,page:1,pagesize:20,dateType:"",search:{date:[],user_id:"",keyword:""},tongji:{msgCount:0,msgTokens:0},dialogMessage:""}},created:function(){this.doInit()},methods:{doInit:function(){this.dateType="",this.search={date:[],user_id:"",keyword:""},this.doSearch()},getList:function(){var t=this,e=Object.assign(this.search,{page:this.page,pagesize:this.pagesize});Object(s["b"])(e).then((function(e){t.dataList=e.data.list,t.dataTotal=e.data.count})),1===this.page&&Object(s["c"])(e).then((function(e){t.tongji=e.data}))},tableIndex:function(t){return this.pagesize*(this.page-1)+t+1},pageChange:function(t){this.page=t,this.getList()},doSearch:function(){this.page=1,this.getList()},dateChange:function(){this.dateType=""},dateTypeChange:function(){var t="",e=this.dateFormat(new Date,"yyyy-MM-dd"),a=this.dateFormat(new Date((new Date).setDate((new Date).getDate()-1)),"yyyy-MM-dd"),n=this.dateFormat(new Date((new Date).setDate((new Date).getDate()-2)),"yyyy-MM-dd");"day0"===this.dateType?t=e:"day1"===this.dateType?t=a:"day2"===this.dateType&&(t=n),this.search.date=[t+" 00:00:00",t+" 23:59:59"]},dateFormat:function(t,e){var a={"M+":t.getMonth()+1,"d+":t.getDate(),"h+":t.getHours(),"m+":t.getMinutes(),"s+":t.getSeconds(),"q+":Math.floor((t.getMonth()+3)/3),S:t.getMilliseconds()};for(var n in/(y+)/.test(e)&&(e=e.replace(RegExp.$1,(t.getFullYear()+"").substr(4-RegExp.$1.length))),a)new RegExp("("+n+")").test(e)&&(e=e.replace(RegExp.$1,1===RegExp.$1.length?a[n]:("00"+a[n]).substr((""+a[n]).length)));return e},showMsgDialog:function(t){this.dialogMessage=t},closeMsgDialog:function(){this.dialogMessage=""},doDel:function(t){var e=this;this.$confirm("确定删除吗?","提示",{confirmButtonText:"确定删除",cancelButtonText:"取消",type:"warning"}).then((function(){Object(s["a"])({id:t}).then((function(t){e.getList(),e.$message.success(t.message)}))}))},toOldMsg:function(){window.open("/admin/#/msg/index","_blank")}}},r=o,l=(a("dc85"),a("3427")),c=Object(l["a"])(r,n,i,!1,null,"6dd5fa52",null);e["default"]=c.exports},bf52:function(t,e,a){var n=a("607c"),i=a("5169"),s=a("1449"),o=a("4459"),r=RegExp.prototype;t.exports=function(t){var e=t.flags;return void 0!==e||"flags"in r||i(t,"flags")||!s(r,t)?e:n(o,t)}},c502:function(t,e,a){},d800:function(t,e,a){"use strict";a.d(e,"b",(function(){return i})),a.d(e,"c",(function(){return s})),a.d(e,"a",(function(){return o})),a.d(e,"d",(function(){return r})),a.d(e,"e",(function(){return l}));var n=a("b775");function i(t){return Object(n["a"])({url:"/msg/getMsgList",method:"post",data:t})}function s(t){return Object(n["a"])({url:"/msg/getMsgTongji",method:"post",data:t})}function o(t){return Object(n["a"])({url:"/msg/delMsg",method:"post",data:t})}function r(t){return Object(n["a"])({url:"/msg/getOldMsgList",method:"post",data:t})}function l(t){return Object(n["a"])({url:"/msg/getOldMsgTongji",method:"post",data:t})}},dc85:function(t,e,a){"use strict";a("c502")}}]);