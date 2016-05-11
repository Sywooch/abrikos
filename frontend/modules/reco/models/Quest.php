<?php

namespace frontend\modules\reco\models;

use common\models\User;
use Yii;


/**
 * This is the model class for table "reco_quest".
 *
 * @property integer $id
 * @property integer $user
 * @property string $session
 * @property string $name
 * @property string $date
 * @property string $email
 * @property boolean $published
 * @property string $picture
 *
 * @property Tour[] $tours
 * @property User $user0
 */
class Quest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reco_quest';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user', 'session', 'name'], 'required'],
            [['user', 'published', 'sendmail'], 'integer'],
            [['date'], 'safe'],
            ['email','email'],
            [['session', 'picture'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 255],
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
            'user' => 'User',
            'session' => 'Session',
            'name' => 'Название',
            'published' => 'Опубликовано',
            'date' => 'Date',
	        'sendmail' => 'Разрешить отправку сообщений о результатах',
	        'tours_count' => 'Количество заданий'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTours()
    {
        return $this->hasMany(Tour::className(), ['quest' => 'id'])->orderBy('sort, date desc');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser0()
    {
        return $this->hasOne(User::className(), ['id' => 'user']);
    }

	public function getLink()
	{
		return Yii::$app->urlManager->createAbsoluteUrl(['/reco/'. $this->id]);
		//return 'http://' . $_SERVER['SERVER_NAME'] . '/'.$this->tableName().'/view/' . $this->id;
	}

	public function getImage()
	{
		return $this->picture ? '/uploads/reco/'. $this->picture :  $this->tours[0]->image;
    }
}
