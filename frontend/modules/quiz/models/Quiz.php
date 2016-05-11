<?php

namespace frontend\modules\quiz\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "{{%quiz_quiz}}".
 *
 * @property integer $id
 * @property string $sessionId
 * @property integer $user
 * @property string $date
 * @property string $name
 * @property string $description
 * @property integer $enabled
 * @property integer $show_result
 * @property integer $result_correct_inform
 * @property integer $result_correct_show
 * @property integer $publish
 * @property string $email
 * @property integer $send_results
 * @property integer $facebook_count
 *
 * @property Quest[] $quests
 * @property User $user0
 * @property Stat[] $stats
 * @property string $image
 * @property string $link
 */
class Quiz extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'quiz_quiz';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['user', 'name'], 'required'],
			[['user', 'enabled', 'publish', 'show_result', 'result_correct_show', 'result_correct_inform', 'send_results', 'facebook_count'], 'integer'],
			[['date'], 'safe'],
			[['sessionId', 'description'], 'string'],
			[['name'], 'string', 'max' => 100],
			[['email'], 'email'],
			[['user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user' => 'id']],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'user' => 'Пользователь',
			'date' => 'Дата',
			'name' => 'Название',
			'description' => 'Описание',
			'enabled' => 'Доступна для ответов',
			'show_result' => 'Показывать результаты',
			'result_correct_show' => 'После завершения показать верный ответ',
			'result_correct_inform' => 'После завершения информировать ответ верный или нет',
			'publish' => 'Публиковать',
			'email' => 'E-mail',
			'send_results' => 'Отправлять результаты участников на мой e-mail'
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getQuests()
	{
		return $this->hasMany(Quest::className(), ['quiz' => 'id'])->orderBy('quiz_quest.sort desc');
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getStats()
	{
		return $this->hasMany(Stat::className(), ['quiz' => 'id'])->orderBy('quiz_stat.date desc');
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser0()
	{
		return $this->hasOne(User::className(), ['id' => 'user']);
	}



	public function noAnswers()
	{
		foreach ($this->quests as $quest) {
			$result = $quest->noAnswers();
			if ($result) return $result;
		}
		return 0;
	}

	public function getCover()
	{
		return $this->image;
	}

	public function getImage()
	{
		return '/uploads/quiz/' . $this->id . '.jpeg';
	}

	public function getImagePath()
	{
		return Yii::getAlias('@app/web') . $this->image;
	}

	public function findEmail()
	{
		if ($this->user) return $this->user0->email;
		if ($this->email) return $this->email;
		return false;
	}

	public function getTour()
	{
		return date('Ym');
	}

	public function getLink()
	{
		return 'https://www.abrikos.su/quiz/view/' . $this->id . '/' . $this->tour;
	}

	public function getRating()
	{
		return $this->stats[0]->share + $this->stats[0]->comment;
	}
}
