(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-cc43a6a4"],{"127e":function(e,t,i){"use strict";i("a080")},"9a65":function(e,t,i){"use strict";i("cb7e")},a080:function(e,t,i){},adab:function(e,t,i){"use strict";i.r(t);var r=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",{class:{mobile:e.isMobile,opened:e.treeOpened,closed:!e.treeOpened}},[i("div",{staticClass:"fox-tree"},[i("div",{staticClass:"box-search"},[i("el-input",{attrs:{placeholder:"输入关键词","prefix-icon":"el-icon-search",clearable:""},on:{input:e.doSearch},model:{value:e.keyword,callback:function(t){e.keyword=t},expression:"keyword"}})],1),i("el-tree",{ref:"tree",attrs:{data:e.articleTree,props:e.treeProps,"highlight-current":!0,"default-expand-all":!0,"render-content":e.renderTreeContent,"filter-node-method":e.treeFilter,"node-key":"key","empty-text":"无数据"},on:{"node-click":e.handleNodeClick}})],1),i("div",{staticClass:"book-main"},[i("div",{staticClass:"content",domProps:{innerHTML:e._s(e.article.content)}})])])},n=[],o=(i("38ac"),i("4161"),i("485a"),i("b775"));function c(e){return Object(o["a"])({url:"/article/getArticleTree",method:"post",data:e})}function l(e){return Object(o["a"])({url:"/article/getArticle",method:"post",data:e})}var s={data:function(){return{articleId:0,type:"",isMobile:!0,treeOpened:!0,articleTree:[],article:{},treeProps:{children:"son",label:"title"},keyword:""}},watch:{"$route.query":function(e){e.type&&(this.type=e.type,"help"===this.type&&e.id&&(this.articleId=e.id),this.getArticle())}},created:function(){var e=this.$route.query;if(e.type){if(this.type=e.type,"help"===this.type){if(!e.id)return;this.articleId=e.id}this.getArticle()}},mounted:function(){var e=this;this.isMobile=document.body.clientWidth<980,this.isMobile&&setTimeout((function(){e.treeOpened=!1}),800),this.getArticleTree(),window.onresize=function(){document.body.clientWidth<980?(e.isMobile=!0,e.treeOpened=!1):(e.isMobile=!1,e.treeOpened=!0)}},beforeDestroy:function(){window.onresize=null},methods:{getArticleTree:function(){var e=this;c().then((function(t){e.articleTree=t.data,setTimeout((function(){if(e.type){var t=e.type;"help"===e.type&&(!e.articleId&&e.articleTree[0]["son"].length>0&&(e.articleId=e.articleTree[0]["son"][0]["id"],e.getArticle()),console.log("article",e.articleId),t+=e.articleId),e.$refs.tree.setCurrentKey(t)}}),10)}))},renderTreeContent:function(e,t){t.node;var i=t.data;t.store;return i.isFolder?e("span",{style:"font-weight:bold;"},[i.title]):e("span",{class:"has-icon"},[e("i",{class:"el-icon-document"}),e("span",[i.title])])},handleNodeClick:function(e){if(!e.isFolder){if("help"===this.type){if(this.articleId===e.id)return}else if(this.type===e.type)return;this.$router.push({name:"Doc",query:{type:e.type,id:e.id}}),this.treeOpened=!1}},getArticle:function(){var e=this;this.type||this.articleId?l({type:this.type,id:this.articleId}).then((function(t){e.article=t.data})):this.article={title:"",content:""}},treeFilter:function(e,t){return!e||-1!==t.title.indexOf(e)},doSearch:function(){this.$refs.tree.filter(this.keyword)},treeToggle:function(){this.treeOpened=!this.treeOpened}}},a=s,d=(i("9a65"),i("127e"),i("3427")),u=Object(d["a"])(a,r,n,!1,null,"39795107",null);t["default"]=u.exports},cb7e:function(e,t,i){}}]);