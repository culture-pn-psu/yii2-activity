<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model culturePnPsu\activity\models\Activity */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class='box box-info'>
    <div class='box-header'>
     <!-- <h3 class='box-title'><?= Html::encode($this->title) ?></h3>-->
    </div><!--box-header -->

    <div class='box-body pad'>

        <p>
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary btnAjaxUpdate']) ?>
            <?=
            Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger btnAjaxDel',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ])
            ?>
        </p>

        <?=
        DetailView::widget([
            'model' => $model,
            'attributes' => [
                //'id',
                'title',
                'calendar_id',
                'detail:ntext',
                'status',
                'link',
                'start',
                'end',
                'allday',
                'article_id',
                'created_at',
                'created_by',
                'updated_at',
                'updated_by',
            ],
        ])
        ?>


    </div><!--box-body pad-->
</div><!--box box-info-->
<?php
if (isset($ajax)) {
    $this->registerJs(' 
$(".btnAjaxUpdate").click(function(){        
    $.get( "' . Yii::$app->urlManager->createUrl('/activity/default/update') . '",
        {
           "id":' . $model->id . ',         
       },
       function(data){   
       $("#modalForm").find("#modalContent").html(data);
       $("#modalForm").modal("show");
      // console.log(data);
    });
       $("#calendar").fullCalendar("unselect");
    
       return false;
});

$(".btnAjaxDel").click(function(){        
    $.get( "' . Yii::$app->urlManager->createUrl('/activity/default/delete-ajax') . '",
       {
           "id":' . $model->id . ',         
       },
       function(data){   
        $("#modalForm").modal("hide");
        $("#calendar").fullCalendar("refetchEvents");
        }
    );   
       return false;
});
        
');
}
