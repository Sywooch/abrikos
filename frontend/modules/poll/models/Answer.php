<?php

namespace app\modules\poll\models;

use Yii;

/**
 * This is the model class for table "answer".
 *
 * @property integer $id
 * @property integer $question
 * @property string $text
 * @property integer $count
 * @property integer $order
 *
 * @property Question $question0
 */
class Answer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'poll_answer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question', 'text', 'count', 'order'], 'required'],
            [['question', 'count', 'order'], 'integer'],
            [['text'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'question' => 'Относится к вопросу',
            'text' => 'Текст ответа',
            'count' => 'Количество ответов',
            'order' => 'Сортировка',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion0()
    {
        return $this->hasOne(Question::className(), ['id' => 'question']);
    }

	public function getVotes()
	{
		return $this->hasMany(Vote::className(), ['answer' => 'id']);
	}

	public function beforeDelete()
	{
		if (parent::beforeDelete()) {
			foreach($this->votes as $v){$v->delete();}
			return true;
		} else {
			return false;
		}
	}
}
