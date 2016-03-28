<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "photo".
 *
 * @property integer $id
 * @property string $type
 * @property string $date
 *
 * @property User $user0
 */
class Photo extends \yii\db\ActiveRecord
{
    public $imageFile;
	public $imageUrl;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'photo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'user'], 'required'],
            [['date'], 'safe'],
	        [['text'], 'string'],
            [['type'], 'string', 'max' => 10],
            //[['user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user' => 'id']],
	        [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, gif, jpeg'],
	        ['imageUrl','url']
        ];
    }

	public function upload()
	{
		if ($this->validate()) {
			$this->id = time();
			$this->imageFile->saveAs('uploads/' . $this->id . '.' . $this->imageFile->extension);
			return true;
		} else {
			return false;
		}
	}
	
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'date' => 'Date',
            'user' => 'User',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser0()
    {
        return $this->hasOne(User::className(), ['id' => 'user']);
    }

	public function getFile()
	{
		return '/uploads/' . $this->id . '.' . $this->type;
	}
}
