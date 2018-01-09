<?php

Class ApiB extends ApiBase {

    public static function account_gmail() {
        $resp = "";
        $email = mb_strtolower(Yii::app()->getRequest()->getPost('email'));
        $displayName = Yii::app()->getRequest()->getPost('displayName');
        $code = Yii::app()->getRequest()->getPost('code');
        $photo = Yii::app()->getRequest()->getPost('photo');
        
        $user = Users::model()->find("email=:email", array(":email" => $email));
        if (isset($user)) {
            $user->app_key = $code;
            $user->photo = $photo;
            if ($user->save()) {
                $resp = array(
                    'id' => $user->id_user,
                    'app_key' => $user->app_key
                );
            }
        }
        else {
            $newUser = new Users();
            $user_data = array(
                'email' => $email,
                'display_name' => $displayName,
                'app_key' => $code,
                'last_active' => new CDbExpression('NOW()'),
                'created' => new CDbExpression('NOW()'),
                'photo' => $photo
            );
            $newUser->attributes = $user_data;
            if (!$newUser->save()) {
                static::_send_resp(null, 101, $user->getErrors());
            }
            else {
                $resp = array(
                    'id' => $newUser->id_user,
                    'app_key' => $newUser->app_key
                );
            }
        }
        
        static::_send_resp($resp);
    }
    
    public static function account_create() {
        $user = new Users;
 
        $email = mb_strtolower(Yii::app()->getRequest()->getPost('email'));
        $password = Yii::app()->getRequest()->getPost('password');
        
        if ($user->check_email_unique($email)) {
            //validation error
            static::_send_resp(null, 102, 'User exist.');
        }
        else {
            $user_data = array(
                'email' => $email,
                'password' => $password,
                'app_key' => md5(microtime() . $email),
                'last_active' => new CDbExpression('NOW()'),
                'created' => new CDbExpression('NOW()'),
            );
            $user->attributes = $user_data;
            if (!$user->save()) {
                static::_send_resp(null, 101, $user->getErrors());
            }
            else {
                $resp = array(
                    'id' => $user->id_user,
                    'app_key' => $user->app_key
                );

                static::_send_resp($resp);
            }
        }
    }
    
    public static function get_profile() {
        static::_check_auth();
        $user = Users::model()->find("id_user=:id_user", array(':id_user' => static::$_user_id));
        $resp = array(
                'id' => $user->id_user,
                'email' => $user->email,
                'display_name' => $user->display_name,
                'photo' => $user->photo,
            );
        static::_send_resp($resp);
    }
    
    public static function set_new_autor() {
        static::_check_auth();
        $user = Users::model()->find("id_user=:id_user", array(':id_user' => static::$_user_id));
        if ($user != null) {
            $message = "I want to be an author of your stories!!";
            $emails = Yii::app()->email;
            $emails->to = "Graf_nfs@ngs.ru";
            $emails->replyTo = $user->email;
            $emails->subject = 'I want to be an author of your stories!!!';
            $emails->message = $message;
            if ($emails->send()) {
                static::_send_resp('Send to email activation.');
            }
        }
    }
}

