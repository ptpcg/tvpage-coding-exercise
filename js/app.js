
//Cached Selectors

var _WINDOW = jQuery(window),

	//Layout
	_HEADER = jQuery("#header"),
	_MOBILE_MENU_BTN = jQuery("#mobile_menu"),
	_MOBILE_MENU = jQuery("#navi"),
	
	//Query View
	_QRY_BTN = jQuery("#qry_btn"),
	
	//History View
	_HISTORY_LIST = jQuery("#history_list"),
	_HISTORY_BTNS = jQuery(".history-lis"),
	
	//Report View
//	_CLOSE_REPORT = jQuery("#close_report"),
	_REPORT_GRAPH = jQuery("#report_graph"),
 	
	//Nav
	_NAV_LINKS = jQuery(".navi-a"),
	
	_JSDB = jQuery.jStorage;
	
//Globals
var _JSDATA = "./_jsdata/";
	
var _APP = {
	init:function(){
		this.nav.init();
		var ang = angular.module('pgCrawl',[]);
		ang.controller('appCtrl',_APP.angularCtrl);
	},
	nav:{
		init:function(){
			var toggleMobileMenu = function(e){
				e.preventDefault();
				_MOBILE_MENU.slideToggle();
			};
			_MOBILE_MENU_BTN.on("click",toggleMobileMenu);
			_WINDOW.resize(function(){
				if(_WINDOW.width() > 1024 && _MOBILE_MENU.is(":hidden")){
					_MOBILE_MENU.show();
				}
			});
		}
	},
	auth:{
		init:function(){
		    
			
		}
	},
	angularCtrl:function($scope){
		_APP.angular = $scope;
		
		$scope.user ={
			tkn:"",
			genTkn:function(){
				
			},
			email:"",
			auth:{
			    init:function(){
			    	//set session for user
			        $scope.user.auth.run("init");
			    },
			    run:function(mode){
    				
					console.log({mode:mode});
    				$scope.user.loading = true;
    				//success method
    				var runRtn = function(rtn){
    					console.log(rtn);
    					$scope.user._pwd = null;
    					if(rtn.ok){
    						$scope.user.loading = false;
    						console.log({mode:mode});
    						if(rtn.email){
    							$scope.user.authed = true;
    							$scope.user.email = rtn.email;
    							switch(mode){
    								default:
										swal({
											title:"Authenticated",
											text:"Your crawl history will be linked to "+rtn.email,
											type:"success"
										});
    								break;
    								case "init":
    									//do nothing
    								break;
    								case "reg":
										swal({
											title:"Registered!",
											text:"Your crawl history will be linked to "+rtn.email,
											type:"success"
										});
    									
    								break;
    							}
    						}else{
    							if(mode === "logout"){
    								$scope.user.authed = false;
									swal({
										title:"Logged Out",
										text:"Your crawl history will no longer be linked to "+rtn.email,
										type:"success"
									});
									
    							}
    						}
    						$scope.user.tkn = rtn.tkn;
    					}else{
    						
							if(mode == "reg"){
								swal({
									title:"Registation Failed",
									text:rtn.msg,
									type:"error"
								});
							}else{
								swal({
									title:"Authentication Failed",
									text:rtn.msg,
									type:"error"
								});
							}
    					}
    					
    					$scope.$apply();
    				},
    				//error method
    				runFail = function(){
    					swal({
    						title:"Error",
    						text:"Authentication Failed",
    						type:"error"
    					});
    				},
    				reqData = {};
    				
    				//find mode to set
    				//request data
    				switch(mode){
    					case "init":
		    				reqData = {
		    					sect:"user",
		    					act:"get"
		    				};
    					break;
    					case "logout":
		    				reqData = {
		    					sect:"user",
		    					act:"logout"
		    				};
    					break;
    					case "reg":
		    				reqData = {
		    					sect:"user",
		    					act:"reg",
								_email:$scope.user._email,
								_pwd:$scope.user._pwd
		    				};
    					break;
    					default:
							reqData = {
								sect:"user",
								act:"auth",
								_email:$scope.user._email,
								_pwd:$scope.user._pwd
							};
    					break;
    				}

    				
    				//ajax request
    				jQuery.ajax({
    				  url: _JSDATA,
    				  dataType: 'json',
    				  data: reqData,
    				  success: runRtn,
    				  error:runFail
    				});
    			},
			
				reg:function(){
				    $scope.user.auth.run("reg");
				}
			},
			logout:function(){
		        $scope.user.auth.run("logout");
			}
		}
			
		$scope.history = {
			list:[{link:"Nothing In Crawl History"}],
			getList:function(){
				
				$scope.history.loading = true;
				var runRtn = function(rtn){
					console.log(rtn);
					if(rtn.ok){
						$scope.history.loading = false;
						$scope.history.list = rtn.data;
						$scope.$apply();
					}
				},
				runFail = function(){
					swal("Crawl Error - Failed");
				},
				reqData = {
					sect:"user",
					act:"get_history"
				};
				
				jQuery.ajax({
				  url: _JSDATA,
				  dataType: 'json',
				  data: reqData,
				  success: runRtn,
				  error:runFail
				});
			},
			clearConf:function(){
				swal({   
					title: "Are you sure?",   
					text: "You will NOT be able to restore Crawl History",   
					type: "warning",   
					showCancelButton: true,   
					confirmButtonColor: "#DD6B55",   
					confirmButtonText: "Yes, Clear It!",   
					closeOnConfirm: false 
				}, 
				$scope.history.clear);
			},
			clear:function(){
				
				$scope.history.loading = true;
				var runRtn = function(rtn){
					console.log(rtn);
					if(rtn.ok){
						$scope.history.loading = false;
						$scope.history.list = rtn.data;
						$scope.$apply();
						swal({
							type:"success",
							title:"History Cleared!",
							text:"Your Crawl History Has Been Cleared"
						});
						$scope.history.getList();
					}
				},
				runFail = function(){
					swal("History Clear - Failed");
				},
				reqData = {
					sect:"user",
					act:"clear_history"
				};
				
				jQuery.ajax({
				  url: _JSDATA,
				  dataType: 'json',
				  data: reqData,
				  success: runRtn,
				  error:runFail
				});
			}
		}
		
		$scope.crawl = {
			url:"",
			depth:0,
			report:{
				btns:function(callbk){
					var domains = jQuery(".report-domain"),
						toggleLinks = function(){
							var domain = jQuery(this);
							domains.children(".link-float:visible").hide().promise().done(function(){
								var links = domain.children(".link-float");
								
								if(links.is(":hidden")){
									links.slideDown();
								}else{
									links.slideUp();
								}
							});							
						};
					domains.on("click",toggleLinks);
				},
				gen:function(rpt){
				    console.log(rpt);
					$scope.report = rpt;
					$scope.report.link_count =  $scope.report.domains.length;
					$scope.$apply();
					$scope.crawl.report.show();
				},
				graph:function(){
					var domains = $scope.report.domains,
						links = $scope.report.links,
						c = 0,
						domainData = {},
						graphCTX = $("#report_graph").get(0).getContext("2d");
					
					while(c < domains.length){
						domainData[c] =  links[domains[c]].length;
						c++;				
					}	
					var graphData = {
						labels: domains,
						datasets: [
							{
								label: domains,
								fillColor: "rgba(220,220,220,0.5)",
								strokeColor: "rgba(220,220,220,0.8)",
								highlightFill: "rgba(220,220,220,0.75)",
								highlightStroke: "rgba(220,220,220,1)",
								data:domainData
								
							}
						]
					},
					chart = new Chart(graphCTX);
					
					var graph = chart.Bar(graphData,{
						barShowStroke: false
					});
					
					console.log(graphData);
				},
				show:function(){
					this.btns();
					$scope.view.ch("report");
				}
			},
			run:function(){
				$scope.crawling = true;
				var runRtn = function(rtn){
					console.log(rtn);
					if(rtn.ok){
						$scope.crawling = false;
						$scope.crawl.url = "";
						$scope.crawl.depth = 0;
						$scope.$apply();
						$scope.crawl.report.gen(rtn.data);
						$scope.history.getList();
					}
				},
				runFail = function(){
					swal("Crawl Error - Failed");
				},
				crawlData = {
					sect:"crawl",
					user:$scope.user.tkn,
					url:$scope.crawl.url,
					minusR:$scope.crawl.depth
				};
				
				jQuery.ajax({
				  url: _JSDATA,
				  dataType: 'json',
				  data: crawlData,
				  success: runRtn,
				  error:runFail
				});
			}
		}
		
		$scope.view = {
			curr:"query",
			get:function(){
				
			},
			ch:function(go2){
				if(go2 !== this.curr){
					if(_WINDOW.width() < 1024 && _MOBILE_MENU.is(":visible")){
						_MOBILE_MENU.slideUp();
					}
					
					switch(go2){
						case "history":
							$scope.history.getList();
						break;
					}
					
					jQuery("#"+this.curr).fadeOut()
					.promise()
					.done(function(){
						jQuery("#"+go2).fadeIn();
						$scope.view.curr = go2;
						
						switch(go2){
							case "report":
								$scope.crawl.report.graph();
							break;
						}
					});
				}
			}
		}
		
		//grab user token
		_APP.angular.user.auth.init();
	}
};
(function(){
	_APP.init();
})();
	
