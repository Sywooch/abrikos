<?php
/**
 * Created by PhpStorm.
 * User: abrikos
 * Date: 06.05.16
 * Time: 12:38
 */

namespace frontend\modules\reco\controllers;


use frontend\modules\reco\models\Reco;
use Yii;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class RestoreController extends Controller
{
	public function init()
	{
		parent::init();
	}

	public function actionIndex($id='')
	{
		return $this->render('index',['id'=>$id, 'object_name'=> $this->module->name, 'module_name'=> $this->module->id]);
	}
	

	public function actionStart()
	{
		$code = Yii::$app->request->post('code');
		$model = Quest::findOne($code);
		if(!$model){
			Yii::$app->session->setFlash('error','Сервиса "'. $this->module->name.'" с таким ID на найдено');
			return $this->redirect('index');
		}
		if(!$model->email){
			Yii::$app->session->setFlash('error','При создании  сервиса "'. $this->module->name.'" #'.$code.' не был указан e-mail для восстановления доступа. Попробуйте обратиться к админитрации.');
			return $this->redirect('/site/contact');
		}
		if ($model->user > 1 &&  Yii::$app->user->isGuest) {
			Yii::$app->session->setFlash('warning', 'Сервис "'. $this->module->name.'" #'.$code.' принадлежит зарегистрированному пользователю. Пожалуйста выполните вход и найдите свою викторину в <a href="/"'. $this->module->id.'"/list">списке</a>');
			return $this->redirect('/site/login');
		}
		if ($model->user !=  Yii::$app->user->id && !Yii::$app->user->isGuest) {
			Yii::$app->session->setFlash('error','Сервис "'. $this->module->name.'" #'.$code.' принадлежит другому пользователю');
			return $this->render('index',['id'=>'', 'object_name'=> $this->module->name , 'module_name'=> $this->module->id]);
		}
		$response = new \stdClass();
		$response->email = $model->email;
		//$link = 'https://' . $_SERVER['SERVER_NAME'] . '/'. $this->module->id.'/restore/finish?uid=' . $model->session;
		$link = Yii::$app->urlManager->createAbsoluteUrl([$this->module->id . '/restore/finish', 'uid' => $model->session]);
		$response->subject = 'Запрос восстановлени доступа к сервису "' .  $this->module->name . '" #' .$code;
		$response->body = 'Ваш адрес указан при создании сервиса "'. $this->module->name.'" #'.$code.'. Было инициировано восстановление доступа. Если нет уверенности, что это произошло по Вашему запросу, то можете не обращать внимание на это сообщение. Для продолжения восстановления пройдите по ссылке '.$link;
		$this->module->mailToUser($response);
		Yii::$app->session->setFlash('success','На почту, указанную при создании сервиса "'. $this->module->name.'" #'.$code.', отправлены инструкции ');
		return $this->render('result');
	}

	public function actionFinish($uid)
	{
		$model = Quest::findOne(['session'=>$uid]);
		if(!$model) throw new NotFoundHttpException('Сервис "'. $this->module->name.'"  #'.$uid.' не найден');
		$model->session = uniqid();
		$model->save(true,['session']);
		$response = new \stdClass();
		$response->email = $model->email;
		//$link = 'https://' . $_SERVER['SERVER_NAME'] . '/'. $this->module->id.'/update/'.$model->id.'?uid=' . $model->session;
		$link = Yii::$app->urlManager->createAbsoluteUrl([$this->module->id . '/update/','id'=>$model->id,  'uid' => $model->session]);
		$response->subject = 'Доступ к сервису "'. $this->module->name.'" #'.$model->id.' восстановлен. ';
		$response->body = 'Для продолжения перейдите по ссылке '.$link;
		$this->module->mailToUser($response);
		Yii::$app->session->setFlash('success','Инструкции по завершению восстановления сервиса "'. $this->module->name.'" #'.$model->id.'" отправлены на e-mail ');
		return $this->render('result');
	}


}