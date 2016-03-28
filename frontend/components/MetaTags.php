<?php
/**
 * Created by PhpStorm.
 * User: abrikos
 * Date: 29.02.16
 * Time: 12:45
 */

namespace app\components;


use yii\base\Component;
use yii\helpers\Url;

class MetaTags extends Component
{
	public function init()
	{

		\Yii::$app->view->registerMetaTag(['name'=>'Description', 'content'=>'Абрикосизм'], 'Description');
		\Yii::$app->view->registerMetaTag(['name'=>'Keywords', 'content'=>'Russia, Россия, русский язык, абрикосизм, лотерея, игры, сапер, быки, коровы'], 'Keywords');



		\Yii::$app->view->registerMetaTag(['property'=>'og:type', 'content'=>'website']);
		\Yii::$app->view->registerMetaTag(['property'=>'og:url', 'content'=>\Yii::$app->request->absoluteUrl]);
		\Yii::$app->view->registerMetaTag(['property'=>'og:title', 'content'=>'Abrikos', 'id'=>'og-title'], 'og-title');
		\Yii::$app->view->registerMetaTag(['property'=>'og:description', 'content'=>'Персональная страница'], 'og-desc');

		\Yii::$app->view->registerMetaTag(['property' => 'og:image', 'content' => 'http://'.$_SERVER['SERVER_NAME'].'/images/logo.png'],'og-image');
		\Yii::$app->view->registerMetaTag(['property' => 'og:image:secure_url', 'content' => 'http://'.$_SERVER['SERVER_NAME'].'/images/logo.png'],'og-imagesec');
		\Yii::$app->view->registerMetaTag(['property' => 'og:image:width', 'content' => 200],'og-image-width');
		\Yii::$app->view->registerMetaTag(['property' => 'og:image:height', 'content' => 200],'og-image-height');
		\Yii::$app->view->registerLinkTag(['rel' => 'image_src', 'href' => 'http://'.$_SERVER['SERVER_NAME'].'/images/logo.png'],'og-image-rel');

}

}