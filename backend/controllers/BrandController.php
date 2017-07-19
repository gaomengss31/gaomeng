<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    public $layout = 'brand_mine';
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>���brand<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    public function actionAdd(){
        $model = new Brand();
        $request = new Request();
        //�жϴ�ֵ��ʽ
        if($request->isPost){
            //�����post��ʽ�ύ���Ϳ�ʼ��������
            $model->load($request->post());
            //ʵ�����ļ�
            $model->imgFile = UploadedFile::getInstance($model,'imgFile');
            //��ʼ��֤����
            if($model->validate()){
                  if($model->imgFile){
                        //�����֤����Ϊimg,��ô����Ҫһ�����������ַ
                      $d = \Yii::getAlias('@webroot').'/upload/'.date('Ymd');
                      //�ж��Ƿ����ļ��У����û�оʹ���
                      if(!is_dir($d)){
                          mkdir($d);
                      }
                      //�������ļ�·�������һ������
                      $fileName = '/upload/'.date('Ymd').'/'.uniqid().'.'.$model->imgFile->extension;

                      $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                      //����ͼƬ·��
                      $model->logo = $fileName;
                      //����
                      $model->save(false);//����Ĭ������£���������ǻ����validate����������֤���ʱ����Ҫ�ر���֤��������false
                      //��ת
                      return $this->redirect(['index']);
                  }else{
                      //��֤ʧ�� ��ӡ������Ϣ
                      var_dump($model->getErrors());exit;
                  }
                return $this->render('add',['model'=>$model]);
            }
        }

        return $this->render('add',['model'=>$model]);
    }
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>��ʾ��ҳ<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    public function actionIndex()
    {
        //��ҳ ������ ÿҳ��ʾ���� ��ǰ�ڼ�ҳ
        $query = Brand::find()->where(['status' => [1,0]])->orderBy('sort DESC');
        //$query = Brand::find()->where(['status' => [1,0]])->orderBy('sort DESC')->all();
        //������
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
    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>�޸Ĺ���<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    public function actionEdit($id){
        //$model = new Brand();
        $model = Brand::findOne(['id'=>$id]);

        $request = new Request();
        //�жϴ�ֵ��ʽ
        if($request->isPost){
            //�����post��ʽ�ύ���Ϳ�ʼ��������
            $model->load($request->post());
            //ʵ�����ļ�
            $model->imgFile = UploadedFile::getInstance($model,'imgFile');
            //��ʼ��֤����
            if($model->validate()){
                if($model->imgFile){
                    //�����֤����Ϊimg,��ô����Ҫһ�����������ַ
                    $d = \Yii::getAlias('@webroot').'/upload/'.date('Ymd');
                    //�ж��Ƿ����ļ��У����û�оʹ���
                    if(!is_dir($d)){
                        mkdir($d);
                    }
                    //�������ļ�·�������һ������
                    $fileName = '/upload/'.date('Ymd').'/'.uniqid().'.'.$model->imgFile->extension;

                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                    //����ͼƬ·��
                    $model->logo = $fileName;
                    //����
                    $model->save(false);//����Ĭ������£���������ǻ����validate����������֤���ʱ����Ҫ�ر���֤��������false
                    //��ת
                    return $this->redirect(['index']);
                }else{
                    //��֤ʧ�� ��ӡ������Ϣ
                    var_dump($model->getErrors());exit;
                }
                return $this->render('add',['model'=>$model]);
            }
        }

        return $this->render('add',['model'=>$model]);
    }

    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>ɾ������status״̬�ĳ�-1<<<<<<<<<<<<<<<<<<<<<<<<<<<
    public function actionDel($id){
        $model = Brand::findOne(['id'=>$id]);
        $model->status = -1;
        $model->save();
        return $this->redirect('index');
    }

}
