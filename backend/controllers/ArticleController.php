<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\web\Request;

class ArticleController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query = Article::find()->where(['status' => [1,0]])->orderBy('sort DESC');

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
    public function actionAdd(){
        $model = new Article();
        $model2= new ArticleDetail();
        $request = new Request();
        //判断传值方式
        if($request->isPost){
            //如果是post方式提交，就开始加载数据
            $model->load($request->post());
            $model2->load($request->post());
            if($model->validate() && $model2->validate()){
                $model->create_time=time();
                $model->save(false);//由于默认情况下，保存操作是会调用validate方法，有验证码的时候，需要关闭验证，所以用false
                //跳转

                $model2->article_id = $model->id;
                $model2->save();

                return $this->redirect(['article/index']);
            }else{
                //验证失败 打印错误信息
                var_dump($model->getErrors());exit;
            }

            /*f($model2->validate()){
                $model->id;
                var_dump($model);
                exit;
                $model2-save();
            }*/

        }
        return $this->render('add',['model'=>$model,'model2'=>$model2]);
    }
    public function actionDelete($id){
        $model = Article::findOne($id);
        $model->status = -1;
        $model->save();
        return $this->redirect('index');
    }
}
