<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;
use flyok666\uploadifive\UploadAction;
use flyok666\qiniu\Qiniu;

class BrandController extends \yii\web\Controller
{
    public $layout = 'brand_mine';
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>添加brand<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    public function actionAdd(){
        $model = new Brand();
        $request = new Request();
        //判断传值方式
        if($request->isPost){
            //如果是post方式提交，就开始加载数据
            $model->load($request->post());
            //实例化文件
            //开始验证数据


            if($model->validate()){
                      $model->save();//由于默认情况下，保存操作是会调用validate方法，有验证码的时候，需要关闭验证，所以用false
                      //跳转
                      return $this->redirect(['index']);
                  }else{
                      //验证失败 打印错误信息
                      var_dump($model->getErrors());exit;
                  }
        }
        return $this->render('add',['model'=>$model]);
    }
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>显示主页<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    public function actionIndex()
    {
        //分页 总条数 每页显示条数 当前第几页
        $query = Brand::find()->where(['status' => [1,0]])->orderBy('sort DESC');
        //$query = Brand::find()->where(['status' => [1,0]])->orderBy('sort DESC')->all();
        //总条数
        $total = $query->count();
        $parPage = 2;
        $pager = new Pagination(
            [
                'totalCount'=>$total,
                'defaultPageSize'=>$parPage
            ]
        );
        $models = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['models'=>$models,'pager'=>$pager]);

    }
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>垃圾桶界面<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    public function actionIndex2(){
        $query2 = Brand::find()->where(['status' => -1])->orderBy('sort DESC');
        //$query = Brand::find()->where(['status' => [1,0]])->orderBy('sort DESC')->all();
        //总条数
        $total = $query2->count();
        $parPage = 5;
        $pager = new Pagination(
            [
                'totalCount'=>$total,
                'defaultPageSize'=>$parPage
            ]
        );
        $models2 = $query2->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index2',['models2'=>$models2,'pager'=>$pager]);
    }
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>修改功能<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    public function actionEdit($id){
        //$model = new Brand();
        $model = Brand::findOne(['id'=>$id]);
        $request = new Request();
        //判断传值方式
        if($request->isPost){
            //如果是post方式提交，就开始加载数据
            $model->load($request->post());
            //实例化文件
            //开始验证数据


            if($model->validate()){
                $model->save();//由于默认情况下，保存操作是会调用validate方法，有验证码的时候，需要关闭验证，所以用false
                //跳转
                return $this->redirect(['index']);
            }else{
                //验证失败 打印错误信息
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>删除，将status状态改成-1<<<<<<<<<<<<<<<<<<<<<<<<<<<
    public function actionDel($id){
        $model = Brand::findOne(['id'=>$id]);
        $model->status = -1;
        $model->save();
        return $this->redirect('index');
    }

    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>添加uploadify用于添加图片的插件<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>还需要在视图上加东西<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                //'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
               /* 'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },*/
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    $action->output['fileUrl'] = $action->getWebUrl();
                    /*$action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"*/
                    //将图片上传到七牛云上
                    $qiniu = new Qiniu(\Yii::$app->params['qiniu']);
                    $qiniu->uploadFile(
                        $action->getSavePath(),$action->getWebUrl()
                    );
                    $url = $qiniu->getLink($action->getWebUrl());
                    $action->output['fileUrl'] = $url;

                    },
            ],
        ];
    }
    //测试青牛云
    public function actionQiniu(){

        $config = [
            'accessKey'=>'_0_0yq12zkEYI-SPX-PS8FGc7XqVD_sNxcwyVo3L',
            'secretKey'=>'49TsocJoSpsupLcThag5twhw9XKpy1Y73gZwYTnD',
            'domain'=>'http://otbw5uw0l.bkt.clouddn.com/',
            'bucket'=>'yiishop',
            'area'=>Qiniu::AREA_HUADONG
        ];



        $qiniu = new Qiniu($config);
        $key = 'upload/cc/8a/cc8af6aa8ea51370c22352d716f4c4b9ba6177e7.jpg';
        //上传图片到七牛云
        $qiniu->uploadFile(\Yii::getAlias('@webroot'.'/upload/cc/8a/cc8af6aa8ea51370c22352d716f4c4b9ba6177e7.jpg'),$key);
        //获取七牛云
        $url = $qiniu->getLink($key);
        var_dump($url);
    }



}
