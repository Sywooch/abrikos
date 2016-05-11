<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "fblogin".
 *
 * @property integer $id
 * @property integer $fbid
 * @property integer $user
 * @property string $email
 *
 * @property User $user0
 */
class Fblogin extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fblogin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fbid', 'user', 'email'], 'required'],
            [['fbid', 'user'], 'integer'],
            [['email'], 'string', 'max' => 255],
            [['user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'fbid' => Yii::t('app', 'Fbid'),
            'user' => Yii::t('app', 'User'),
            'email' => Yii::t('app', 'Email'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser0()
    {
        return $this->hasOne(User::className(), ['id' => 'user']);
    }
}
