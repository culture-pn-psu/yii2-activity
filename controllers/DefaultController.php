<?php

namespace culturePnPsu\activity\controllers;

use Yii;
use culturePnPsu\activity\models\Activity;
use culturePnPsu\activity\models\ActivitySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * DefaultController implements the CRUD actions for Activity model.
 */
class DefaultController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Activity models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ActivitySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $activity = Activity::find()->all();
        $events = array();
        foreach ($activity as $act) {
            $Event = new \yii2fullcalendar\models\Event();
            $Event->id = $act->id;
            $Event->title = $act->title;
            $Event->color = $act->calendar->color;
            $Event->start = Yii::$app->formatter->asDate($act->start, 'php:Y-m-d');
            $Event->end = Yii::$app->formatter->asDate($act->end, 'php:Y-m-d');
            $Event->editable = true;
            $Event->allDay = $act->allday ? true : false;
            $Event->durationEditable = true;
            $Event->startEditable = $act->allday ? true : false;
            $events[] = $Event;
        }

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'events' => $events
        ]);
    }

    /**
     * Displays a single Activity model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                        'model' => $this->findModel($id),
                        'ajax' => Yii::$app->request->isAjax
            ]);
        } else {
            return $this->render('view', [
                        'model' => $this->findModel($id),
                        'ajax' => Yii::$app->request->isAjax
            ]);
        }
    }

    /**
     * Creates a new Activity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($start = null, $end = null) {
        $model = new Activity();
        $model->start = $start;
        $model->end = $end;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->isAjax) {
                echo 'Success';
            } else {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            if (Yii::$app->request->isAjax) {
                //echo Yii::$app->request->isAjax;
                return $this->renderAjax('create', [
                            'model' => $model,
                            'ajax' => Yii::$app->request->isAjax
                ]);
            } else {
                return $this->render('create', [
                            'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Activity model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->isAjax) {
                echo 'Success';
            } else {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('update', [
                            'model' => $model,
                            'ajax' => Yii::$app->request->isAjax
                ]);
            } else {
                return $this->render('update', [
                            'model' => $model,
                ]);
            }
        }
    }

    /**
     * Deletes an existing Activity model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();
        if (Yii::$app->request->isAjax) {
            echo 'Success';
        } else {
            return $this->redirect(['index']);
        }
    }
    
    public function actionDeleteAjax($id) {
        $this->findModel($id)->delete();
        if (Yii::$app->request->isAjax) {
            echo 'Success';
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Activity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Activity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Activity::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionJsoncalendar($start = NULL, $end = NULL, $_ = NULL) {
        $activity = Activity::find()->all();
        $events = array();
        foreach ($activity as $act) {
            $Event = new \yii2fullcalendar\models\Event();
            $Event->id = $act->id;
            
            $status=$act->status==0?' ('.$act->statusLabel.')':'';
            $Event->title = $act->title.$status; 
            
            $Event->textColor = $act->status?'#fff':'#ff0000';
            $Event->color = $act->calendar->color;
            $Event->start = Yii::$app->formatter->asDate($act->start, 'php:Y-m-d');
            $Event->end = Yii::$app->formatter->asDate($act->end, 'php:Y-m-d');
            $Event->editable = true;
            $Event->allDay = true;
            $Event->durationEditable = true;
            $Event->startEditable = true;
            $events[] = $Event;
        }
        //print_r($events);
        header('Content-type: application/json');
        echo Json::encode($events);
        Yii::$app->end();
    }

    public function actionResize($id = null) {
        $post = Yii::$app->request->post();
        $model = $this->findModel($post['id']);
        $json = ["success" => false, $post];
        //print_r($post);
        if ($model->load(['Activity' => Yii::$app->request->post()])) {
            if ($model->save()) {
                $json = ["success" => true];
            }
        }
        header('Content-type: application/json');
        echo Json::encode($json);
        Yii::$app->end();
    }

}
