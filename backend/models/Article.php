<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "Article".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $article_id
 * @property integer $sort
 * @property integer $status
 * @property integer $create_time
 */
class Article extends \yii\db\ActiveRecord
{
    public function getarticlecategory(){
        return $this->hasOne(ArticleCategory::className(),['id'=>'article_category_id']);
    }
    public function getarticletatail(){
        return $this->hasOne(ArticleCategory::className(),['id'=>'article_category_id']);
    }
    public static $status_options = [
        -1=>'删除',
        0=>'隐藏',
        1=>'正常'
    ];


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['intro'], 'string'],
            [['article_category_id', 'sort', 'status', 'create_time'], 'integer'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '文章名称',
            'intro' => '文章简介',
            'article_category_id' => '文章分类',
            'sort' => '排序',
            'status' => '状态',
            'create_time' => '创建时间',
        ];
    }
}
