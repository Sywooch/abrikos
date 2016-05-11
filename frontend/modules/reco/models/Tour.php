<?php

namespace frontend\modules\reco\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "reco".
 *
 * @property integer $id
  * @property string $date
 * @property string $answer
  * @property string $letters
 * @property string $shuffle
 * @property string $link getLink()
 * @property string $imagePath
 * @property string $imageUrl getImageUrl()
 * @property array $images getImages()
 * @property integer $letters_count
 * @property string $image getImage()
 * @property string $email
 * @property boolean $sendmail
 * @property boolean $enabled
 * @property integer $quest
 * @property array $formatter getFormatter()
 * @property boolean $eng
 * @property integer $sort
 *
 * @property User $user0
 * @property Quest $quest0
 */
class Tour extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reco_tour';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['answer'], 'required'],
            [['enabled','eng','sort'], 'integer'],
            ['letters_count', 'integer', 'min'=>5,'max'=>10],
            [['date', 'shuffle'], 'safe'],
	        ['answer','oneword'],
            [['answer'], 'string', 'max' => 255],
            [['letters'], 'string', 'max' => 10],
        ];
    }

	public function oneword($attribute,$params)
	{
		if (!preg_match('/^([A-Za-zА-Яа-я]+)$/u', $this->$attribute)) {
			$this->addError($attribute, 'Ответ должен состоять только из букв в одно слово');
		}
	}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user' => 'User',
            'date' => 'Дата',
            'answer' => 'Ответ',
            'session' => 'Сессия',
            'letters' => 'Дополнительные буквы',
	        'letters_count' =>'Количество дополнительных букв',
	        'enabled' => 'Опубликовать',
	        'eng'=>'Добавочные латинскими'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
	public function getQuest0()
	{
		return $this->hasOne(Quest::className(), ['id' => 'quest']);
	}

	public function getLink()
	{
		return Yii::$app->urlManager->createAbsoluteUrl(['/reco/view', 'id' => $this->id]);
		//return 'http://' . $_SERVER['SERVER_NAME'] . '/'.$this->tableName().'/view/' . $this->id;
    }

	public function getImagePath($i)
	{
		return Yii::getAlias('@app/web') . $this->getImageUrl($i);
	}

	public function getImageName($i)
	{
		return $this->id . '-' . ($i*1) . '.jpeg';
	}

	public function getImageUrl($i)
	{
		return '/uploads/reco/' . $this->getImageName($i);
	}

	public function getImageId($i)
	{
		return md5($this->getImageUrl($i));
	}

	public function getImages()
	{
		$ret = [];
		for($i=0;$i<4;$i++){
			if(file_exists($this->getImagePath($i)))
				$ret[] =['id'=>$this->getImageId($i), 'src' => $this->getImageUrl($i), 'status'=>1, 'order'=>$i];
			else
				$ret[] =['id'=>$this->getImageId($i), 'src' => '/images/noimage.jpeg','status'=>0];
		}
		return $ret;
	}

	public function getImage()
	{
		return  Yii::$app->urlManager->createAbsoluteUrl(file_exists($this->getImagePath(0)) ? $this->getImageUrl(0):'/images/noimage.jpeg') ;
	}

	public function getFormatter()
	{
		return ['tour'=>$this, 'images'=>$this->images];
	}

	public function getViewdata(){
		$ret['id'] = $this->id;
		$ret['name'] = $this->quest0->name;
		$ret['answer'] = mb_strlen($this->answer,'utf-8');
		$ret['shuffle'] = $this->shuffle;
		$ret['images'] = $this->images;
		return $ret;
	}

}
