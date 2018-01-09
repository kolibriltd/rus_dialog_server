<?php

class User extends CActiveRecord {

    const STATUS_NOACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_BANED = -1;

    public $more_category_id;

/**
* The followings are the available columns in table 'users':
* @var integer $id_user
* @var string $email
* @var string $password
* @var string $app_key
* @var string $created
* @var string $last_active
* @var string $display_name
* @var string $photo
* @var integer $superuser
*/

    /**
     * Returns the static model of the specified AR class.
     * @return CActiveRecord the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return Yii::app()->getModule('user')->tableUsers;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.

        return ((Yii::app()->getModule('user')->isAdmin()) ? array(
            #array('username, password, email', 'required'),
            array('password', 'length', 'max' => 100, 'min' => 4, 'message' => UserModule::t("Incorrect password (minimal length 4 symbols).")),
            array('email', 'email'),
            array('email', 'unique', 'message' => UserModule::t("This user's email address already exists.")),
            array('email, superuser', 'required'),
            array('superuser', 'numerical', 'integerOnly' => true),
                ) : ((Yii::app()->user->id == $this->id_user) ? array(
                    array('email', 'required'),
                    array('email', 'email'),
                    array('email', 'unique', 'message' => UserModule::t("This user's email address already exists.")),
                        ) : array()));
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        $relations = array(
            'profile' => array(self::HAS_ONE, 'Profile', 'id_user'),
        );
        if (isset(Yii::app()->getModule('user')->relations))
            $relations = array_merge($relations, Yii::app()->getModule('user')->relations);
        return $relations;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'password' => UserModule::t("password"),
            'email' => UserModule::t("E-mail"),
            'verifyCode' => UserModule::t("Verification Code"),
            'id_user' => UserModule::t("Id"),
            'created' => UserModule::t("Registration date"),
            'lastvisit' => UserModule::t("Last visit"),
            'superuser' => UserModule::t("Superuser"),
        );
    }

    public function scopes() {
        return array(
            'superuser' => array(
                'condition' => 'superuser=1',
            ),
            'notsafe' => array(
                'select' => 'id_user, password, email',
            ),
        );
    }

    public function defaultScope() {
        return array(
            'select' => 'id_user, email, display_name, last_active, superuser',
        );
    }

    public static function itemAlias($type, $code = NULL) {
        $_items = array(
            'AdminStatus' => array(
                '0' => UserModule::t('No'),
                '1' => UserModule::t('Yes'),
            ),
        );
        if (isset($code))
            return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
        else
            return isset($_items[$type]) ? $_items[$type] : false;
    }

}
