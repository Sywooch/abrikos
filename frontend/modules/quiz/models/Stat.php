<?php

namespace frontend\modules\quiz\models;

use Yii;

/**
 * This is the model class for table "quiz_stat".
 *
 * @property integer $quiz
 * @property integer $share
 * @property integer $tour
 * @property integer $comment
 * @property string $date
 *
 * @property Quiz $quiz0
 */
class Stat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'quiz_stat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['quiz', 'share', 'tour', 'comment'], 'required'],
            [['quiz', 'share', 'tour', 'comment'], 'integer'],
            [['date'], 'safe'],
            [['quiz'], 'exist', 'skipOnError' => true, 'targetClass' => Quiz::className(), 'targetAttribute' => ['quiz' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'quiz' => 'Quiz',
            'share' => 'Share',
            'like' => 'Like',
            'comment' => 'Comment',
            'date' => 'Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuiz0()
    {
        return $this->hasOne(Quiz::className(), ['id' => 'quiz']);
    }
}
