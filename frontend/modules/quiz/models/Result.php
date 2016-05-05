<?php

namespace frontend\modules\quiz\models;

use Yii;

/**
 * This is the model class for table "{{%quiz_result}}".
 *
 * @property integer $id
 * @property string $date
 * @property integer $quest
 * @property integer $answer
 * @property string $ip
 * @property string $round
 * @property  integer $time
 * @property  string $email
 *
 * @property Answer $answer0
 * @property Quest $quest0
 */
class Result extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%quiz_result}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['quest', 'answer', 'ip', 'round'], 'required'],
            [['quest', 'answer','time'], 'integer'],
            [['round','email'],'string'],
            [['ip'], 'string', 'max' => 16],
	        [['quest', 'round'], 'unique', 'targetAttribute' => ['quest', 'round'], 'message' => 'Ответ на этот вопрос уже принят.'],
            [['answer'], 'exist', 'skipOnError' => true, 'targetClass' => Answer::className(), 'targetAttribute' => ['answer' => 'id']],
            [['quest'], 'exist', 'skipOnError' => true, 'targetClass' => Quest::className(), 'targetAttribute' => ['quest' => 'id']],
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
            'quest' => 'Quest',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuest0()
    {
        return $this->hasOne(Quest::className(), ['id' => 'quest']);
    }

	public function getIsRight()
	{
		return $this->answer0->correct;
	}

}
