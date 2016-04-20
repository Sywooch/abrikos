<?php

namespace app\modules\seawar\controllers;

use app\modules\seawar\models\SeaTable;
use yii\helpers\Json;
use yii\web\Controller;

/**
 * Default controller for the `seawar` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

	public function actionTest()
	{
		$table =new SeaTable();
		$e =$table->randomFill();
		return Json::encode(['vert'=>$table->free[0], 'hor'=>$table->free[1], 'ships'=>$table->ships, 'e'=>$e]);
	}
}
