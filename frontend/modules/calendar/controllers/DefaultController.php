<?php

namespace app\modules\calendar\controllers;

use app\modules\calendar\models\Calendar;
use Yii;
use yii\web\Controller;

/**
 * Default controller for the `calendar` module
 */
class DefaultController extends Controller
{
	public function init()
	{
		if (isset(Yii::$app->request->cookies['language'])) {
			Yii::$app->language = Yii::$app->request->cookies['language'];
		}
		return parent::init();
	}

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex($retro='')
    {
        $model = new Calendar($retro);
        return $this->render('index', ['calendar'=>$model]);
    }

	public function actionGetRetro($year,$day)
	{
		$calendar = new Calendar();
		return  Yii::t('calendar', 'UNIX epoch date')
			. ': ' . $calendar->getModern($year,$day)
			. "\n" . Yii::t('calendar', 'Retro date')
			. ': ' . $calendar->getRetro($year,$day);
	}

	public function actionLanguage($l)
	{
		$cookies = Yii::$app->response->cookies;
		$cookies->remove('language');
		unset($cookies['language']);
		$cookies->add(new \yii\web\Cookie([
			'name' => 'language',
			'value' => $l
		]));
		$this->redirect(Yii::$app->request->referrer);
	}
}
