<?php
/**
 * Created by PhpStorm.
 * User: abrikos
 * Date: 30.04.15
 * Time: 17:12
 */

namespace console\controllers;

use common\models\Phone;
use common\models\Round;
use frontend\modules\quiz\Quiz;
use Yii;

class CronController extends \yii\console\Controller
{
	public function actionTest()
	{
		//print mail('abrikoz@gmail.com','tezzz','one more');

	}

	public function actionHourly()
	{
		Quiz::cron();
	}

	public function actionDaily()
	{
	}

	public function actionWeekly()
	{
		
	}

}