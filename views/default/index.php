<?php

//use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $searchModel culturePnPsu\activity\models\ActivitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Activities');
$this->params['breadcrumbs'][] = $this->title;


Modal::begin([
    'header' => Html::tag('h4', 'Add Event'),
    'id' => 'modalForm',
    'size' => 'modal-lg'
]);
echo Html::tag('div', '', ['id' => 'modalContent']);

Modal::end();

$select = 'function(start, end) {	
                //alert("Event: " + start+" "+end);
                
            $.get( "' . Yii::$app->urlManager->createUrl('/activity/default/create') . '",
                 {
                    "start":moment(start).format("YYYY-MM-DD"),
                    "end":moment(end).format("YYYY-MM-DD"),
                },
                function(data){   
                $("#modalForm").find("#modalContent").html(data);
                $("#modalForm").modal("show");
               // console.log(data);
            });               
            }';
$eventClick = 'function(calEvent, jsEvent, view) {
                console.log("Event View: " + calEvent.title);
		var data={                   
                    id:calEvent.id,
                }                
                $.ajax({
                    url: "' . Yii::$app->urlManager->createUrl('/activity/default/view') . '",
                    data: data,
                    type: "GET",
                    success: function(data) {                        
                        $("#modalForm").find("#modalContent").html(data);
                        $("#modalForm").modal("show");
                    }
                });


            }';
$eventResize = 'function(event, delta, revertFunc) {
                start = moment(event.start).format("YYYY-MM-DD");
                end = moment(event.end).format("YYYY-MM-DD");
		var data={
                    start:start,
                    end:end,
                    id:event.id,
                    allday:event.allday
                }
                
                $.ajax({
                    url: "' . Yii::$app->urlManager->createUrl('/activity/default/resize') . '",
                    data: data,
                    type: "POST",
                    dataType:"json",
                    success: function(data) {
                        //if(data.success){
                            $("#calendar").fullCalendar("refetchEvents");
                       // }
                    }
                });
}';

$DragJS = <<<EOF
/* initialize the external events
-----------------------------------------------------------------*/
$('#calendar .fc-event').each(function() {
// store data so the calendar knows to render an event upon drop
$(this).data('event', {
title: $.trim($(this).text()), // use the element's text as the event title
stick: true // maintain when user navigates (see docs on the renderEvent method)
});
// make the event draggable using jQuery UI
$(this).draggable({
zIndex: 999,
 revert: true, // will cause the event to go back to its
revertDuration: 0  //  original position after the drag
});
});
EOF;
$this->registerJs($DragJS);
?>
<div class='box box-info'>
    <div class='box-header'>
     <!-- <h3 class='box-title'><?= Html::encode($this->title) ?></h3>-->
    </div><!--box-header -->

    <div class='box-body pad'>

        <p>
            <?= Html::a(Yii::t('app', 'Create Activity'), ['create'], ['class' => 'btn btn-success']) ?>
        </p>

        <?=
        \yii2fullcalendar\yii2fullcalendar::widget(
                [
                    //'events' => $events,                    
                    'ajaxEvents' => Url::toRoute(['/activity/default/jsoncalendar']),
                    'options' => ['id' => 'calendar', 'lang' => 'th'],
                    'header' => [
                        'left' => 'prev,next today',
                        'center' => 'title',
                        'right' => 'month,basicWeek,basicDay'
                    ],
                    'clientOptions' => [
//                        'views' => [
//                            'year' => [
//                                'type' => 'YearView',
//                            ]
//                        ],
                        'selectable' => true,
                        'selectHelper' => true,
                        'draggable' => true,
                        'editable' => true,
                        //'drop' => new JsExpression($eventDrop),
                        'select' => new JsExpression($select),
                        'eventClick' => new JsExpression($eventClick),
                        'eventDrop' => new JsExpression($eventResize),
                        'eventResize' => new JsExpression($eventResize),
                    ],
                ]
        );
        ?>


    </div><!--box-body pad-->
</div><!--box box-info-->





