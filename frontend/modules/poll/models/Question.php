<?php

namespace app\modules\poll\models;

use Yii;

/**
 * This is the model class for table "question".
 *
 * @property integer $id
 * @property string $text
 * @property string $date
 * @property integer $active
 * @property integer $user
 *
 * @property Answer[] $answers
 * @property User $user0
 */
class Question extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'poll_question';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text', 'active', 'user'], 'required'],
            [['text'], 'string'],
            [['date'], 'safe'],
            [['active', 'user', 'multiselect'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Текст опроса',
            'date' => 'Дата изменения',
            'active' => 'Разрешить отвечать',
            'user' => 'Владелец',
	        'multiselect'=>'Множественный выбор'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(Answer::className(), ['question' => 'id'])->orderBy('order');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser0()
    {
        return $this->hasOne(User::className(), ['id' => 'user']);
    }

	public function getCode()
	{
		return '<!-- '.$_SERVER['SERVER_NAME'].'--><div id="poll-'.$this->id.'"></div><script src="http://'.$_SERVER['SERVER_NAME'].'/poll/script/'.$this->id.'" type="application/javascript"></script><!-- '.$_SERVER['SERVER_NAME'].'-->';
	}

	public function getLink()
	{
		return 'http://' . $_SERVER['SERVER_NAME'] . '/poll/' . $this->id;
    }
}
