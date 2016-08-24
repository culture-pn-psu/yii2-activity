<?php

namespace culturePnPsu\activity\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "calendar".
 *
 * @property integer $id
 * @property string $title
 * @property string $color
 * @property integer $created_at
 * @property integer $created_by
 *
 * @property Activity[] $activities
 * @property User $createdBy
 */
class Calendar extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'calendar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'color'], 'required'],
            [['created_at', 'created_by'], 'integer'],
            [['title'], 'string', 'max' => 100],
            [['color'], 'string', 'max' => 7]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'ปฎิทิน'),
            'color' => Yii::t('app', 'สีประจำกลุ่มกิจรรม'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivities()
    {
        return $this->hasMany(Activity::className(), ['calendar_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }    
    
    public static function getCalendarList()
    {
        return ArrayHelper::map(self::find()->all(),'id','title');
    }
}
