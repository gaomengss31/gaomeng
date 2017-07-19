<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'intro');
echo $form->field($model,'article_id');
echo $form->field($model,'sort');
echo $form->field($model,'status',['inline'=>'ture'])->radioList(\backend\models\Brand::getStatusOption());
echo $form->field($model2,'content')->textarea();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();