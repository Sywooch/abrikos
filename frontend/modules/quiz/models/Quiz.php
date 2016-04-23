<?php

namespace frontend\modules\quiz\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "{{%quiz_quiz}}".
 *
 * @property integer $id
 * @property integer $user
 * @property string $date
 * @property string $name
 * @property string $description
 * @property integer $enabled
 *
 * @property QuizQuest[] $quizQuests
 * @property User $user0
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
            [['user', 'name', 'description'], 'required'],
            [['user', 'enabled'], 'integer'],
            [['date'], 'safe'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 100],
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
            'enabled' => 'Активный',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuests()
    {
        return $this->hasMany(Quest::className(), ['quiz' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser0()
    {
        return $this->hasOne(User::className(), ['id' => 'user']);
    }
}
