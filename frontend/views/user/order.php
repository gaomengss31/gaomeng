<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>填写核对订单信息</title>
    <link rel="stylesheet" href="/style/base.css" type="text/css">
    <link rel="stylesheet" href="/style/global.css" type="text/css">
    <link rel="stylesheet" href="/style/header.css" type="text/css">
    <link rel="stylesheet" href="/style/fillin.css" type="text/css">
    <link rel="stylesheet" href="/style/footer.css" type="text/css">

    <script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="/js/cart2.js"></script>

</head>
<body>
<!-- 顶部导航 start -->
<div class="topnav">
    <div class="topnav_bd w990 bc">
        <div class="topnav_left">

        </div>
        <div class="topnav_right fr">
            <ul>
                <li>您好，欢迎来到京西！[<a href="login.html">登录</a>] [<a href="register.html">免费注册</a>] </li>
                <li class="line">|</li>
                <li>我的订单</li>
                <li class="line">|</li>
                <li>客户服务</li>

            </ul>
        </div>
    </div>
</div>
<!-- 顶部导航 end -->

<div style="clear:both;"></div>

<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="index.html"><img src="/images/logo.png" alt="京西商城"></a></h2>
        <div class="flow fr flow2">
            <ul>
                <li>1.我的购物车</li>
                <li class="cur">2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->

<div style="clear:both;"></div>

<!-- 主体部分 start -->
<div class="fillin w990 bc mt15">
    <div class="fillin_hd">
        <h2>填写并核对订单信息</h2>
    </div>
    <!-- 收货人信息  start-->
    <form action="order1" method="post" id="order_form">
    <div class="fillin_bd">
        <div class="address">
            <h3>收货人信息</h3>
            <div class="address_info">
                <?php foreach ($address as $add):?>
                <p>
                   <input type="radio" value="<?=$add->id?>" name="address_id"/><?=$add->username?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$add->tel?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?=$add->province?> <?=$add->city?> <?=$add->area?><?=$add->address?>
                </p>
                <?php endforeach;?>
            </div>
        </div>
        <!-- 收货人信息  end-->

        <!-- 配送方式 start -->
        <div class="delivery">
            <h3>送货方式 </h3>
            <div class="delivery_select">
                <table>
                    <thead>
                    <tr>
                        <th class="col1">送货方式</th>
                        <th class="col2">运费</th>
                        <th class="col3">运费标准</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach (\frontend\models\Order::$deliveries as $delivery_id=>$deli):?>
                    <tr class="cur">
                        <td>
                            <input type="radio" name="delivery_id" checked="checked" value="<?=$delivery_id?>"/><?=$deli['name']?>
                        </td>
                        <td>￥<?=$deli['price']?></td>
                        <td><?=$deli['detail']?></td>
                    </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- 配送方式 end -->
        <!-- 支付方式  start-->
        <div class="pay">
            <h3>支付方式 </h3>

            <div class="pay_select">
                <table>
                    <?php foreach (\frontend\models\Order::$payes as $pay_id=>$pay):?>
                    <tr class="cur">
                        <td class="col1"><input type="radio" name="pay_id" value="<?=$pay_id?>"/><?=$pay['name']?></td>
                        <td class="col2"><?=$pay['detail']?></td>
                    </tr>
                    <?php endforeach;?>
                </table>

            </div>
        </div>
        <!-- 支付方式  end-->
        <!-- 商品清单 start -->
        <div class="goods">
            <h3>商品清单</h3>
            <table name="carts">
                <thead>
                <tr>
                    <th class="col1">商品</th>
                    <th class="col3">价格</th>
                    <th class="col4">数量</th>
                    <th class="col5">小计</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($models as $model):?>
                    <input type="hidden" name="<?=$model['id']?>">
                <tr>
                    <td class="col1">
                        <a href=""><img src="<?=$model['logo']?>" alt="" /></a>
                        <strong><a href=""><?=$model['name']?></a></strong>
                    </td>
                    <td class="col3"><?=$model['shop_price']?></td>
                    <td class="col4"> <?=$carts[$model['id']]?></td>
                    <td class="col5">
                        <?php echo
                        $totle= $model['shop_price']*$carts[$model['id']];

                        ?>
                        <span></span>
                    </td>
                </tr>
                <?php endforeach;?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <ul>
                            <li>
                                <span>4 件商品，总商品金额：</span>
                                <em>￥5316.00</em>
                            </li>
                            <li>
                                <span>返现：</span>
                                <em>-￥240.00</em>
                            </li>
                            <li>
                                <span>运费：</span>
                                <em>￥10.00</em>
                            </li>
                            <li>
                                <span>应付总额：</span>
                                <em>￥<?php echo 111?></em>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- 商品清单 end -->

    </div>
    <div class="fillin_ft">
        <a href="javascript:;" id="order_submit" onclick="document.getElementById('order_form').submit();"><span>提交订单</span></a>
        <p>应付总额：<strong>￥5076.00元</strong></p>
    </div>
    </form>
</div>
<!-- 主体部分 end -->

<div style="clear:both;"></div>
<!-- 底部版权 start -->
<div class="footer w1210 bc mt15">
    <p class="links">
        <a href="">关于我们</a> |
        <a href="">联系我们</a> |
        <a href="">人才招聘</a> |
        <a href="">商家入驻</a> |
        <a href="">千寻网</a> |
        <a href="">奢侈品网</a> |
        <a href="">广告服务</a> |
        <a href="">移动终端</a> |
        <a href="">友情链接</a> |
        <a href="">销售联盟</a> |
        <a href="">京西论坛</a>
    </p>
    <p class="copyright">
        © 2005-2013 京东网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号
    </p>
    <p class="auth">
        <a href=""><img src="/images/xin.png" alt="" /></a>
        <a href=""><img src="/images/kexin.jpg" alt="" /></a>
        <a href=""><img src="/images/police.jpg" alt="" /></a>
        <a href=""><img src="/images/beian.gif" alt="" /></a>
    </p>
</div>
<!-- 底部版权 end -->
</body>
</html>
