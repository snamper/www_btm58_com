<?php
/**
 * 增加课程订单
 * ============================================================================
 * 版权所有 2015-2018 微课堂团队，并保留所有权利。
 * 网站地址: https://wx.haoshu888.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！不允许对程序代码以任何形式任何目的的再发布，作者将保留
 * 追究法律责任的权力和最终解释权。
 */
checkauth();
$lesson_id = intval($_GPC['id']); /* 课程id */
$uid = $_W['member']['uid'];

/* 检查黑名单操作 */
$this->check_black_list();

/* 检查用户是否完善手机号码/姓名 */
$member = pdo_fetch("SELECT a.*,b.credit1,b.nickname,b.mobile,b.realname,b.msn,b.occupation,b.company,b.graduateschool,b.grade,b.address FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename($this->table_mc_members). " b ON a.uid=b.uid WHERE a.uid=:uid", array(':uid'=>$uid));

$lesson_list = array();

$meanwhile_id = intval($_GPC['meanwhile_id']);  /* 是否同时购 */

if($meanwhile_id){
    $meanwhile_list = pdo_fetchall('SELECT * FROM ' . tablename($this->table_meanwhile_lesson) . ' WHERE meanwhileid=:id', array(':id' => $meanwhile_id));
    foreach($meanwhile_list as $meanwhile){
        $lesson_list[] = array('id' => $meanwhile['lesson_id'],'spec_id' => $meanwhile['spec_id']);
    }
}else{
    if (empty($lesson_id)) {
        message("参数缺失！", "", "error");
    }

    $lesson_list[] = array('id' => $lesson_id, 'spec_id' => intval($_GPC['spec_id']));
}

$lessons = array();

foreach($lesson_list as $key => $item){
    $id = intval($item['id']);

    $conditions = 'status>=:status AND uid=:uid AND is_delete=:is_delete AND lesson_ids LIKE :ids';
    $params = array(':status'=>0,':uid'=>$uid,':is_delete'=>0,':ids' => '%,' . $id . ',%');
    $lessonorder = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE " . $conditions . " LIMIT 1", $params);
    //$lessonorder = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE uniacid=:uniacid AND lessonid=:id AND status>=:status AND uid=:uid AND is_delete=:is_delete LIMIT 1", array(':uniacid'=>$uniacid, ':id'=>$id, ':status'=>0, ':uid'=>$uid,':is_delete'=>0));
    if (!empty($lessonorder)) {
        if ($lessonorder['status'] == 1 && $lessonorder['status']==0) {
            if ($lessonorder['validity']==0) {
                message("您已购买该课程，无需重复购买！", $this->createMobileUrl('lesson', array('id' => $id)), "error");
            }else{
                if($lessonorder['validity'] > time()){
                    message("您已购买该课程，无需重复购买！", $this->createMobileUrl('lesson', array('id' => $id)), "error");
                }
            }
        } elseif ($lessonorder['status'] == 0) {
            message("您还有该课程未付款订单，无需重复下单！", $this->createMobileUrl('mylesson', array('status' => 0)), "warning");
        }
    }

    $lesson = pdo_fetch("SELECT * FROM " . tablename($this->table_lesson_parent) . " WHERE id=:id AND status=:status LIMIT 1", array(':id'=>$id,':status'=>1));
    if (empty($lesson)) {
        message("课程不存在或已下架！", "", "error");
    }

    /* 检查是否开启库存 */
    if($setting['stock_config']==1){
        if($lesson['stock'] <=0 ){
            message("该课程已售罄，下次记得早点来哦~", "", "error");
        }
    }

    if ($setting['mustinfo'] && (!$lesson['lesson_type'] || ($lesson['lesson_type'] && $setting['appoint_mustinfo']))) {
        $user_info = json_decode($setting['user_info']);
        /*
        if(in_array('mobile',$user_info) && empty($member['mobile'])){
            message("请完善您的个人信息", $this->createMobileUrl('writemsg', array('lessonid' => $id)), "warning");
        }
        if(in_array('realname',$user_info) && empty($member['realname'])){
            message("请完善您的个人信息", $this->createMobileUrl('writemsg', array('lessonid' => $id)), "warning");
        }
        if(in_array('msn',$user_info) && empty($member['msn'])){
            message("请完善您的个人信息", $this->createMobileUrl('writemsg', array('lessonid' => $id)), "warning");
        }
        if(in_array('occupation',$user_info) && empty($member['occupation'])){
            message("请完善您的个人信息", $this->createMobileUrl('writemsg', array('lessonid' => $id)), "warning");
        }
        if(in_array('company',$user_info) && empty($member['company'])){
            message("请完善您的个人信息", $this->createMobileUrl('writemsg', array('lessonid' => $id)), "warning");
        }
        if(in_array('graduateschool',$user_info) && empty($member['graduateschool'])){
            message("请完善您的个人信息", $this->createMobileUrl('writemsg', array('lessonid' => $id)), "warning");
        }
        if(in_array('grade',$user_info) && empty($member['grade'])){
            message("请完善您的个人信息", $this->createMobileUrl('writemsg', array('lessonid' => $id)), "warning");
        }
        if(in_array('address',$user_info) && empty($member['address'])){
            message("请完善您的个人信息", $this->createMobileUrl('writemsg', array('lessonid' => $id)), "warning");
        }
        */
    }

    /* 课程规格 */
    $spec_id = $item['spec_id'];
    if (empty($spec_id)) {
        message("课程规格不存在！", "", "error");
    }
    $spec = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_spec). " WHERE lessonid=:lessonid AND spec_id=:spec_id", array(':lessonid'=>$id,':spec_id'=>$spec_id));

    if($lesson['']){
        message("课程规格不存在！", "", "error");
    }

    /* 报名课程附加个人信息 */
    $appoint_name = json_decode($lesson['appoint_info'], true);
    if(!empty($appoint_name) && $lesson['lesson_type']==1){
        foreach($_GPC['appoint_info'] as $k=>$v){
            if(empty($v)){
                message("请填写".$appoint_name[$k]);
            }

            $appoint_info[] = $appoint_name[$k].'：'.preg_replace('#[^\x{4e00}-\x{9fa5}A-Za-z0-9]#u','',$v);
        }
    }

    if(empty($meanwhile_id)){
        /* 检查会员是否享受折扣 */
        $memberVip_list = pdo_fetchall("SELECT * FROM  " .tablename($this->table_member_vip). " WHERE uid=:uid AND validity>:validity", array(':uid'=>$uid,':validity'=>time()));

        $discount = 100; /* 初始折扣为100，即100% */
        if(!empty($memberVip_list)){
            $isVip = true;
            foreach($memberVip_list as $v){
                if($v['discount'] < $discount) {
                    $discount = $v['discount'];
                }
            }
        }

        /* 检查课程是否参加限时活动 */
        $discount_lesson = pdo_fetch("SELECT * FROM " .tablename($this->table_discount_lesson). " WHERE uniacid=:uniacid AND lesson_id=:lesson_id AND starttime<:time AND endtime>:time", array(':uniacid'=>$uniacid,':lesson_id'=>$id,':time'=>time()));
        if(!empty($discount_lesson)){
            $spec['spec_price'] = round($spec['spec_price']*$discount_lesson['discount']*0.01, 2);
        }

        $price = $spec['spec_price'];
        if ($isVip && $discount!=1) { /* 折扣开启 */
            if ($spec['spec_price'] > 0) {
                if ($lesson['isdiscount'] == 1) {/* 课程开启折扣 */
                    if(empty($discount_lesson) || (!empty($discount_lesson) && $discount_lesson['member_discount'])){
                        if ($lesson['vipdiscount'] > 0) {/* 使用课程单独折扣 */
                            $price = round($spec['spec_price'] * $lesson['vipdiscount'] * 0.01, 2);
                        } else {/* 使用VIP等级最低折扣 */
                            $price = round($spec['spec_price'] * $discount * 0.01, 2);
                        }
                    }
                }
            } else {
                $price = 0;
            }
        } else {
            $price = $spec['spec_price']; /* 课程全局关闭折扣 */
        }
    }

    $lesson['spec'] = $spec;
    $lesson['appoint_info'] = json_encode($appoint_info);
    $lessons[$key] = $lesson;
}

if($meanwhile_id){
    $price = pdo_fetchcolumn('SELECT price FROM ' . tablename($this->table_lesson_meanwhile) . ' WHERE id=:id', array(':id' => $meanwhile_id));
}
$lesson = $lessons[0];

$ordersn = date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);

/* 优惠券抵扣 */
$coupon_id = intval($_GPC['coupon_id']);
if($coupon_id>0 && $lesson['support_coupon']){
    $coupon = pdo_fetch("SELECT * FROM " .tablename($this->table_member_coupon). " WHERE id=:id AND uid=:uid AND status=:status", array(':id'=>$coupon_id,':uid'=>$uid, ':status'=>0));
    if(empty($coupon)){
        message("优惠券不存在", "", "error");
    }
    if($price < $coupon['conditions']){
        message("支付金额未满".$coupon['conditions']."元，无法使用该优惠券", "", "error");
    }
    if(time() > $coupon['validity']){
        message("优惠券已过期", "", "error");
    }
    if($coupon['category_id']>0 && $lesson['pid']!=$coupon['category_id']){
        message("优惠券不适用于该课程", "", "error");
    }

    $price -= $coupon['amount'];
    $price = $price>0 ? $price : 0;
}

/* 积分抵扣 */
if(intval($_GPC['deduct_integral'])>0 && $price>0){
    $deduct_integral = intval($_GPC['deduct_integral']);
    $market = pdo_fetch("SELECT * FROM " .tablename($this->table_market). " WHERE uniacid=:uniacid", array(':uniacid'=>$uniacid));

    if($market['deduct_switch']==1 && $market['deduct_money']>0 && $lesson['deduct_integral']>0 && $member['credit1']>0){
        $deduct_money = round($deduct_integral*$market['deduct_money'], 2);

        if($member['credit1'] < $deduct_integral){
            message("用户积分余额不足".$deduct_integral."积分", "", "error");
        }
        if($deduct_integral > $lesson['deduct_integral']){
            message("当前课程最多可使用".$lesson['deduct_integral']."积分", "", "error");
        }
        if($deduct_money > $price){
            $dis_money = $deduct_money - $price;
            $dis_integral = $dis_money/$market['deduct_money'];
            $deduct_integral -= $dis_integral;
        }else{
            $price -= $deduct_money;
        }
    }

}

/* 报名课程且会员为VIP免费时，报名价格为0 */
if(!empty($memberVip_list) && $lesson['lesson_type']==1){
    foreach($memberVip_list as $v){
        if(in_array($v['level_id'], json_decode($lesson['vipview']))){
            $price = 0;
            break;
        }
    }
}

/* 购买课程ID */
$lesson_ids = ',';
foreach($lesson_list as $item){
    $lesson_ids .= $item['id'] . ',';
}

$orderdata = array(
    'acid'		=> $_W['account']['acid'],
    'uniacid'	=> $uniacid,
    'ordersn'	=> $ordersn,
    'uid'		=> $uid,
    'lesson_type'  => $lesson['lesson_type'],
    'appoint_info' => $lesson['appoint_info'],
    'spec_name' => $lesson['spec']['spec_name'],
    'order_cdkey'  => random(8, true),
    'lessonid'	=> $lesson['id'],
    'bookname'	=> $lesson['bookname'],
    'marketprice' => $lesson['spec']['spec_price'],
    'coupon'	=> $coupon_id ? $coupon_id : "",
    'coupon_amount' => $coupon['amount'],
    'price'		=> $price,
    'spec_day'  => $lesson['spec']['spec_day']==-1 ? 0 : $lesson['spec']['spec_day'],
    'teacherid' => $lesson['teacherid'],
    'integral'	=> $lesson['integral_rate']>0 ? ceil($price*$lesson['integral_rate']) : $lesson['integral'],
    'deduct_integral' => $deduct_integral,
    'validity'	=> $lesson['spec']['spec_day']==-1 ? 0 : $lesson['spec']['spec_day'],
    'addtime'	=> time(),
    'is_meanwhile' => $meanwhile_id > 0 ? 1 : 0,
    'lesson_ids' => $lesson_ids
);

/* 检查课程是否存在讲师分成 */
$teacher = pdo_fetch("SELECT id,uid,company_uid FROM " . tablename($this->table_teacher) . " WHERE id=:id", array(':id'=>$lesson['teacherid']));
if ($lesson['teacher_income'] > 0 && $teacher['uid']>0) {
    $orderdata['teacher_income'] = $lesson['teacher_income'];
} else {
    $orderdata['teacher_income'] = 0;
}
if($setting['company_income'] && $teacher['company_uid']>0){
    $orderdata['company_uid'] = $teacher['company_uid'];
    $orderdata['company_income'] = $lesson['company_income'];
}

/* 检查当前分销功能是否开启且课程价格大于0 */
if ($comsetting['is_sale'] == 1 && $spec['spec_price'] > 0) {
    $orderdata['commission1'] = 0;
    $orderdata['commission2'] = 0;
    $orderdata['commission3'] = 0;

    if ($comsetting['self_sale'] == 1) {
        /* 开启分销内购，一级佣金为购买者本人 */
        $orderdata['member1'] = $uid;
        $orderdata['member2'] = $this->getParentid($uid);
        $orderdata['member3'] = $this->getParentid($orderdata['member2']);
    } else {
        /* 关闭分销内购 */
        $orderdata['member1'] = $this->getParentid($uid);
        $orderdata['member2'] = $this->getParentid($orderdata['member1']);
        $orderdata['member3'] = $this->getParentid($orderdata['member2']);
    }

    $orderdata['commission1'] = 0;
    $orderdata['commission2'] = 0;
    $orderdata['commission3'] = 0;

    foreach($lessons as $lesson){
        $lessoncom = unserialize($lesson['commission']); /* 本课程佣金比例 */
        $settingcom = unserialize($comsetting['commission']); /* 全局佣金比例 */

        if ($orderdata['member1'] > 0) {
            $orderdata['commission1'] += $this->getAgentCommission1($lessoncom['commission1'], $settingcom['commission1'], $price, $orderdata['member1']);
        }

        if ($orderdata['member2'] > 0 && in_array($comsetting['level'], array('2', '3'))) {
            $orderdata['commission2'] += $this->getAgentCommission2($lessoncom['commission2'], $settingcom['commission2'], $price, $orderdata['member2']);
        }

        if ($orderdata['member3'] > 0 && $comsetting['level'] == 3) {
            $orderdata['commission3'] += $this->getAgentCommission3($lessoncom['commission3'], $settingcom['commission3'], $price, $orderdata['member3']);
        }

    }
}


if ($uid>0) {
    pdo_insert($this->table_order, $orderdata);
    $orderid = pdo_insertid();
}

foreach($lesson_list as $key => $item){
    $lesson = $lessons[$key];
    $spec_id = $item['spec_id'];

    $order_goods = array(
        'uniacid' => $uniacid,
        'order_id' => $orderid,
        'lessonid' => $lesson['id'],
        'bookname' => $lesson['bookname'],
        'marketprice' => $lesson['spec']['spec_price'],
        'price' => $lesson['spec']['spec_price'],
        'spec_day' => $lesson['spec']['spec_day']==-1 ? 0 : $lesson['spec']['spec_day'],
        'teacherid' => $lesson['teacherid'],
        'validity' => $lesson['spec']['spec_day']==-1 ? 0 : $lesson['spec']['spec_day'],
        'lesson_type' => $lesson['lesson_type'],
        'appoint_info' => $lesson['$appoint_info'],
        'spec_name' => $lesson['spec']['spec_name']
    );

    pdo_insert($this->table_lesson_order_parent, $order_goods);
}

if ($orderid) {
    //减少库存
    if($setting['stock_config']==1){
        $this->updateLessonStock($id, "-1");
    }

    if($coupon_id>0){/* 更改优惠券状态 */
        $couponData = array(
            'ordersn' => $ordersn,
            'status'  => 1,
            'update_time' => time()
        );
        pdo_update($this->table_member_coupon, $couponData, array('id'=>$coupon_id));
    }
    if($deduct_integral>0){/* 扣除会员积分 */
        load()->model('mc');
        mc_credit_update($uid, 'credit1', '-'.$deduct_integral, array(0, '微课堂下单使用积分，sn:'.$ordersn));
    }

    header("Location:" . $this->createMobileUrl('pay', array('orderid' => $orderid)));
} else {
    message("写入订单失败", "", "error");
}
?>