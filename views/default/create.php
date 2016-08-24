<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model culturePnPsu\activity\models\Activity */

$this->title = Yii::t('app', 'Create Activity');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class='box box-info'>
    <div class='box-header'>
     <!-- <h3 class='box-title'><?= Html::encode($this->title) ?></h3>-->
    </div><!--box-header -->
    
    <div class='box-body pad'>

    <?= $this->render('_form', [
        'model' => $model,
        'ajax' => isset($ajax)?$ajax:null
    ]) ?>

    </div><!--box-body pad-->
 </div><!--box box-info-->
