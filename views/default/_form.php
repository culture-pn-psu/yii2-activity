<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use culturePnPsu\activity\models\Activity;
use culturePnPsu\activity\models\Calendar;
use kartik\widgets\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model culturePnPsu\activity\models\Activity */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="activity-form">

    <?php $form = ActiveForm::begin(['id' => $model->formName()]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <div class='row'>
        <div class="col-sm-4">
            <?= $form->field($model, 'calendar_id')->dropDownList(Calendar::getCalendarList(), ['prompt' => Yii::t('app', 'เลือก')]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'status')->dropDownList(Activity::getItemStatus()) ?>
        </div>

        <div class="col-sm-5"> 

            <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <?= $form->field($model, 'detail')->textarea(['rows' => 6]) ?>




    <div class='row'>
        <div class="col-sm-4">
            <?=
            $form->field($model, 'start')->widget(DateTimePicker::classname(), [
                'language' => \Yii::$app->language,
                'value' => date('Y-m-d H:i:s'),
                'removeButton' => false,
                'pickerButton' => ['icon' => 'time'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd hh:ii:ss',
                ]
            ]);
            ?>
        </div>
        <div class="col-sm-4">
            <?=
            $form->field($model, 'end')->widget(DateTimePicker::classname(), [
                'language' => \Yii::$app->language,
                'value' => date('Y-m-d H:i:s'),
                'removeButton' => false,
                'pickerButton' => ['icon' => 'time'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd hh:ii:ss',
                ]
            ]);
            ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'allday')->radioList([ 1 => '1', 0 => '0',], ['prompt' => '']) ?>  
        </div>
    </div>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php
if (isset($ajax)) {
    //echo $ajax;
    $this->registerJs(' 
    $("form#' . $model->formName() . '").on("beforeSubmit",function(e){        
        var $form = $(this);
        $.post( 
            $form.attr("action"),
            $form.serialize()
        ).done(function(result){ 
            $form.trigger("reset");
            console.log(result);
           $("#modalForm").modal("hide");
            $("#calendar").fullCalendar("unselect");
           $("#calendar").fullCalendar("refetchEvents");
        });   
        
        return false;
    });
        
');
}