<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;

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
            $model->imgFile = UploadedFile::getInstance($model,'imgFile');
            //开始验证数据
            if($model->validate()){
                  if($model->imgFile){
                        //如果验证数据为img,那么就需要一个变量保存地址
                      $d = \Yii::getAlias('@webroot').'/upload/'.date('Ymd');
                      //判断是否有文件夹，如果没有就创建
                      if(!is_dir($d)){
                          mkdir($d);
                      }
                      //将储存文件路径定义成一个变量
                      $fileName = '/upload/'.date('Ymd').'/'.uniqid().'.'.$model->imgFile->extension;

                      $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                      //保存图片路径
                      $model->logo = $fileName;
                      //保存
                      $model->save(false);//由于默认情况下，保存操作是会调用validate方法，有验证码的时候，需要关闭验证，所以用false
                      //跳转
                      return $this->redirect(['index']);
                  }else{
                      //验证失败 打印错误信息
                      var_dump($model->getErrors());exit;
                  }
                return $this->render('add',['model'=>$model]);
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
            $model->imgFile = UploadedFile::getInstance($model,'imgFile');
            //开始验证数据
            if($model->validate()){
                if($model->imgFile){
                    //如果验证数据为img,那么就需要一个变量保存地址
                    $d = \Yii::getAlias('@webroot').'/upload/'.date('Ymd');
                    //判断是否有文件夹，如果没有就创建
                    if(!is_dir($d)){
                        mkdir($d);
                    }
                    //将储存文件路径定义成一个变量
                    $fileName = '/upload/'.date('Ymd').'/'.uniqid().'.'.$model->imgFile->extension;

                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                    //保存图片路径
                    $model->logo = $fileName;
                    //保存
                    $model->save(false);//由于默认情况下，保存操作是会调用validate方法，有验证码的时候，需要关闭验证，所以用false
                    //跳转
                    return $this->redirect(['index']);
                }else{
                    //验证失败 打印错误信息
                    var_dump($model->getErrors());exit;
                }
                return $this->render('add',['model'=>$model]);
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

}
