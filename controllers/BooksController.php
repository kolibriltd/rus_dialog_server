<?php

class BooksController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/main';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        if (UserModule::isAdmin()) {
            return array(
                array('allow',  // allow all users to perform 'index' and 'view' actions
                        'actions'=>array('index','view'),
                        'users'=>array('*'),
                ),
                array('allow', // allow authenticated user to perform 'create' and 'update' actions
                        'actions'=>array('create', 'createtxt','update', 'createtext', 'addtextbook', 'deletemessagetext'),
                        'users'=>array('@'),
                ),
                array('allow', // allow admin user to perform 'admin' and 'delete' actions
                        'actions'=>array('admin','delete'),
                        'users'=>array('@'),
                ),
                array('deny',  // deny all users
                        'users'=>array('*'),
                ),
            );
        }
        else {
            return array(
                array('allow',  // allow all users to perform 'index' and 'view' actions
                        'actions'=>array('index','view'),
                        'users'=>array('*'),
                ),
                array('allow', // allow authenticated user to perform 'create' and 'update' actions
                        'actions'=>array('create', 'createtxt','update', 'createtext', 'addtextbook', 'deletemessagetext'),
                        'users'=>array('@'),
                ),
                array('deny',  // deny all users
                        'users'=>array('*'),
                ),
            );
        }
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionNewPush() {
        if (isset($_POST['OneSignal'])) {
            new OneSignal($_POST['OneSignal']['title'], $_POST['OneSignal']['message']);
        }
        $this->render('newpush');
    }

    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    public function actionAddTextBook($id, $type, $branch, $floor) {
        $model = $this->loadModel($id);
        $html_result = "";

        if (isset($_GET['type'])) {
            $json_text_decode = json_decode($model->path_txt_file);
            $nameA = $json_text_decode->result[0]->nameA;
            $nameB = $json_text_decode->result[1]->nameB;
            $json_array_add = $this->addBookMessage($json_text_decode, $type, $_POST, $branch, $floor);
            if ($type == "peopleA") {
                $html_result = "<div id=\"peopleA_blok\">
                        <div id=\"peopleA_msg\">
                    {$_POST['text']}
                        </div>
                        <div id=\"peopleA_name\">
                    {$nameA}
                        </div>
                    </div>";
            }
            if ($type == "imgPeopleA") {
                $html_result = "<div id=\"peopleA_blok\">
                        <div id=\"peopleA_msg\">
                    <img src=\"". Yii::app()->request->baseUrl . "/uploads/" . $model->dir_book . "/" . $_POST['text'] . "\" width=\"200\"/>
                        </div>
                        <div id=\"peopleA_name\">
                    {$nameA}
                        </div>
                    </div>";
            }
            if ($type == "peopleB") {
                $html_result = "<div id=\"peopleB_blok\">
                        <div id=\"peopleB_msg\">
                    {$_POST['text']}
                        </div>
                        <div id=\"peopleB_name\">
                    {$nameB}
                        </div>
                    </div>";
            }
            if ($type == "imgPeopleB") {
                $html_result = "<div id=\"peopleB_blok\">
                        <div id=\"peopleB_msg\">
                    <img src=\"". Yii::app()->request->baseUrl . "/uploads/" . $model->dir_book . "/" . $_POST['text'] . "\" width=\"200\"/>
                        </div>
                        <div id=\"peopleB_name\">
                    {$nameB}
                        </div>
                    </div>";
            }
            if ($type == "context") {
                $html_result = "<div id=\"context_blok\">
                    {$_POST['text']}
   
                    </div>";
            }
            if ($type == "callPeopleB") {
                $html_result = "<div id=\"context_blok\">
                    {$nameB} сбросил(-а) вызов
   
                    </div>";
            }
            if ($type == "missCallPeopleB") {
                $html_result = "<div id=\"context_blok\">
                    Абонент не отвечает
                    </div>";
            }
            if ($type == "branch") {
                $model->type_id = 2;
                $html_result = "<div>
            Ветка 1        </div><div id=\"peopleA_blok\">
                        <div id=\"peopleA_msg\">
                    {$_POST['branchOne']}
                        </div>
                        <div id=\"peopleA_name\">
                    {$nameA}
                        </div>
                    </div>";
            }

            $model->path_txt_file = json_encode($json_array_add);
            if ($model->save()) {
                echo $html_result;
            }
        }
    }
    
    public function actionDeleteMessageText($id, $branch, $floorMsg, $keyMsg) {
        $boolDelete = false;
        $model = $this->loadModel($id);
        $json_text_decode = json_decode($model->path_txt_file);
        $new_json_array = "";
        
        if ($floorMsg == 0) {
            unset($json_text_decode->result[$keyMsg]);
            foreach ($json_text_decode->result as $value) {
                $new_json_array[] = $value;
            }
            $json_text_decode->result = $new_json_array;
            $boolDelete = true;
        }
        if ($floorMsg == 1) {
            $branchId = "";
            if ($branch == 2 || $branch == 5 || $branch == 6) {
                $branchId = 1;
            }
            if ($branch == 1 || $branch == 3 || $branch == 4) {
                $branchId = 0;
            }
            if ($keyMsg != 0) {
                foreach ($json_text_decode->result as $key => $value) {
                    if (isset($value->branch)) {
                        unset($json_text_decode->result[$key]->branch[$branchId]->content[$keyMsg]);
                        foreach ($json_text_decode->result[$key]->branch[$branchId]->content as $valueBr) {
                            $new_json_array[] = $valueBr;
                        }
                        $json_text_decode->result[$key]->branch[$branchId]->content = $new_json_array;
                        $boolDelete = true;
                    }
                }
            }
        }
        if ($floorMsg == 2) {
            $branchId = "";
            if ($branch == 2 || $branch == 5 || $branch == 6) {
                $branchId = 1;
            }
            if ($branch == 1 || $branch == 3 || $branch == 4) {
                $branchId = 0;
            }
            if ($keyMsg != 0) {
                foreach ($json_text_decode->result as $key => $value) {
                    if (isset($value->branch)) {
                        foreach ($value->branch[$branchId]->content as $keyBr => $valueBr) {
                            if (isset($valueBr->branch)) {
                                $brId = "";
                                if ($branch == 3 || $branch == 5) {
                                    $brId = 0;
                                }
                                if ($branch == 4 || $branch == 6) {
                                    $brId = 1;
                                }
                                unset($json_text_decode->result[$key]->branch[$branchId]->content[$keyBr]->branch[$brId]->content[$keyMsg]);
                                foreach ($json_text_decode->result[$key]->branch[$branchId]->content[$keyBr]->branch[$brId]->content as $valueBrNew) {
                                    $new_json_array[] = $valueBrNew;
                                }
                                $json_text_decode->result[$key]->branch[$branchId]->content[$keyBr]->branch[$brId]->content = $new_json_array;
                                $boolDelete = true;
                            }
                        }
                    }
                }
            }
        }
        
        if ($boolDelete) {
            $model->path_txt_file = json_encode($json_text_decode);
            if ($model->save()) {
                echo "1";
            }
            else {
                echo "0";
            }
        }
        else {
            echo "0";
        }
    }

    public function actionUpload($id) {

        if($this->issetDirAndCreateDir($id)) {
            $model = $this->loadModel($id);
            Yii::import("ext.EAjaxUpload.qqFileUploader");
            
            $folder = 'uploads/' . $model->dir_book . "/"; // folder for uploaded files
            $allowedExtensions = array("jpg", "jpeg", "mp3"); //array("jpg","jpeg","gif","exe","mov" and etc...
            $sizeLimit = 10 * 1024 * 1024; // maximum file size in bytes
            $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
            $result = $uploader->handleUpload($folder);
            $return = htmlspecialchars(json_encode($result), ENT_NOQUOTES);
            echo $return;
        }
        
    }

    private function addBookMessage($jsonArray, $type, $postData, $branch, $floor) {
        $branchBool = false;
        $json_text_decode = "";
        if ($type == "peopleA") {
            $json_text_decode = array("peopleA" => $postData['text']);
        }
        if ($type == "imgPeopleA") {
            $json_text_decode = array("imgPeopleA" => $postData['text']);
        }
        if ($type == "audioPeopleA") {
            $json_text_decode = array("audioPeopleA" => $postData['text']);
        }
        if ($type == "peopleB") {
            $json_text_decode = array("peopleB" => $postData['text']);
        }
        if ($type == "imgPeopleB") {
            $json_text_decode = array("imgPeopleB" => $postData['text']);
        }
        if ($type == "audioPeopleB") {
            $json_text_decode = array("audioPeopleB" => $postData['text']);
        }
        if ($type == "context") {
            $json_text_decode = array("context" => $postData['text']);
        }
        if ($type == "callPeopleB") {
            $json_text_decode = array("callPeopleB" => "2");
        }
        if ($type == "missCallPeopleB") {
            $json_text_decode = array("missCallPeopleB" => "2");
        }
        if ($type == "branch") {
            $branchOne = "";
            $branchTwo = "";
            $array_branch = "";
            $branchOne[] = array("peopleA" => $postData['branchOne']);
            $branchTwo[] = array("peopleA" => $postData['branchTwo']);
            $array_branch["branch"][] = array("message" => $postData['branchOne'], "content" => $branchOne);
            $array_branch["branch"][] = array("message" => $postData['branchTwo'], "content" => $branchTwo);
            $json_text_decode = $array_branch;
        }

        if ($floor == 0) {
            $boolBranch = false;
            $tmp_json_branch = "";
            foreach ($jsonArray->result as $key => $value) {
                if (isset($value->branch)) {
                    $tmp_json_branch[] = $json_text_decode;
                    $tmp_json_branch[] = $value;
                    $boolBranch = true;
                }
                else {
                    $tmp_json_branch[] = $value;
                }
            }
            if ($boolBranch) {
                $jsonArray->result = $tmp_json_branch;
            }
            else {
                $jsonArray->result[] = $json_text_decode;
            }
        }
        else {
            
            foreach ($jsonArray->result as $key => $value) {
                if (isset($value->branch)) {
                    if ($floor == 1) {
                        $boolBranch = false;
                        $tmp_json_branch = "";
                        foreach ($jsonArray->result[$key]->branch[$branch - 1]->content as $keyBr => $valueBr) {
                            if (isset($valueBr->branch)) {
                                $tmp_json_branch[] = $json_text_decode;
                                $tmp_json_branch[] = $valueBr;
                                $boolBranch = true;
                            }
                            else {
                                $tmp_json_branch[] = $valueBr;
                            }
                        }
                        if ($boolBranch) {
                            $jsonArray->result[$key]->branch[$branch - 1]->content = $tmp_json_branch;
                        }
                        else {
                            $jsonArray->result[$key]->branch[$branch - 1]->content[] = $json_text_decode;
                        }
                    }
                    if ($floor == 2) {
                        if ($branch == 3) {
                            foreach ($jsonArray->result[$key]->branch[0]->content as $keyBr => $valueBr) {
                                if (isset($valueBr->branch)) {
                                    $jsonArray->result[$key]->branch[0]->content[$keyBr]->branch[0]->content[] = $json_text_decode;
                                }
                            }
                        }
                        if ($branch == 4) {
                            foreach ($jsonArray->result[$key]->branch[0]->content as $keyBr => $valueBr) {
                                if (isset($valueBr->branch)) {
                                    $jsonArray->result[$key]->branch[0]->content[$keyBr]->branch[1]->content[] = $json_text_decode;
                                }
                            }
                        }
                        if ($branch == 5) {
                            foreach ($jsonArray->result[$key]->branch[1]->content as $keyBr => $valueBr) {
                                if (isset($valueBr->branch)) {
                                    $jsonArray->result[$key]->branch[1]->content[$keyBr]->branch[0]->content[] = $json_text_decode;
                                }
                            }
                        }
                        if ($branch == 6) {
                            foreach ($jsonArray->result[$key]->branch[1]->content as $keyBr => $valueBr) {
                                if (isset($valueBr->branch)) {
                                    $jsonArray->result[$key]->branch[1]->content[$keyBr]->branch[1]->content[] = $json_text_decode;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $jsonArray;
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreateTxt() {
        $str_json_array = "";
        $model = new Books;
        if (isset($_POST['Books'])) {
            if (!empty($_POST['Books']['peopleA'])) {
                $str_json_array[] = array("nameA" => $_POST['Books']['peopleA']);
            }
            if (!empty($_POST['Books']['peopleB'])) {
                $str_json_array[] = array("nameB" => $_POST['Books']['peopleB']);
            }
            $model->name = $_POST['Books']['name'];
            $model->author = $_POST['Books']['author'];
            $model->path_txt_file = json_encode(array("result" => $str_json_array));
            $model->description = $_POST['Books']['description'];
            $model->user_id = Yii::app()->user->id;
            $model->is_view_count = 0;
            $model->status = 1;
            $model->type_id = 1;
            $model->last_modified = new CDbExpression('NOW()');
            $model->created = new CDbExpression('NOW()');
            if ($model->save()) {
                $this->redirect(array('books/createtext/id/' . $model->id_book));
            }
        }
        $this->render('createtxt', array('model' => $model));
    }

    public function actionCreatetext($id) {
        $model = $this->loadModel($id);

        $this->render('createtext', array('model' => $model));
    }

    private function issetDirAndCreateDir($id) {
        $model = $this->loadModel($id);
        
        if (empty($model->dir_book)) {
            $nameDir = Helper::random_string();
            mkdir("uploads/" . $nameDir . "/", 0777);
            $model->dir_book = $nameDir;
            if ($model->save()) {
                return true;
            }
        }
        if (is_writable("uploads/" . $model->dir_book . "/")) {
            return true;
        }
        return false;
    }

    public function actionCreate() {
        $model = new Books;
        $typeList = Type::model()->findAll();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Books'])) {
            $model->attributes = $_POST['Books'];


            $model->txt_file = CUploadedFile::getInstance($model, 'txt_file');
            $ext = explode(".", $model->txt_file->name);
            $nameFile = Helper::random_string();
            $fileName = $nameFile . '.' . $ext[1];

            if ($ext[1] == "zip") {

                if ($model->txt_file->saveAs(Yii::app()->basePath . "/uploads/patch_rar/" . $fileName)) {
                    $zip = new ZipArchive;
                    if ($zip->open(Yii::app()->basePath . "/uploads/patch_rar/" . $fileName) === TRUE) {
                        $zip->extractTo(Yii::app()->basePath . "/uploads/unzip/" . $nameFile . "/");
                        $zip->close();
                        unlink(Yii::app()->basePath . "/uploads/patch_rar/" . $fileName);
                        if ($_POST['Books']['type_id'] == 2) {
                            $model->path_txt_file = $this->ParseBranchTxtFile("/uploads/unzip/" . $nameFile . "/book.txt", $nameFile);
                        } else {
                            $model->path_txt_file = $this->parseTxtFile("/uploads/unzip/" . $nameFile . "/book.txt", $nameFile);
                        }
                        $model->cover_file = CUploadedFile::getInstance($model, 'cover_file');
                        $ext1 = explode(".", $model->cover_file->name);
                        $fileName1 = Helper::random_string() . '.' . $ext1[1];
                        if ($model->cover_file->saveAs(Yii::app()->basePath . "/uploads/patch_cover/" . $fileName1)) {
                            $model->path_cover_file = "/uploads/patch_cover/" . $fileName1;
                            $model->is_view_count = 0;
                            $model->last_modified = new CDbExpression('NOW()');
                            $model->created = new CDbExpression('NOW()');
                            if ($model->save()) {
                                $this->redirect(array('admin'));
                            }
                        }
                    }
                }
            } else {
                if ($model->txt_file->saveAs(Yii::app()->basePath . "/uploads/patch_txt/" . $fileName)) {
                    if ($_POST['Books']['type_id'] == 2) {
                        $model->path_txt_file = $this->ParseBranchTxtFile("/uploads/patch_txt/" . $fileName, null);
                    } else {
                        $model->path_txt_file = $this->parseTxtFile("/uploads/patch_txt/" . $fileName, null);
                    }
                    $model->cover_file = CUploadedFile::getInstance($model, 'cover_file');
                    $ext1 = explode(".", $model->cover_file->name);
                    $fileName1 = Helper::random_string() . '.' . $ext1[1];
                    if ($model->cover_file->saveAs(Yii::app()->basePath . "/uploads/patch_cover/" . $fileName1)) {
                        $model->path_cover_file = "/uploads/patch_cover/" . $fileName1;
                        $model->is_view_count = 0;
                        $model->last_modified = new CDbExpression('NOW()');
                        $model->created = new CDbExpression('NOW()');
                        if ($model->save())
                            $this->redirect(array('admin'));
                    }
                }
            }
        }

        $this->render('create', array('model' => $model, "typeList" => $typeList));
    }

    private function parseTxtFile($filename, $imgPatch = null) {
        if (file_exists(Yii::app()->basePath . $filename)) {
            $tmp_str = file_get_contents(Yii::app()->basePath . $filename);
            try {
                $tmp_str = iconv("windows-1251", "UTF-8", $tmp_str);
            } catch (Exception $ex) {
                
            }
            $array_str_parse = explode("#", $tmp_str);
            $str_json_array = "";
            for ($i = 0; $i < count($array_str_parse); $i++) {
                $array_str_parse[$i] = str_replace("\r\n", "", $array_str_parse[$i]);
                $array_str_parse[$i] = str_replace('"', '\\"', $array_str_parse[$i]);
                if (substr_count($array_str_parse[$i], "AN-") > 0) {
                    $str_json_array[] = array("nameA" => str_replace("AN-", "", $array_str_parse[$i]));
                }
                if (substr_count($array_str_parse[$i], "BN-") > 0) {
                    $str_json_array[] = array("nameB" => str_replace("BN-", "", $array_str_parse[$i]));
                }
                if (substr_count($array_str_parse[$i], "A-") > 0) {
                    $str_json_array[] = array("peopleA" => str_replace("A-", "", $array_str_parse[$i]));
                }
                if (substr_count($array_str_parse[$i], "VA-") > 0) {
                    $str_json_array[] = array("videoPeopleA" => str_replace("VA-", "", $array_str_parse[$i]));
                }
                if (substr_count($array_str_parse[$i], "VB-") > 0) {
                    $str_json_array[] = array("videoPeopleB" => str_replace("VB-", "", $array_str_parse[$i]));
                }
                if (substr_count($array_str_parse[$i], "C-") > 0) {
                    $str_json_array[] = array("context" => str_replace("C-", "", $array_str_parse[$i]));
                }
                if (substr_count($array_str_parse[$i], "BCALL-") > 0) {
                    $str_json_array[] = array("callPeopleB" => str_replace("BCALL-", "", $array_str_parse[$i]));
                }
                if (substr_count($array_str_parse[$i], "BMCALL-") > 0) {
                    $str_json_array[] = array("missCallPeopleB" => str_replace("BMCALL-", "", $array_str_parse[$i]));
                }
                if (substr_count($array_str_parse[$i], "B-") > 0) {
                    $str_json_array[] = array("peopleB" => str_replace("B-", "", $array_str_parse[$i]));
                }
                if (substr_count($array_str_parse[$i], "T") > 0) {
                    $str_json_array[] = array("metka" => "STOP");
                }
                if (substr_count($array_str_parse[$i], "E") > 0) {
                    $str_json_array[] = array("metka" => "END");
                }
                if (substr_count($array_str_parse[$i], "AIMG-") > 0) {
                    $str_json_array[] = array("imgPeopleA" => "/uploads/unzip/" . $imgPatch . "/image/" . str_replace("AIMG-", "", $array_str_parse[$i]));
                }
                if (substr_count($array_str_parse[$i], "BIMG-") > 0) {
                    $str_json_array[] = array("imgPeopleB" => "/uploads/unzip/" . $imgPatch . "/image/" . str_replace("BIMG-", "", $array_str_parse[$i]));
                }
                if (substr_count($array_str_parse[$i], "AMP-") > 0) {
                    $str_json_array[] = array("audioPeopleA" => "/uploads/unzip/" . $imgPatch . "/audio/" . str_replace("AMP-", "", $array_str_parse[$i]));
                }
                if (substr_count($array_str_parse[$i], "BMP-") > 0) {
                    $str_json_array[] = array("audioPeopleB" => "/uploads/unzip/" . $imgPatch . "/audio/" . str_replace("BMP-", "", $array_str_parse[$i]));
                }
            }
            unlink(Yii::app()->basePath . $filename);
            return json_encode(array("result" => $str_json_array));
        } else {
            return Yii::app()->basePath . $filename;
        }
    }

    private function ParseBranchTxtFile($filename, $imgPatch) {

        if (file_exists(Yii::app()->basePath . $filename)) {
            $tmp_str = file_get_contents(Yii::app()->basePath . $filename);
            try {
                $tmp_str = iconv("windows-1251", "UTF-8", $tmp_str);
            } catch (Exception $ex) {
                
            }

            $tmp_str = str_replace("\r", "", $tmp_str);
            //echo "<pre>";
            $tmp_str = str_replace('"', '\\"', $tmp_str);
            $array_str_parse = explode("0#", $tmp_str);
            //print_r($array_str_parse);
            $str_json_array = "";
            for ($i = 0; $i < count($array_str_parse); $i++) {
                if (substr_count($array_str_parse[$i], "AN-") > 0) {
                    if (!substr_count($array_str_parse[$i], "#AN-") > 0) {
                        $array_str_parse[$i] = str_replace("\n", "", $array_str_parse[$i]);
                        $str_json_array[] = array("nameA" => str_replace("AN-", "", $array_str_parse[$i]));
                    }
                }
                if (substr_count($array_str_parse[$i], "BN-") > 0) {
                    if (!substr_count($array_str_parse[$i], "#BN-") > 0) {
                        $array_str_parse[$i] = str_replace("\n", "", $array_str_parse[$i]);
                        $str_json_array[] = array("nameB" => str_replace("BN-", "", $array_str_parse[$i]));
                    }
                }
                if (substr_count($array_str_parse[$i], "A-") > 0) {
                    if (!substr_count($array_str_parse[$i], "#A-") > 0) {
                        $array_str_parse[$i] = str_replace("\n", "", $array_str_parse[$i]);
                        $str_json_array[] = array("peopleA" => str_replace("A-", "", $array_str_parse[$i]));
                    }
                }
                if (substr_count($array_str_parse[$i], "AV-") > 0) {
                    if (!substr_count($array_str_parse[$i], "#AV-") > 0) {
                        $array_str_parse[$i] = str_replace("\n", "", $array_str_parse[$i]);
                        $str_json_array[] = array("videoPeopleA" => str_replace("VA-", "", $array_str_parse[$i]));
                    }
                }
                if (substr_count($array_str_parse[$i], "BV-") > 0) {
                    if (!substr_count($array_str_parse[$i], "#BV-") > 0) {
                        $array_str_parse[$i] = str_replace("\n", "", $array_str_parse[$i]);
                        $str_json_array[] = array("videoPeopleB" => str_replace("VB-", "", $array_str_parse[$i]));
                    }
                }
                if (substr_count($array_str_parse[$i], "C-") > 0) {
                    if (!substr_count($array_str_parse[$i], "#C-") > 0) {
                        $array_str_parse[$i] = str_replace("\n", "", $array_str_parse[$i]);
                        $str_json_array[] = array("context" => str_replace("C-", "", $array_str_parse[$i]));
                    }
                }
                if (substr_count($array_str_parse[$i], "BCALL-") > 0) {
                    if (!substr_count($array_str_parse[$i], "#BCALL-") > 0) {
                        $array_str_parse[$i] = str_replace("\n", "", $array_str_parse[$i]);
                        $str_json_array[] = array("callPeopleB" => str_replace("BCALL-", "", $array_str_parse[$i]));
                    }
                }
                if (substr_count($array_str_parse[$i], "BMCALL-") > 0) {
                    if (!substr_count($array_str_parse[$i], "#BMCALL-") > 0) {
                        $array_str_parse[$i] = str_replace("\n", "", $array_str_parse[$i]);
                        $str_json_array[] = array("missCallPeopleB" => str_replace("BMCALL-", "", $array_str_parse[$i]));
                    }
                }
                if (substr_count($array_str_parse[$i], "B-") > 0) {
                    if (!substr_count($array_str_parse[$i], "#B-") > 0) {
                        $array_str_parse[$i] = str_replace("\n", "", $array_str_parse[$i]);
                        $str_json_array[] = array("peopleB" => str_replace("B-", "", $array_str_parse[$i]));
                    }
                }
                if (substr_count($array_str_parse[$i], "T") > 0) {
                    if (!substr_count($array_str_parse[$i], "#T-") > 0) {
                        $str_json_array[] = array("metka" => "STOP");
                    }
                }
                if ($end = substr_count($array_str_parse[$i], "E") > 0) {
                    if (substr_count($array_str_parse[$i], "#E") == 0) {
                        //if ($end<$end_f) {
                        $str_json_array[] = array("metka" => "END");

                        //}
                    }
                }
                if (substr_count($array_str_parse[$i], "AIMG-") > 0) {
                    if (!substr_count($array_str_parse[$i], "#AIMG-") > 0) {
                        $array_str_parse[$i] = str_replace("\n", "", $array_str_parse[$i]);
                        $str_json_array[] = array("imgPeopleA" => "/uploads/unzip/" . $imgPatch . "/image/" . str_replace("AIMG-", "", $array_str_parse[$i]));
                    }
                }
                if (substr_count($array_str_parse[$i], "BIMG-") > 0) {
                    if (!substr_count($array_str_parse[$i], "#BIMG-") > 0) {
                        $array_str_parse[$i] = str_replace("\n", "", $array_str_parse[$i]);
                        $str_json_array[] = array("imgPeopleB" => "/uploads/unzip/" . $imgPatch . "/image/" . str_replace("BIMG-", "", $array_str_parse[$i]));
                    }
                }
                if (substr_count($array_str_parse[$i], "AMP-") > 0) {
                    if (!substr_count($array_str_parse[$i], "#AMP-") > 0) {
                        $array_str_parse[$i] = str_replace("\n", "", $array_str_parse[$i]);
                        $str_json_array[] = array("audioPeopleA" => "/uploads/unzip/" . $imgPatch . "/audio/" . str_replace("AMP-", "", $array_str_parse[$i]));
                    }
                }
                if (substr_count($array_str_parse[$i], "BMP-") > 0) {
                    if (!substr_count($array_str_parse[$i], "#BMP-") > 0) {
                        $array_str_parse[$i] = str_replace("\n", "", $array_str_parse[$i]);
                        $str_json_array[] = array("audioPeopleB" => "/uploads/unzip/" . $imgPatch . "/audio/" . str_replace("BMP-", "", $array_str_parse[$i]));
                    }
                }
                if (substr_count($array_str_parse[$i], "BRANCH-") > 0) {

                    if (!substr_count($array_str_parse[$i], "#BRANCH-") > 0) {

                        $array_branch_parse = explode("@", str_replace("BRANCH-", "", $array_str_parse[$i]));

                        $array_branch = "";
                        for ($br = 0; $br < count($array_branch_parse); $br++) {
                            $array_branch_parse[$br] = str_replace("\n", "", $array_branch_parse[$br]);
                            for ($j = $i; $j < count($array_str_parse); $j++) {

                                if (substr_count($array_str_parse[$j], $array_branch_parse[$br] . "-") > 0) {
                                    $tmp_br = explode("\n", str_replace($array_branch_parse[$br] . "-", "", $array_str_parse[$j]));

                                    $parse = $this->parseBranch($array_branch_parse[$br], $array_str_parse[$j], $imgPatch);
                                    if (!empty($parse) && $parse != "") {
                                        $array_branch["branch"][] = array("message" => $tmp_br[0], "content" => $parse);
                                    }
                                }
                            }
                        }
                        $str_json_array[] = $array_branch;
                        $i++;
                    }
                }
            }
            //unlink(Yii::app()->basePath . $filename);
            return json_encode(array("result" => $str_json_array));
        }
    }

    private function parseBranch($br, $br_str, $imgPatch) {
        $str_json_array = "";
        $num_br = str_replace("BR", "", $br);

        $array_str_parse = explode($num_br . "#", $br_str);
        for ($i = 0; $i < count($array_str_parse); $i++) {
            if (substr_count($array_str_parse[$i], "AN-") > 0) {
                if (!substr_count($array_str_parse[$i], "#AN-") > 0) {
                    $array_str_parse[$i] = str_replace("\n", "", $array_str_parse[$i]);
                    $str_json_array[] = array("nameA" => str_replace("AN-", "", $array_str_parse[$i]));
                }
            }
            if (substr_count($array_str_parse[$i], "BN-") > 0) {
                if (!substr_count($array_str_parse[$i], "#BN-") > 0) {
                    $array_str_parse[$i] = str_replace("\n", "", $array_str_parse[$i]);
                    $str_json_array[] = array("nameB" => str_replace("BN-", "", $array_str_parse[$i]));
                }
            }
            if (substr_count($array_str_parse[$i], "A-") > 0) {
                if (!substr_count($array_str_parse[$i], "#A-") > 0) {
                    $array_str_parse[$i] = str_replace("\n", "", $array_str_parse[$i]);
                    $str_json_array[] = array("peopleA" => str_replace("A-", "", $array_str_parse[$i]));
                }
            }
            if (substr_count($array_str_parse[$i], "AV-") > 0) {
                if (!substr_count($array_str_parse[$i], "#AV-") > 0) {
                    $array_str_parse[$i] = str_replace("\n", "", $array_str_parse[$i]);
                    $str_json_array[] = array("videoPeopleA" => str_replace("VA-", "", $array_str_parse[$i]));
                }
            }
            if (substr_count($array_str_parse[$i], "BV-") > 0) {
                if (!substr_count($array_str_parse[$i], "#BV-") > 0) {
                    $array_str_parse[$i] = str_replace("\n", "", $array_str_parse[$i]);
                    $str_json_array[] = array("videoPeopleB" => str_replace("VB-", "", $array_str_parse[$i]));
                }
            }
            if (substr_count($array_str_parse[$i], "C-") > 0) {
                if (!substr_count($array_str_parse[$i], "#C-") > 0) {
                    $array_str_parse[$i] = str_replace("\n", "", $array_str_parse[$i]);
                    $str_json_array[] = array("context" => str_replace("C-", "", $array_str_parse[$i]));
                }
            }
            if (substr_count($array_str_parse[$i], "BCALL-") > 0) {
                if (!substr_count($array_str_parse[$i], "#BCALL-") > 0) {
                    $array_str_parse[$i] = str_replace("\n", "", $array_str_parse[$i]);
                    $str_json_array[] = array("callPeopleB" => str_replace("BCALL-", "", $array_str_parse[$i]));
                }
            }
            if (substr_count($array_str_parse[$i], "BMCALL-") > 0) {
                if (!substr_count($array_str_parse[$i], "#BMCALL-") > 0) {
                    $array_str_parse[$i] = str_replace("\n", "", $array_str_parse[$i]);
                    $str_json_array[] = array("missCallPeopleB" => str_replace("BMCALL-", "", $array_str_parse[$i]));
                }
            }
            if (substr_count($array_str_parse[$i], "B-") > 0) {
                if (!substr_count($array_str_parse[$i], "#B-") > 0) {
                    $array_str_parse[$i] = str_replace("\n", "", $array_str_parse[$i]);
                    $str_json_array[] = array("peopleB" => str_replace("B-", "", $array_str_parse[$i]));
                }
            }
            if (substr_count($array_str_parse[$i], "T") > 0) {
                if (!substr_count($array_str_parse[$i], "#T-") > 0) {
                    $str_json_array[] = array("metka" => "STOP");
                }
            }
            if (substr_count($array_str_parse[$i], "E") > 0) {
                if (substr_count($array_str_parse[$i], "#E") == 0) {
                    //if ($end<$end_f) {
                    $str_json_array[] = array("metka" => "END");
                    //echo $end." ".$end_f." ".$i."+++==== ".$array_str_parse[$i];
                    //}
                }
            }
            if (substr_count($array_str_parse[$i], "AIMG-") > 0) {
                if (!substr_count($array_str_parse[$i], "#AIMG-") > 0) {
                    $array_str_parse[$i] = str_replace("\n", "", $array_str_parse[$i]);
                    $str_json_array[] = array("imgPeopleA" => "/uploads/unzip/" . $imgPatch . "/image/" . str_replace("AIMG-", "", $array_str_parse[$i]));
                }
            }
            if (substr_count($array_str_parse[$i], "BIMG-") > 0) {
                if (!substr_count($array_str_parse[$i], "#BIMG-") > 0) {
                    $array_str_parse[$i] = str_replace("\n", "", $array_str_parse[$i]);
                    $str_json_array[] = array("imgPeopleB" => "/uploads/unzip/" . $imgPatch . "/image/" . str_replace("BIMG-", "", $array_str_parse[$i]));
                }
            }
            if (substr_count($array_str_parse[$i], "AMP-") > 0) {
                if (!substr_count($array_str_parse[$i], "#AMP-") > 0) {
                    $array_str_parse[$i] = str_replace("\n", "", $array_str_parse[$i]);
                    $str_json_array[] = array("audioPeopleA" => "/uploads/unzip/" . $imgPatch . "/audio/" . str_replace("AMP-", "", $array_str_parse[$i]));
                }
            }
            if (substr_count($array_str_parse[$i], "BMP-") > 0) {
                if (!substr_count($array_str_parse[$i], "#BMP-") > 0) {
                    $array_str_parse[$i] = str_replace("\n", "", $array_str_parse[$i]);
                    $str_json_array[] = array("audioPeopleB" => "/uploads/unzip/" . $imgPatch . "/audio/" . str_replace("BMP-", "", $array_str_parse[$i]));
                }
            }
            if (substr_count($array_str_parse[$i], "BRANCH-") > 0) {
                if (!substr_count($array_str_parse[$i], "#BRANCH-") > 0) {
                    $array_branch_parse = explode("@", str_replace("BRANCH-", "", $array_str_parse[$i]));

                    $array_branch = "";

                    for ($br = 0; $br < count($array_branch_parse); $br++) {
                        $array_branch_parse[$br] = str_replace("\n", "", $array_branch_parse[$br]);
                        for ($j = $i; $j < count($array_str_parse); $j++) {

                            if (substr_count($array_str_parse[$j], $array_branch_parse[$br] . "-") > 0) {

                                $tmp_br = explode("\n", str_replace($array_branch_parse[$br] . "-", "", $array_str_parse[$j]));

                                $parse = $this->parseBranch($array_branch_parse[$br], $array_str_parse[$j], $imgPatch);
                                if (!empty($parse) && $parse != "") {
                                    $array_branch["branch"][] = array("message" => $tmp_br[0], "content" => $parse);
                                }
                            }
                        }
                    }
                    $str_json_array[] = $array_branch;
                    $i++;
                }
            }
        }


        return $str_json_array;
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        $typeList = Type::model()->findAll();
        $json_text_decode = json_decode($model->path_txt_file);


        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Books'])) {

            /*$model->attributes = $_POST['Books'];

            if (isset($_FILES["Books"]["name"]["txt_file"]) && !empty($_FILES["Books"]["name"]["txt_file"])) {
                $model->txt_file = CUploadedFile::getInstance($model, 'txt_file');
                $ext = explode(".", $model->txt_file->name);
                $nameFile = Helper::random_string();
                $fileName = $nameFile . '.' . $ext[1];

                if ($ext[1] == "zip") {
                    if ($model->txt_file->saveAs(Yii::app()->basePath . "/uploads/patch_rar/" . $fileName)) {
                        $zip = new ZipArchive;
                        if ($zip->open(Yii::app()->basePath . "/uploads/patch_rar/" . $fileName) === TRUE) {
                            $zip->extractTo(Yii::app()->basePath . "/uploads/unzip/" . $nameFile . "/");
                            $zip->close();
                            unlink(Yii::app()->basePath . "/uploads/patch_rar/" . $fileName);
                            if ($_POST['Books']['type_id'] == 2) {
                                $model->path_txt_file = $this->ParseBranchTxtFile("/uploads/unzip/" . $nameFile . "/book.txt", $nameFile);
                            } else {
                                $model->path_txt_file = $this->parseTxtFile("/uploads/unzip/" . $nameFile . "/book.txt", $nameFile);
                            }
                        }
                    }
                } else {

                    if ($model->txt_file->saveAs(Yii::app()->basePath . "/uploads/patch_txt/" . $fileName)) {

                        if ($_POST['Books']['type_id'] == 2) {
                            $model->path_txt_file = $this->ParseBranchTxtFile("/uploads/patch_txt/" . $fileName, null);
                        } else {
                            $model->path_txt_file = $this->parseTxtFile("/uploads/patch_txt/" . $fileName, null);
                        }
                    }
                }
            }

            if (isset($_FILES["Books"]["name"]["cover_file"]) && !empty($_FILES["Books"]["name"]["cover_file"])) {
                if (file_exists(Yii::app()->basePath . $model->path_cover_file)) {
                    unlink(Yii::app()->basePath . $model->path_cover_file);
                }
                $model->cover_file = CUploadedFile::getInstance($model, 'cover_file');
                $ext1 = explode(".", $model->cover_file->name);
                $fileName1 = Helper::random_string() . '.' . $ext1[1];
                if ($model->cover_file->saveAs(Yii::app()->basePath . "/uploads/patch_cover/" . $fileName1)) {
                    $model->path_cover_file = "/uploads/patch_cover/" . $fileName1;
                }
            }*/
            
            $json_text_decode->result[0]->nameA = $_POST['Books']['peopleA'];
            $json_text_decode->result[1]->nameB = $_POST['Books']['peopleB'];
            
            $model->name = $_POST['Books']['name'];
            $model->author = $_POST['Books']['author'];
            $model->path_txt_file = json_encode($json_text_decode);
            $model->last_modified = new CDbExpression('NOW()');

            if ($model->save()) {
                $this->redirect(array('books/createtext/id/' . $model->id_book));
            }
        }

        $this->render('update', array(
            'model' => $model,
            'typeList' => $typeList,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Books');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Books('search');
        $model->unsetAttributes();  // clear any default values
        $model->status = 1;
        if (isset($_GET['Books']))
            $model->attributes = $_GET['Books'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Books the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Books::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Books $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'books-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
