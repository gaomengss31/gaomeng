<?php
namespace backend\models;

use yii\base\Model;

class LoginForm2 extends Model
{
    public $username;
    public $password_hash;
    public $rememberMe;


    public function rules()
    {
        return [
            [['username', 'password_hash'], 'required'],
            [['rememberMe'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password_hash'=>'密码',
            'rememberMe'=>'记住我'


        ];
    }

    public function login()
    {
        //1.1 通过用户名查找用户
        $admin = User::findOne(['username'=>$this->username]);
       /* echo '<pre/>';
        var_dump($admin);
        exit;*/
        if($admin){
            if(\Yii::$app->security->validatePassword($this->password_hash,$admin->password_hash)){
                //密码正确.可以登录
                //保存用户信息到session
                \Yii::$app->user->login($admin,$this->rememberMe?7*24*3600:0);
                $admin->last_login_time=time();
                $admin->last_login_ip = \Yii::$app->request->userIP;
                $admin->save();
               //var_dump($admin->getErrors());exit;

                return true;
            }else{
                $this->addError('password_hash','密码错误');
            }

        }else{
            //用户不存在,提示 用户不存在 错误信息
            $this->addError('username','用户名不存在');
        }
        return false;
    }

}