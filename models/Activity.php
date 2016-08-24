<?php

namespace culturePnPsu\activity\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "activity".
 *
 * @property integer $id
 * @property string $title
 * @property integer $calendar_id
 * @property string $detail
 * @property integer $status
 * @property string $link
 * @property string $start
 * @property string $end
 * @property string $allday
 * @property integer $article_id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Calendar $calendar
 * @property User $createdBy
 * @property User $updatedBy
 */
class Activity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'calendar_id'], 'required'],
            [['calendar_id', 'status', 'article_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['detail', 'allday'], 'string'],
            [['start', 'end'], 'safe'],
            [['title', 'link'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'ชือกิจกรรม'),
            'calendar_id' => Yii::t('app', 'ปฎิทิน'),
            'detail' => Yii::t('app', 'รายละเอียด'),
            'status' => Yii::t('app', 'สถานะ'),
            'link' => Yii::t('app', 'ลิงค์'),
            'start' => Yii::t('app', 'เริ่ม'),
            'end' => Yii::t('app', 'สิ้นสุด'),
            'allday' => Yii::t('app', 'แสดงทั่งวัน'),
            'article_id' => Yii::t('app', 'บทความ'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
        ];
    }

    public static function itemsAlias($key) {
        $items = [
            'status' => [
                0 => Yii::t('app', 'ร่าง'),
                1 => Yii::t('app', 'แสดง'),
                2 => Yii::t('app', 'ซ่อน')
            ],
            
        ];
        return ArrayHelper::getValue($items, $key, []);
    }
    
    public function getStatusLabel() {
        return ArrayHelper::getValue($this->getItemStatus(), $this->status);
    }

    public static function getItemStatus() {
        return self::itemsAlias('status');
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCalendar()
    {
        return $this->hasOne(Calendar::className(), ['id' => 'calendar_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
    
    public static function getActivity()
    {
        $activity = self::find()->where(['status'=>1])->all();
        $events = array();
        foreach ($activity as $act) {
            $Event = new \yii2fullcalendar\models\Event();
            $Event->id = $act->id;
            $Event->title = $act->title;
            $Event->color = $act->calendar->color;
            $Event->start = Yii::$app->formatter->asDate($act->start, 'php:Y-m-d');
            $Event->end = Yii::$app->formatter->asDate($act->end, 'php:Y-m-d');
            $Event->editable = false;
            $Event->allDay = true ;
            $Event->durationEditable = false;
            $Event->startEditable = false;
            $events[] = $Event;
        }
        return $events;
    }
}
