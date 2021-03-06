<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<div class="alert we7-page-alert">
	<p><i class="wi wi-info-sign"></i>从公众号应用或PC应用同步的会员信息,在小程序应用登录时会被判断为新用户,此处无法识别为同一会员；</p>
	<p><i class="wi wi-info-sign"></i>本小程序可操作的模块中，仅显示可进行同步的模块；</p>
	<p><i class="wi wi-info-sign"></i>可以把PC应用或公众号应用的会员、数据等信息同步至小程序应用当中；</p>
	<p><i class="wi wi-info-sign"></i>一个模块，只能同步了PC、公众号、小程序中的其中一种，且只可同步一个；</p>
	<p><i class="wi wi-info-sign"></i>若公众号A应用已同步本小程序，则本小程序同步公众号应用时，无法选择公众号A且公众号A不会显示在同步列表中，即两者不可相互同步,PC同理,小程序亦同理。</p>
</div>
<div class="we7-page-title">关联设置</div>
<div id="js-module-link-uniacid" ng-controller="moduleLinkUniacidCtrl" ng-cloak>
	<table class="table we7-table table-hover vertical-middle">
		<col width="180px" />
		<col width="140px"/>
		<col width="140px" />
		<tr>
			<th class="text-left">关联设置</th>
			<th class="text-left">公众号或PC</th>
			<th class="text-right">操作</th>
		</tr>
		<tr ng-repeat="module in versionInfo.modules" ng-if="versionInfo.modules">
			<td class="text-left">
				<img ng-src="{{module.logo}}" style="width:50px;height:50px;">
				{{module.title}}
			</td>
			<td class="text-left" ng-if="module.account">
				<img ng-src="{{module.account.logo}}" style="width:50px;height:50px;">
				<span>{{module.account.name}}</span>
			</td>
			<td class="text-left" ng-if="!module.account && !module.other_link">
				<span>暂无</span>
			</td>
			<td class="text-left" ng-if="!module.account && module.other_link">
				<span>
					已被
					<span ng-if="module.other_link.type == 1 || module.other_link.type == 3">公众号：<span class="color-default" ng-bind="module.other_link.name"></span></span>
					<span ng-if="module.other_link.type == 5">PC：<span class="color-default" ng-bind="module.other_link.name"></span></span>
					<span ng-if="module.other_link.type == 4 || module.other_link.type == 7">小程序：<span class="color-default" ng-bind="module.other_link.name"></span></span>
					同步
				</span>
			</td>
			<td>
				<div class="link-group" ng-if="module.account">
					<a href="javascript:;" ng-click="searchLinkAccount(module.name, 'webapp')">修改</a>
					<a href="javascript:;" ng-click="module_unlink_uniacid(module.name)">删除</a>
				</div>
				<div class="link-group" ng-if="!module.account && !module.other_link">
					<a href="javascript:;" ng-click="searchLinkAccount(module.name, 'webapp')">添加</a>
				</div>
				<div class="link-group" ng-if="!module.account && module.other_link">
					<a href="javascript:;">---</a>
				</div>
			</td>
		</tr>
	</table>
	<div class="modal fade uploader-modal module" id="show-account" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog we7-modal-dialog" style="width:800px">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">选择</h4>
				</div>
				<div class="modal-body material-content">
					<div class="material-nav">
						<a href="javascript:;" ng-click="tabChange(0)"  ng-class="{true:'active',false:''}[jurindex==0]" >PC</a>
						<a href="javascript:;" ng-click="tabChange(1)" ng-class="{true:'active',false:''}[jurindex==1]">公众号</a>
						<a href="javascript:;" ng-click="tabChange(2)" ng-class="{true:'active',false:''}[jurindex==2]">小程序</a>
					</div>
					<div class="panel-body we7-padding material-body" ng-show="jurindex==0" id="account-webapp">
						<div class="alert bg-light-gray">
							<p><i class="wi wi-info-sign color-default"></i>该应用必须同时支持PC应用</p>
							<p><i class="wi wi-info-sign color-default"></i>PC必须安装有该应用.</p>
							<p><i class="wi wi-info-sign color-default"></i>必须有PC和小程序的主管理员及以上权限</p>
						</div>
						<div class="form-group we7-padding" ng-show="loadingData">
							<span class="help-block text-center"><img src="./resource/images/loading.gif" alt="" width="45px"> 拼命加载中。。。</span>
						</div>
						<div class="row js-show-account-content" ng-show="!loadingData">
							<div class="col-sm-2" ng-repeat="account in linkWebappAccounts" ng-if="linkWebappAccounts">
								<div class="item" ng-click="selectLinkAccount(account, $event)">
									<img ng-src="{{account.logo}}" class="icon" alt="">
									<div class="name">{{account.name}}</div>
									<div class="mask">
										<span class="wi wi-right"></span>
									</div>
								</div>
							</div>
							<div class="col-sm-12 text-center" ng-if="linkWebappAccounts.length == 0">没有可以关联的PC</div>
						</div>
					</div>
					<div class="material-body" ng-show="jurindex==1" id="account-app">
						<div class="alert bg-light-gray">
							<p><i class="wi wi-info-sign color-default"></i>该应用必须同时支持公众号应用</p>
							<p><i class="wi wi-info-sign color-default"></i>公众号必须安装有该应用.</p>
							<p><i class="wi wi-info-sign color-default"></i>必须有公众号和小程序的主管理员及以上权限</p>
						</div>
						<div class="form-group we7-padding" ng-show="loadingData">
							<span class="help-block text-center"><img src="./resource/images/loading.gif" alt="" width="45px"> 拼命加载中。。。</span>
						</div>
						<div class="row js-show-account-content" ng-show="!loadingData">
							<div class="col-sm-2" ng-repeat="account in linkAppAccounts" ng-if="linkAppAccounts">
								<div class="item" ng-click="selectLinkAccount(account, $event)">
									<img ng-src="{{account.logo}}" class="icon">
									<div class="name">{{account.name}}</div>
									<div class="mask">
										<span class="wi wi-right"></span>
									</div>
								</div>
							</div>
							<div class="col-sm-12 text-center" ng-if="linkAppAccounts.length == 0">没有可以关联的公众号</div>
						</div>
					</div>
					<div class="material-body" ng-show="jurindex==2" id="account-wxapp">
						<div class="alert bg-light-gray">
							<p><i class="wi wi-info-sign color-default"></i>关联小程序只关联最高版本；</p>
							<p><i class="wi wi-info-sign color-default"></i>要关联的小程序最高版本必须打包有该应用，其他低版本打包该应用自动过滤；</p>
							<p><i class="wi wi-info-sign color-default"></i>必须同时有小程序的主管理员及以上权限。</p>
						</div>
						<div class="form-group we7-padding" ng-show="loadingData">
							<span class="help-block text-center"><img src="./resource/images/loading.gif" alt="" width="45px"> 拼命加载中。。。</span>
						</div>
						<div class="row js-show-account-content" ng-show="!loadingData">
							<div class="col-sm-2" ng-repeat="account in linkWxappAccounts" ng-if="linkWxappAccounts">
								<div class="item" ng-click="selectLinkAccount(account, $event)">
									<img ng-src="{{account.logo}}" class="icon">
									<div class="name">{{account.name}}</div>
									<div class="mask">
										<span class="wi wi-right"></span>
									</div>
								</div>
							</div>
							<div class="col-sm-12 text-center" ng-if="linkWxappAccounts.length == 0">没有可以关联的小程序</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" ng-click="moduleLinkUniacid()">确定</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	angular.module('wxApp').value('config', {
		'version_info': <?php echo !empty($version_info) ? json_encode($version_info) : 'null'?>,
		'token': "<?php  echo $_W['token']?>",
		'app': "<?php  echo ACCOUNT_TYPE_OFFCIAL_NORMAL?>",
		'webapp': "<?php  echo ACCOUNT_TYPE_WEBAPP_NORMAL?>",
		'wxapp': "<?php  echo ACCOUNT_TYPE_APP_NORMAL?>",
		'links' : {
			'search_link_account': "<?php  echo url('wxapp/module-link-uniacid/search_link_account')?>",
			'module_link_uniacid': "<?php  echo url('wxapp/module-link-uniacid')?>",
			'module_unlink_uniacid':"<?php  echo url('wxapp/module-link-uniacid/module_unlink_uniacid')?>"
		},
	});
	angular.bootstrap($('#js-module-link-uniacid'), ['wxApp']);
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>