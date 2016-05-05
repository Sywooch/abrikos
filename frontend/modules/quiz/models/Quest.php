<?php

namespace frontend\modules\quiz\models;

use Yii;

/**
 * This is the model class for table "{{%quiz_quest}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $quiz
 * @property integer $rate
 * @property integer $sort
 * @property Quiz $quiz0
 * @property string youtube
 * @property integer multi
 * @property string description
 */
class Quest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'quiz_quest';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'quiz', 'rate'], 'required'],
            [['name', 'youtube', 'description'], 'string'],
            [['quiz', 'rate', 'sort', 'multi'], 'integer'],
            [['quiz'], 'exist', 'skipOnError' => true, 'targetClass' => Quiz::className(), 'targetAttribute' => ['quiz' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Вопрос',
            'quiz' => 'Викторина',
            'rate' => 'Баллы',
	        'description' => 'Комментарий к ответу'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuiz0()
    {
        return $this->hasOne(Quiz::className(), ['id' => 'quiz']);
    }

	public function getAnswers()
	{
		return $this->hasMany(Answer::className(), ['quest' => 'id'])->orderBy('quiz_answer.id');
	}

	public function getAnswersRand()
	{
		return $this->hasMany(Answer::className(), ['quest' => 'id'])->orderBy('rand()');
	}

	public function getRightAnswers()
	{
		return $this->hasMany(Answer::className(), ['quest' => 'id'])->where(['correct'=>1]);
	}


	public function getImage()
	{
		return '/uploads/quiz/quest/' . $this->id . '.jpeg';
    }

	public function getImagePath()
	{
		return Yii::getAlias('@app/web') . $this->image;
	}

	public function noAnswers()
	{
		$sum = 0;
		foreach ($this->answers as $answer) {
			$sum += $answer->correct;
		}
		if(!$sum) return $this; else return 0;
	}
}
