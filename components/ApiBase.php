<?php

class ApiBase {

    const ONLINE_TIME_LIMIT = 3;

    protected static $_resp = array(
        'status' => 0,
        'error' => null,
        'result' => null
    );

    protected static $_user_id = null;

    protected static function _check_auth() {
        //print_r($_SERVER);
        if (!isset($_SERVER["HTTP_APP_KEY"])) {
            static::_send_resp(null, 99, 'app key is not set');
        }
        $app_key = $_SERVER['HTTP_APP_KEY'];

        $user = Users::model()->find("app_key=:app_key", array(':app_key' => $app_key));
   
        if ($user == null) {
            static::_send_resp(null, 99, 'app key is invalid');
        } else {
            static::$_user_id = $user->id_user;
            $user->last_active = new CDbExpression('NOW()');
            $user->save();
        }
    }
    
    protected static function _revizion_code() {
        if (!isset($_SERVER["HTTP_VERSION_CODE"])) {
            static::_send_resp(null, 50, 'Вы используетие не ту версию приложения');
        }
        
        $versionCode = $_SERVER["HTTP_VERSION_CODE"];
        
        $info = InfoIstoria::model()->find("id_info=:id_info", array(":id_info" => 1));
        
        if ($info != null) {
            if ($info->version_code != $versionCode) {
                static::_send_resp(null, 51, 'Обновите приложение для дальнейшей работы с ним!');
            }
        }
    }

    protected static function _send_resp($result = null, $status = 0, $error = null) {
        if ($result)
            static::$_resp['result'] = $result;
        if ($status)
            static::$_resp['status'] = $status;
        if ($error)
            static::$_resp['error'] = $error;

        header('Content-type: application/json');
        echo CJSON::encode(static::$_resp);
        Yii::app()->end();
    }

    public static function unknown($error = null) {
        static::$_resp['status'] = 100;
        static::$_resp['error'] = $error? : 'unknown method';
        static::_send_resp();
    }
}
