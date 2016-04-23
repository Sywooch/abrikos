<?php

namespace app\modules\poll\models;

use Yii;

/**
 * This is the model class for table "vote".
 *
 * @property integer $id
 * @property string $date
 * @property integer $answer
 * @property string $ip
 *
 * @property Answer $answer0
 */
class Vote extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'poll_vote';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['answer', 'ip'], 'required'],
            [['answer'], 'integer'],
            [['ip'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'answer' => 'Answer',
            'ip' => 'Ip',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswer0()
    {
        return $this->hasOne(Answer::className(), ['id' => 'answer']);
    }
}
