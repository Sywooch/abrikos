<?php

namespace frontend\modules\quiz\models;

use Yii;

/**
 * This is the model class for table "quiz_answer".
 *
 * @property integer $id
 * @property string $text
 * @property integer $right
 * @property integer $quest
 * @property string $date
 * @property integer $sort
 *
 * @property QuizQuest $quest0
 */
class Answer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'quiz_answer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text', 'correct', 'quest', 'sort'], 'required'],
            [['text'], 'string'],
            [['correct', 'quest', 'sort'], 'integer'],
            [['date'], 'safe'],
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
            'text' => 'Техт ответа',
            'right' => 'Верный',
            'quest' => 'Quest',
            'date' => 'Дата',
            'sort' => 'Сортировка',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuest0()
    {
        return $this->hasOne(Quest::className(), ['id' => 'quest']);
    }

	public function getImage()
	{
		return '/uploads/quiz/answer/' . $this->id . '.jpeg';
	}

	public function getImagePath()
	{
		return Yii::getAlias('@app/web') . $this->image;
	}

}
