<?php defined('IN_IA') or exit('Access Denied');?><!--
 * 参数设置
 * ============================================================================
 * 版权所有 2015-2018 微课堂团队，并保留所有权利。
 * 网站地址: https://wx.haoshu888.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！不允许对程序代码以任何形式任何目的的再发布，作者将保留
 * 追究法律责任的权力和最终解释权。
 * ============================================================================
-->

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<style type="text/css">
.red{color:red;}
</style>
<div class="main">
	<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">
        <div class="panel panel-default">
            <div class="panel-heading">参数设置  <span class="red">[以下所有参数不修改的选项请留空]</span></div>
            <div class="panel-body">
				<div class="form-group">
					<label class="col-sm-2 control-label">[立即购买]</label>
					<div class="col-sm-10">
						<div class="input-group">
							<span class="input-group-addon">自定义名称</span>
							<input type="text" name="buynow_name" value="<?php  echo $settings['buynow_name'];?>" class="form-control">
							<span class="input-group-addon">网页链接</span>
							<input type="text" name="buynow_link" value="<?php  echo $settings['buynow_link'];?>" class="form-control">
						</div>
						<span class="help-block">默认请留空，课程详情页右下角“立即购买”自定义名称，网页链接应包含http://或https://</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">[开始学习]</label>
					<div class="col-sm-10">
						<div class="input-group">
							<span class="input-group-addon">自定义名称</span>
							<input type="text" name="study_name" value="<?php  echo $settings['study_name'];?>" class="form-control">
							<span class="input-group-addon">网页链接</span>
							<input type="text" name="study_link" value="<?php  echo $settings['study_link'];?>" class="form-control">
						</div>
						<span class="help-block">默认请留空，课程详情页右下角“开始学习”自定义名称，网页链接应包含http://或https://</span>
					</div>
				</div>
				<div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">客服系统链接</label>
                    <div class="col-sm-10">
						<input type="text" name="service_url" value="<?php  echo $settings['service_url'];?>" class="form-control">
						<span class="help-block">第三方客服系统链接请以http://或https://开头，<strong>设置该客服系统链接后，将覆盖讲师QQ、QQ群和微信等咨询方式。</strong></span>
                    </div>
                </div>
				<div class="form-group">
					<label class="col-sm-2 control-label">课程咨询方式</label>
					<div class="col-sm-10">
						<div class="input-group">
							<span class="input-group-addon">讲师QQ</span>
							<input type="text" name="teacher_qq" value="<?php  echo $settings['teacher_qq'];?>" class="form-control">
							<span class="input-group-addon">QQ群</span>
							<input type="text" name="teacher_qqgroup" value="<?php  echo $settings['teacher_qqgroup'];?>" class="form-control">
							<span class="input-group-addon">加QQ群链接</span>
							<input type="text" name="teacher_qqlink" value="<?php  echo $settings['teacher_qqlink'];?>" placeholder="包含http://或https://" class="form-control">
						</div>
						<span class="help-block">设置统一的课程咨询方式，将覆盖讲师个人的咨询方式</span>
					</div>
				</div>
				<div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">课程咨询二维码</label>
                    <div class="col-sm-9">
						<?php  echo tpl_form_field_image('teacher_qrcode', $settings['teacher_qrcode']);?>
						<span class="help-block">课程咨询方式二维码，设置该二维码将覆盖讲师个人的二维码</span>
                    </div>
                </div>
				<div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">个人中心背景图</label>
                    <div class="col-sm-9">
						<?php  echo tpl_form_field_image('ucenter_bg', $settings['ucenter_bg']);?>
						<span class="help-block">建议尺寸 534px * 300px，JPG格式</span>
                    </div>
                </div>
				<div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">VIP服务背景图</label>
                    <div class="col-sm-9">
						<?php  echo tpl_form_field_image('vip_bg', $settings['vip_bg']);?>
						<span class="help-block">建议尺寸 750px * 514px，JPG格式</span>
                    </div>
                </div>
				<div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">讲师主页背景图</label>
                    <div class="col-sm-9">
						<?php  echo tpl_form_field_image('teacher_bg', $settings['teacher_bg']);?>
						<span class="help-block">建议尺寸 500px * 220px，JPG格式</span>
                    </div>
                </div>
				<div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">手机端首页底部图片</label>
                    <div class="col-sm-9">
						<?php  echo tpl_form_field_image('index_slogan', $settings['index_slogan']);?>
						<span class="help-block">建议尺寸 750px * 26px，PNG格式</span>
                    </div>
                </div>
				<div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">统计代码</label>
                    <div class="col-sm-9">
						<textarea class="form-control" name="statis_code" style="height:90px;"><?php  echo $settings['statis_code'];?></textarea>
						<span class="help-block">例如CNZZ站点统计的js代码</span>
                    </div>
                </div>
				<div class="form-group">
					<label class="col-sm-2 control-label red">[首页]</label>
					<div class="col-sm-10">
						<div class="input-group">
							<span class="input-group-addon">导航名称</span>
							<input type="text" name="indexs_name" value="<?php  echo $settings['indexs_name'];?>" class="form-control">
							<span class="input-group-addon">链接</span>
							<input type="text" name="indexs_link" value="<?php  echo $settings['indexs_link'];?>" class="form-control">
							<span class="input-group-addon">图标</span>
							<input type="text" name="indexs_icon" value="<?php  echo $settings['indexs_icon'];?>" class="form-control">
						</div>
						<span class="help-block">
							<strong class="red">底部导航栏参数均可为空</strong><br/>
							图标请参考<a href="http://fontawesome.dashgame.com/" target="_blank" style="color:#0f7cda;">Font Awesome图标集</a>，例如"address-book"、"bandcamp"
						</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">[全部课程]</label>
					<div class="col-sm-10">
						<div class="input-group">
							<span class="input-group-addon">导航名称</span>
							<input type="text" name="searchs_name" value="<?php  echo $settings['searchs_name'];?>" class="form-control">
							<span class="input-group-addon">链接</span>
							<input type="text" name="searchs_link" value="<?php  echo $settings['searchs_link'];?>" class="form-control">
							<span class="input-group-addon">图标</span>
							<input type="text" name="searchs_icon" value="<?php  echo $settings['searchs_icon'];?>" class="form-control">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">[自定义导航]</label>
					<div class="col-sm-10">
						<div class="input-group">
							<span class="input-group-addon">导航名称</span>
							<input type="text" name="diynav_name" value="<?php  echo $settings['diynav_name'];?>"  class="form-control">
							<span class="input-group-addon">链接</span>
							<input type="text" name="diynav_link" value="<?php  echo $settings['diynav_link'];?>" class="form-control">
							<span class="input-group-addon">图标</span>
							<input type="text" name="diynav_icon" value="<?php  echo $settings['diynav_icon'];?>" class="form-control">
						</div>
						<span class="help-block">
							如需开启该导航，请在“基本设置~手机端显示~底部自选菜单”里选用，此处的自定义导航会覆盖您在“基本设置~手机端显示~底部自选菜单”里的选择
						</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">[我的课程]</label>
					<div class="col-sm-10">
						<div class="input-group">
							<span class="input-group-addon">导航名称</span>
							<input type="text" name="lessons_name" value="<?php  echo $settings['lessons_name'];?>"  class="form-control">
							<span class="input-group-addon">链接</span>
							<input type="text" name="lessons_link" value="<?php  echo $settings['lessons_link'];?>" class="form-control">
							<span class="input-group-addon">图标</span>
							<input type="text" name="lessons_icon" value="<?php  echo $settings['lessons_icon'];?>" class="form-control">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">[个人中心]</label>
					<div class="col-sm-10">
						<div class="input-group">
							<span class="input-group-addon">导航名称</span>
							<input type="text" name="selfs_name" value="<?php  echo $settings['selfs_name'];?>" class="form-control">
							<span class="input-group-addon">链接</span>
							<input type="text" name="selfs_link" value="<?php  echo $settings['selfs_link'];?>" class="form-control">
							<span class="input-group-addon">图标</span>
							<input type="text" name="selfs_icon" value="<?php  echo $settings['selfs_icon'];?>" class="form-control">
						</div>
					</div>
				</div>
            </div>
        </div>

        <div class="form-group col-sm-12">
            <input type="submit" name="submit" value="保存设置" class="btn btn-primary col-lg-1" />
            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
        </div>
	</form>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>