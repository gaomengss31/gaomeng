
<!--<p>
    <?/*=\yii\bootstrap\Html::a('垃圾桶',['article/index2'],['class'=>'btn btn-info'])*/?>
</p>-->

<table class="table table-bordered table-condensed">
    <tr>
        <th>ID</th>
        <th>商品名称</th>
        <th>货号</th>
        <th>LOGO图片</th>
        <th>商品分类id</th>
        <th>库存</th>
        <th>商品内容</th>
    </tr>



        <tr>
            <td><?=$models->id?></td>
            <td><?=$models->name?></td>
            <td><?=$models->sn?></td>
            <td><?=\yii\bootstrap\Html::img($models->logo?$models->logo:'/upload/default.png',['height'=>50])?></td>
            <td><?=$models->brand->name?></td>
            <td><?=$models->sort?></td>
            <td><?=$models2->content?></td>
        </tr>




</table>
<?php
//echo \yii\widgets\LinkPager::widget(['pagination'=>$pager]);