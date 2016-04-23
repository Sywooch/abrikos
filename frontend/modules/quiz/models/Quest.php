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
 * @property QuizQuiz $quiz0
 * @property string youtube
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
            [['name', 'youtube'], 'string'],
            [['quiz', 'rate', 'sort'], 'integer'],
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
		return $this->hasMany(Answer::className(), ['quest' => 'id']);
	}

	public function getImage()
	{
		return '/uploads/quiz/quest/' . $this->id . '.jpeg';
    }

	public function getImagePath()
	{
		return Yii::getAlias('@app/web') . $this->image;
	}
}
