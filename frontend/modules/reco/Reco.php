<?php

namespace frontend\modules\reco;
use Yii;
use yii\web\HttpException;

/**
 * crea module definition class
 */
class Reco extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'frontend\modules\reco\controllers';
	public $name = '4 картинки';
	public $maxWidth = 600;
	public $maxHeight = 400;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

	public function mailToUser($data)
	{
		$mailer = Yii::$app->mailer->compose()
			->setFrom(['abrikoscron@gmail.ru' => \Yii::$app->name . '. Робот'])
			->setTo($data->email)
			->setSubject($data->subject)
			->setTextBody($data->body ."\n\n" . $this->name . " \n" .Yii::$app->name . " \n" . $_SERVER['SERVER_NAME']);
		if(!$mailer->send()){
			throw new HttpException(500,'E-mail не отправлен. Обратитесь в тех-поддержку разделе "Помошь"');
		}
	}

	public function timeFormat($time){
		$ret['days'] = round($time / 24/ 3600);
		$daySec = $time % (24 * 3600);
		$ret['hours'] = round($daySec / 3600);
		$hourSec = $daySec % 3600;
		$ret['minutes'] = round($hourSec / 60);
		$ret['seconds'] = $hourSec % 60;
		return $ret;
	}

	public function timeString($time)
	{
		$arr = $this->timeFormat($time);
		$ret = '';
		if($arr['days']>0) $ret .= 'Дни:'. $arr['days'] .'. ';
		if($arr['hours']>0) $ret .= 'Часы:'. $arr['hours'] .'. ';
		if($arr['minutes']>0) $ret .= 'Минуты:'. $arr['minutes'] .'. ';
		if($arr['seconds']>0) $ret .= 'Секунды:'. $arr['seconds'] .'. ';
		return $ret;
	}

}
