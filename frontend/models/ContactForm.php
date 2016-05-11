<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifycode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'subject', 'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
            ['verifycode', 'captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'verifycode' => 'Код подтверждения',
            'name'=>'Ваше имя',
            'email'=>'Ваш e-mail адрес',
            'subject'=>'Тема сообщения',
            'body'=>'Ваше сообщение',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @return boolean whether the email was sent
     */
    public function sendEmail()
    {
	    return Yii::$app->mailer->compose()
		    ->setFrom(['abrikoscron@gmail.com'=>$_SERVER['SERVER_NAME'] . ' Вопрос о помощи '])
		    ->setTo([Yii::$app->params['adminEmail']])
		    ->setSubject($this->subject)
		    ->setTextBody('Вопрос от ' . $this->name . ':  ' . $this->body . ' ' . $this->email)
		    ->send();
    }
}
