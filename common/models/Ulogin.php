<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ulogin".
 *
 * @property integer $user
 * @property string $uid
 * @property string $network
 * @property string $identity
 * @property integer $id
 *
 * @property User $user0
 */
class Ulogin extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ulogin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user', 'uid', 'network', 'identity', 'email'], 'required'],
            [['user'], 'integer'],
            [['uid', 'identity'], 'string', 'max' => 255],
            [['network'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user' => 'User',
            'uid' => 'Openid',
            'network' => 'Network',
            'identity' => 'Identity',
            'id' => 'ID',
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
