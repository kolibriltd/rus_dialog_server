<?php

$this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Profile")  . ". Виды деятельности";
$this->breadcrumbs=array(
	UserModule::t("Profile")=>array('profile'),
	UserModule::t("Изменение видов деятельности"),
);
?>

<div class="two_column">
<?php echo $this->renderPartial('menu'); ?>
<div class="right_part">

<h1 class="no_mt"><?php echo UserModule::t('Виды деятельности'); ?></h1>

<div class="form">

<?php echo CHtml::beginForm(); ?>
 
<?php echo CHtml::errorSummary($model); ?>

<div class="input_str">
    <label>&nbsp;</label>
     <div class="for_left_fullwidth">
            
            <?php
                $i=0;
                $cat = Categories::items('Name');
                $old_categories = UserCategories::items('category', Yii::app()->user->id);
                $cln1 = ceil(count($cat) / 2);
                $cln2 = count($cat);
                foreach ($cat as $key => $category) {
                    $arr = array ('value' => $key);
                    for($j=0; $j < count($old_categories); $j++){
                        if ($old_categories[$j] == $key) {
                            $arr = array ('value' => $key, 'checked' => 'true');
                            break;
                        }
                    }
                    if ($i==0) echo '<div class="footer_cat">';
                    if ($i == $cln1) echo '</div><div class="footer_cat footer_cat_last">';
                    echo CHtml::activeCheckBox($model,'more_category_id['.$i++.']', $arr)." ".$category; 
                    if ($i != $cln1 AND $i != $cln2) echo "<br/>" . chr(10);
                }?>
                </div>
    </div>
</div>
<div class="input_str">
     Выбрать: <a href="#select_all">Все</a>, <a href="#select_none">Ни одного</a>
</div>

<div class="input_str">
<label>&nbsp;</label>
<?php echo CHtml::submitButton('Изменить',array('class'=>'submit')); ?>
</div>
 
<?php echo CHtml::endForm(); ?>
</div>

</div>
</div>

<script> 
        // Выбор всех
        //При клике на ссылку "Все", активируем checkbox
        $("a[href='#select_all']").click( function() {
           $("input:checkbox:enabled").attr('checked', true);
            return false;
        });
 
        // Ни одного
        $("a[href='#select_none']").click( function() {
             $("input:checkbox").attr('checked', false);
            return false;
        });
 
</script>