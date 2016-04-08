<?php
namespace frontend\controllers;

use common\models\Photo;
use common\models\Ulogin;
use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\HttpException;
use yii\web\UploadedFile;

/**
 * Site controller
 */
class SiteController extends Controller
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only' => ['logout', 'signup'],
				'rules' => [
					[
						'actions' => ['signup'],
						'allow' => true,
						'roles' => ['?'],
					],
					[
						'actions' => ['logout'],
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'logout' => ['post'],
				],
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha' => [
				'class' => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
			],
		];
	}

	/**
	 * Displays homepage.
	 *
	 * @return mixed
	 */
	public function actionIndex()
	{
		return $this->render('index');
	}

	/**
	 * Logs in a user.
	 *
	 * @return mixed
	 */
	public function actionLogin()
	{
		if (!\Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			return $this->goBack();
		} else {
			return $this->render('login', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Logs out the current user.
	 *
	 * @return mixed
	 */
	public function actionLogout()
	{
		Yii::$app->user->logout();

		return $this->goHome();
	}

	/**
	 * Displays contact page.
	 *
	 * @return mixed
	 */
	public function actionContact()
	{
		$model = new ContactForm();
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
				Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
			} else {
				Yii::$app->session->setFlash('error', 'There was an error sending email.');
			}

			return $this->refresh();
		} else {
			return $this->render('contact', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Displays about page.
	 *
	 * @return mixed
	 */
	public function actionAbout()
	{
		return $this->render('about');
	}

	public function actionRequestPasswordReset()
	{
		$model = new PasswordResetRequestForm();
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			if ($model->sendEmail()) {
				Yii::$app->getSession()->setFlash('success', 'На указанный адрес высланы дальнейшие инструкции.');

				return $this->goHome();
			} else {
				Yii::$app->getSession()->setFlash('error', 'К сожалению мы не можем восстановить пароль для указанного адреса.');
			}
		}

		return $this->render('requestPasswordResetToken', [
			'model' => $model,
		]);
	}

	public function actionResetPassword($token)
	{
		try {
			$model = new ResetPasswordForm($token);
		} catch (InvalidParamException $e) {
			throw new BadRequestHttpException($e->getMessage());
		}

		if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
			Yii::$app->getSession()->setFlash('success', 'Новый пароль сохранен.');

			return $this->goHome();
		}

		return $this->render('resetPassword', [
			'model' => $model,
		]);
	}

	public function actionUlogin(){
		$s = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
		$user = json_decode($s, true);
		//$user['network'] - соц. сеть, через которую авторизовался пользователь
		//$user['identity'] - уникальная строка определяющая конкретного пользователя соц. сети
		//$user['first_name'] - имя пользователя
		//$user['last_name'] - фамилия пользователя
		//$user['photo'] - Photo
		if(!$user['uid']){throw new HttpException(500,'Не найден UID');}
		$query = Ulogin::find();
		$query->orWhere(['identity'=>$user['identity']]);
		$query->orWhere(['email'=>$user['email']]);
		//return $query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql;
		$openid = $query->one();
		//if(isset($openid)){ $openid = Ulogin::findOne(['identity'=>$user['identyty']]); }
		if(isset($openid)){
			if(Yii::$app->getUser()->login($openid->user0, Yii::$app->params['remeberMe.Time'])){
				if( $openid->network != $user['network'] ) {

					$oi = new Ulogin();
					$oi->user = Yii::$app->user->id;
					$oi->uid = $user['uid'];
					$oi->network = $user['network'];
					$oi->identity = $user['identity'];
					$oi->email = $user['email'];
					$oi->save();
				}elseif($openid->email != $user['email']){
					$openid->email = $user['email'];
					$openid->save(true,['email']);
				}
				Yii::$app->user->identity->photo = preg_match('!ulogin.ru!', $user['photo'])?'':$user['photo'];
				Yii::$app->user->identity->save();

				$redirect = Yii::$app->getUser()->getReturnUrl();
			}else{
				throw new ForbiddenHttpException('Доступ запрещен');
			}
		}else{
			$session = new Session();
			$session->open();
			$session['openid'] = $user;  // set session variable 'name3'
			$redirect = '/site/ulogin-signup';
		}
		return Json::encode(['redirect'=>$redirect]);
	}

	public function actionAddPic()
	{
		$model = new Photo();
		if($model->load(Yii::$app->request->post())){
			$model->user = Yii::$app->user->id;
			$model->imageFile = UploadedFile::getInstance($model, 'imageFile');
			if($model->imageFile){
				$model->type = $model->imageFile->extension;
				if ($model->upload()) {
					if(!$model->save(false))
						throw new HttpException(500,var_export($model->errors,1));
					$this->redirect('/');
				}else{
					throw new HttpException(501,var_export($model->errors,1));
				}
			}elseif($model->imageUrl){
				$model->type =  pathinfo($model->imageUrl, PATHINFO_EXTENSION);
				$model->id = time();
				if(!$model->save(false))
					throw new HttpException(500,var_export($model->errors,1));
				copy(Yii::$app->request->post('Photo')['imageUrl'], 'uploads/' . $model->id . '.' . $model->type );
				$this->redirect('/');
			}

		}
		return $this->render('add-photo',['model'=>$model]);
	}

	public function actionPicture($id)
	{
		$model = Photo::findOne($id);
		return $this->render('photo',['model'=>$model]);
	}

	public function actionUloginSignup()
	{
		$session = new Session();
		$session->open();
		if( isset($session['openid'])) {
			$model = new SignupForm();
			$model->email = $model->username = $session['openid']['email'];
			$model->password = md5(time().uniqid());
			$user = $model->signup();
			if (Yii::$app->getUser()->login($user,Yii::$app->params['remeberMe.Time'])) {
				$oi = new Ulogin();
				$oi->user = Yii::$app->user->id;
				$oi->uid = $session['openid']['uid'];
				$oi->network = $session['openid']['network'];
				$oi->identity = $session['openid']['identity'];
				$oi->email = $session['openid']['email'];
				if (!$oi->save()) {
				}
				Yii::$app->user->identity->last_name = $session['openid']['last_name'];
				Yii::$app->user->identity->first_name = $session['openid']['first_name'];
				Yii::$app->user->identity->photo = preg_match('!ulogin.ru!', $session['openid']['photo'])?'':$session['openid']['photo'];
				Yii::$app->user->identity->save();
			}
		}
		return $this->goHome();
	}

	public function actionSignup()
	{
		$model = new SignupForm();
		if ($model->load(Yii::$app->request->post())) {
			if ($user = $model->signup()) {
				if (Yii::$app->getUser()->login($user, Yii::$app->params['remeberMe.Time'])) {
					return $this->goHome();
				}
			}
		}
		return $this->render('signup', [
			'model' => $model,
		]);
	}

	
}
