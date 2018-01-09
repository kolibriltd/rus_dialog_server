<?php

class LoginController extends Controller {

    public $defaultAction = 'login';

    /**
     * Displays the login page
     */
    public function actionLogin() {
        if (Yii::app()->user->isGuest) {
            $model = new UserLogin;
            // collect user input data
            if (isset($_POST['UserLogin'])) {
                $model->attributes = $_POST['UserLogin'];
                // validate user input and redirect to previous page if valid
                if ($model->validate()) {
                    $this->lastViset();
                    if (strpos(Yii::app()->user->returnUrl, '/index.php') !== false) {
                        $userInf = User::model()->find("id_user=:user_id", array(":user_id" => Yii::app()->user->id));
//                        if ($userInf->roles == 1) {
//                            $this->redirect(array("/profiles/admin"));
//                        }
//                        else {
//                            $this->redirect(array("/issues/index"));
//                        }
                        $this->redirect(array("/books/admin"));
                    }
                    else {
                        $this->redirect(Yii::app()->user->returnUrl);
                    }
                }
            }
            // display the login form
            $this->render('/user/login', array('model' => $model));
       }
        
    }

    private function lastViset() {
        $lastVisit = User::model()->notsafe()->find(Yii::app()->user->id);
        $lastVisit->last_active = time();
        $lastVisit->save();
    }

}
