(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-67b51bf9"],{"2f8d":function(t,e,i){"use strict";i.r(e);var o=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"app-container"},[i("div",{staticClass:"toolbar"},[i("div",[i("el-button-group",[i("el-button",{attrs:{type:"all"===t.search.topic_id?"primary":"default",size:"small"},on:{click:function(e){return t.changeTopic("all")}}},[t._v("全部类别")]),t._l(t.topicList,(function(e){return i("el-button",{attrs:{type:t.search.topic_id===e.id?"primary":"default",size:"small"},on:{click:function(i){return t.changeTopic(e.id)}}},[t._v(" "+t._s(e.title))])}))],2)],1),i("div",[i("el-button",{attrs:{type:"primary",icon:"el-icon-plus",size:"mini"},on:{click:t.clickAdd}},[t._v("添加模型")])],1)]),i("el-table",{attrs:{data:t.dataList,stripe:"",size:"medium","header-cell-class-name":"bg-gray"}},[i("el-table-column",{attrs:{prop:"weight",label:"权重",width:"60"}}),i("el-table-column",{attrs:{prop:"topic_title",label:"所属类别",width:"140"}}),i("el-table-column",{attrs:{prop:"title",label:"模型标题",width:"200"}}),i("el-table-column",{attrs:{prop:"desc",label:"描述",width:"350"}}),i("el-table-column",{attrs:{prop:"views",label:"点击量",width:"100"}}),i("el-table-column",{attrs:{prop:"usages",label:"使用量",width:"100"}}),i("el-table-column",{attrs:{prop:"votes",label:"收藏量",width:"100"}}),i("el-table-column",{attrs:{prop:"state",label:"启用",width:"80"},scopedSlots:t._u([{key:"default",fn:function(e){return[i("el-switch",{attrs:{"active-value":1,"inactive-value":0},on:{change:function(i){return t.setState(e.row.id,i)}},model:{value:e.row.state,callback:function(i){t.$set(e.row,"state",i)},expression:"scope.row.state"}})]}}])}),i("el-table-column",{attrs:{label:"操作"},scopedSlots:t._u([{key:"default",fn:function(e){return[i("el-button-group",[i("el-button",{attrs:{type:"text",size:"mini",icon:"el-icon-edit"},nativeOn:{click:function(i){return i.preventDefault(),t.clickEdit(e.row.id)}}},[t._v("编辑 ")]),i("el-button",{attrs:{type:"text text-danger",size:"mini",icon:"el-icon-delete"},nativeOn:{click:function(i){return i.preventDefault(),t.doDel(e.row.id)}}},[t._v("删除")])],1)]}}])})],1),i("el-pagination",{attrs:{"current-page":t.page,"page-size":t.pagesize,layout:"total, prev, pager, next",total:t.dataTotal},on:{"current-change":t.pageChange}}),t.form?i("div",[i("el-dialog",{attrs:{"custom-class":"my-dialog full-dialog",title:t.formTitle,width:"800px",visible:!0,"close-on-click-modal":!1,"before-close":t.formClose}},[i("el-form",{ref:"form",attrs:{model:t.form,rules:t.formRules,"label-width":"120px"}},[i("el-form-item",{attrs:{label:"权重",prop:"weight"}},[i("el-input",{staticStyle:{width:"110px"},attrs:{placeholder:"越大越靠前",size:"normal"},model:{value:t.form.weight,callback:function(e){t.$set(t.form,"weight",e)},expression:"form.weight"}}),i("span",{staticStyle:{color:"#666","margin-left":"10px"}},[t._v("越大越靠前")])],1),i("el-form-item",{attrs:{label:"所属类别",prop:"title"}},[i("el-select",{staticStyle:{width:"180px"},attrs:{placeholder:"选择所属类别",size:"normal"},model:{value:t.form.topic_id,callback:function(e){t.$set(t.form,"topic_id",e)},expression:"form.topic_id"}},t._l(t.topicList,(function(t,e){return i("el-option",{key:e,attrs:{label:t.title,value:t.id}})})),1)],1),i("el-form-item",{attrs:{label:"模型标题",prop:"title"}},[i("el-input",{staticStyle:{width:"400px"},attrs:{placeholder:"模型标题",size:"normal"},model:{value:t.form.title,callback:function(e){t.$set(t.form,"title",e)},expression:"form.title"}})],1),i("el-form-item",{attrs:{label:"描述",prop:"desc"}},[i("el-input",{staticStyle:{width:"500px"},attrs:{type:"textarea",rows:3,placeholder:"模型描述",size:"normal"},model:{value:t.form.desc,callback:function(e){t.$set(t.form,"desc",e)},expression:"form.desc"}})],1),i("el-form-item",{attrs:{label:"模型内容",prop:"prompt"}},[i("el-input",{staticStyle:{width:"500px"},attrs:{type:"textarea",rows:6,placeholder:"模型内容",size:"normal"},model:{value:t.form.prompt,callback:function(e){t.$set(t.form,"prompt",e)},expression:"form.prompt"}}),i("p",{staticStyle:{margin:"0","line-height":"24px","margin-top":"10px"}},[t._v("变量说明：")]),i("p",{staticStyle:{margin:"0","line-height":"24px",color:"#888"}},[t._v("用户输入内容："),i("span",{staticStyle:{color:"#04BABE"}},[t._v("[PROMPT]")])]),i("p",{staticStyle:{margin:"0","line-height":"24px",color:"#888"}},[t._v("输出语言："),i("span",{staticStyle:{color:"#04BABE"}},[t._v("[TARGETLANGGE]")])])],1),i("el-form-item",{attrs:{label:"提示文字",prop:"hint"}},[i("el-input",{staticStyle:{width:"500px"},attrs:{type:"textarea",rows:3,placeholder:"在输入框里提示用户的文字",size:"normal"},model:{value:t.form.hint,callback:function(e){t.$set(t.form,"hint",e)},expression:"form.hint"}})],1),i("el-form-item",{attrs:{label:"欢迎语",prop:"welcome"}},[i("el-input",{staticStyle:{width:"500px"},attrs:{type:"textarea",rows:3,placeholder:"可留空，默认使用提示文字",size:"normal"},model:{value:t.form.welcome,callback:function(e){t.$set(t.form,"welcome",e)},expression:"form.welcome"}})],1),i("el-form-item",{attrs:{label:"虚拟点击量",prop:"fake_views"}},[i("el-input",{staticStyle:{width:"110px"},attrs:{placeholder:"",size:"normal"},model:{value:t.form.fake_views,callback:function(e){t.$set(t.form,"fake_views",e)},expression:"form.fake_views"}})],1),i("el-form-item",{attrs:{label:"虚拟使用量",prop:"fake_usages"}},[i("el-input",{staticStyle:{width:"110px"},attrs:{placeholder:"",size:"normal"},model:{value:t.form.fake_usages,callback:function(e){t.$set(t.form,"fake_usages",e)},expression:"form.fake_usages"}})],1),i("el-form-item",{attrs:{label:"虚拟收藏量",prop:"fake_votes"}},[i("el-input",{staticStyle:{width:"110px"},attrs:{placeholder:"",size:"normal"},model:{value:t.form.fake_votes,callback:function(e){t.$set(t.form,"fake_votes",e)},expression:"form.fake_votes"}})],1)],1),i("span",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[i("el-button",{attrs:{type:"default",icon:"el-icon-close",size:"small"},on:{click:t.formClose}},[t._v("取 消")]),i("el-button",{attrs:{type:"primary",icon:"el-icon-check",size:"small"},on:{click:t.doSubmit}},[t._v("提 交")])],1)],1)],1):t._e()],1)},r=[],a=(i("c36d"),i("7a70"),i("3441")),l={data:function(){return{form:null,formType:null,dataList:[],dataTotal:0,topicList:[],page:1,pagesize:10,search:{topic_id:"all"},formRules:{title:[{required:!0,message:"此项必填",trigger:"blur"}],desc:[{required:!0,message:"此项必填",trigger:"blur"}],prompt:[{required:!0,message:"此项必填",trigger:"blur"}],hint:[{required:!0,message:"此项必填",trigger:"blur"}]}}},computed:{formTitle:function(){return"add"===this.formType?"添加模型":"编辑"}},mounted:function(){this.getTopicList(),this.getList()},methods:{getTopicList:function(){var t=this;Object(a["i"])().then((function(e){t.topicList=e.data}))},getList:function(){var t=this;Object(a["g"])({page:this.page,pagesize:this.pagesize,topic_id:this.search.topic_id}).then((function(e){t.dataList=e.data.list,t.dataTotal=e.data.count}))},pageChange:function(t){this.page=t,this.getList()},clickAdd:function(){this.formType="add",this.form={weight:100}},clickEdit:function(t){var e=this;Object(a["f"])({id:t}).then((function(t){e.formType="edit",e.form=t.data}))},formClose:function(){this.form=null,this.formType=""},doSubmit:function(){var t=this;this.$refs.form.validate((function(e){e?Object(a["j"])(t.form).then((function(e){t.getList(),t.$message({message:e.message,type:"success",duration:1500}),t.formClose()})):console.log("请填写必填项")}))},doDel:function(t){var e=this;this.$confirm("删除后不可回复，确定删除吗?","提示",{confirmButtonText:"确定删除",cancelButtonText:"取消",type:"warning"}).then((function(){Object(a["b"])({id:t}).then((function(t){e.getList(),e.$message({message:t.message,type:"success",duration:1500})}))}))},setState:function(t,e){var i=this;Object(a["l"])({id:t,state:e}).then((function(t){i.getList(),i.$message({message:t.message,type:"success",duration:1500})}))},changeTopic:function(t){this.search.topic_id=t,this.page=1,this.getList()}}},n=l,s=(i("4076"),i("3427")),c=Object(s["a"])(n,o,r,!1,null,"3ea57ef8",null);e["default"]=c.exports},3441:function(t,e,i){"use strict";i.d(e,"d",(function(){return r})),i.d(e,"e",(function(){return a})),i.d(e,"a",(function(){return l})),i.d(e,"i",(function(){return n})),i.d(e,"h",(function(){return s})),i.d(e,"k",(function(){return c})),i.d(e,"c",(function(){return u})),i.d(e,"m",(function(){return p})),i.d(e,"g",(function(){return d})),i.d(e,"f",(function(){return f})),i.d(e,"j",(function(){return m})),i.d(e,"b",(function(){return h})),i.d(e,"l",(function(){return b}));var o=i("b775");function r(t){return Object(o["a"])({url:"/write/getMsgList",method:"post",data:t})}function a(t){return Object(o["a"])({url:"/write/getMsgTongji",method:"post",data:t})}function l(t){return Object(o["a"])({url:"/write/delMsg",method:"post",data:t})}function n(t){return Object(o["a"])({url:"/write/getTopicList",method:"post",data:t})}function s(t){return Object(o["a"])({url:"/write/getTopic",method:"post",data:t})}function c(t){return Object(o["a"])({url:"/write/saveTopic",method:"post",data:t})}function u(t){return Object(o["a"])({url:"/write/delTopic",method:"post",data:t})}function p(t){return Object(o["a"])({url:"/write/setTopicState",method:"post",data:t})}function d(t){return Object(o["a"])({url:"/write/getPromptList",method:"post",data:t})}function f(t){return Object(o["a"])({url:"/write/getPrompt",method:"post",data:t})}function m(t){return Object(o["a"])({url:"/write/savePrompt",method:"post",data:t})}function h(t){return Object(o["a"])({url:"/write/delPrompt",method:"post",data:t})}function b(t){return Object(o["a"])({url:"/write/setPromptState",method:"post",data:t})}},"3d53":function(t,e){t.exports=Object.is||function(t,e){return t===e?0!==t||1/t===1/e:t!=t&&e!=e}},4076:function(t,e,i){"use strict";i("563b")},"563b":function(t,e,i){},"7a70":function(t,e,i){"use strict";var o=i("10b0"),r=i("e67f"),a=i("2f89"),l=i("4b5b"),n=i("57eb"),s=i("3d53"),c=i("f8b7"),u=i("5de4"),p=i("4f00");r("search",(function(t,e,i){return[function(e){var i=n(this),r=l(e)?void 0:u(e,t);return r?o(r,e,i):new RegExp(e)[t](c(i))},function(t){var o=a(this),r=c(t),l=i(e,o,r);if(l.done)return l.value;var n=o.lastIndex;s(n,0)||(o.lastIndex=0);var u=p(o,r);return s(o.lastIndex,n)||(o.lastIndex=n),null===u?-1:u.index}]}))}}]);