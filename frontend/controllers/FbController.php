<?php
/**
 * Created by PhpStorm.
 * User: abrikos
 * Date: 04.05.16
 * Time: 11:48
 */

namespace frontend\controllers;


use common\models\Fblogin;
use common\models\User;
use Facebook\Facebook;
use Facebook\FacebookApp;
use Facebook\SignedRequest;
use Yii;
use yii\base\Component;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\HttpException;

class FbController extends Controller
{

	public function beforeAction($action)
	{
		if ($action->id == 'canvas') {
			$this->enableCsrfValidation = false;
		}
		return parent::beforeAction($action);
	}

	static public function fbinit()
	{
		return new Facebook([
			'app_id' => Yii::$app->params['facebook']['app_id'],
			'app_secret' => Yii::$app->params['facebook']['app_secret'],
			'default_graph_version' => 'v2.6',
		]);
	}

	public function actionLogin()
	{
		return $this->render('fb-login',['fb'=>self::fbinit()]);
	}

	public function actionCallback()
	{
		$fb = self::fbinit();
		$helper = $fb->getRedirectLoginHelper();

		try {
			$accessToken = $helper->getAccessToken();
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			// When Graph returns an error
			echo 'Graph returned an error: ' . $e->getMessage();
			exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			// When validation fails or other local issues
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}

		if (! isset($accessToken)) {
			if ($helper->getError()) {
				header('HTTP/1.0 401 Unauthorized');
				echo "Error: " . $helper->getError() . "\n";
				echo "Error Code: " . $helper->getErrorCode() . "\n";
				echo "Error Reason: " . $helper->getErrorReason() . "\n";
				echo "Error Description: " . $helper->getErrorDescription() . "\n";
			} else {
				header('HTTP/1.0 400 Bad Request');
				echo 'Bad request';
			}
			exit;
		}


// The OAuth 2.0 client handler helps us manage access tokens
		$oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
		$tokenMetadata = $oAuth2Client->debugToken($accessToken);

// Validation (these will throw FacebookSDKException's when they fail)
		$tokenMetadata->validateAppId(Yii::$app->params['facebook']['app_id']); // Replace {app-id} with your app id
// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
		$tokenMetadata->validateExpiration();

		if (! $accessToken->isLongLived()) {
			// Exchanges a short-lived access token for a long-lived one
			try {
				$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
			} catch (Facebook\Exceptions\FacebookSDKException $e) {
				echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
				exit;
			}

			echo '<h3>Long-lived</h3>';
			var_dump($accessToken->getValue());
		}

		$_SESSION['fb_access_token'] = (string) $accessToken;

// User is logged in with a long-lived access token.
// You can redirect them to a members-only page.
//header('Location: https://example.com/members.php');



// Since all the requests will be sent on behalf of the same user,
// we'll set the default fallback access token here.
		$fb->setDefaultAccessToken($accessToken->getValue());

		/**
		 * Generate some requests and then send them in a batch request.
		 */

// Get the name of the logged in user
		$requestUserName = $fb->request('GET', '/me?fields=id,email,first_name,last_name,picture');

		$batch = [
			'user-profile' => $requestUserName,
			//'user-likes' => $requestUserLikes,
			//'user-events' => $requestUserEvents,
			//'post-to-feed' => $requestPostToFeed,
			//'user-photos' => $requestUserPhotos,
		];


		try {
			$responses = $fb->sendBatchRequest($batch);
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			// When Graph returns an error
			echo 'Graph returned an error: ' . $e->getMessage();
			exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			// When validation fails or other local issues
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}

		foreach ($responses as $key => $response) {
			if ($response->isError()) {
				$e = $response->getThrownException();
				echo '<p>Error! Facebook SDK Said: ' . $e->getMessage() . "\n\n";
				echo '<p>Graph Said: ' . "\n\n";
				var_dump($e->getResponse());
			} else {
				 $userData = $response->getDecodedBody();
				//print_r($userData);exit;
				$user = User::findOne(['email'=>$userData['email']]);
				if(!$user){
					$user = new User();
					$user->first_name = $userData['first_name'];
					$user->last_name = $userData['last_name'];
					$user->username = $user->email = $userData['email'];
					$user->created_at = time();
					$user->photo = isset($userData['picture']['data']['url']) ? $userData['picture']['data']['url'] : '';
					$user->setPassword(uniqid());
					$user->generateAuthKey();
					if (!$user->save()) throw new HttpException(500,Json::encode($user->errors));
					$auth = Yii::$app->authManager;
					$authorRole = $auth->getRole('user');
					$auth->assign($authorRole, $user->getId());
				}else{
					$user->first_name = $userData['first_name'];
					$user->last_name = $userData['last_name'];
					$user->updated_at = time();
					$user->photo = isset($userData['picture']['data']['url']) ? $userData['picture']['data']['url'] : '';
					if (!$user->save()) throw new HttpException(500,Json::encode($user->errors));
				}
				Yii::$app->getUser()->login($user, Yii::$app->params['remeberMe.Time']);
				return $this->redirect(Yii::$app->session['returnUrl'] ? Yii::$app->session['returnUrl']:'/user/cabinet');
			}
		}
	}

	public function actionIndex()
	{
		return $this->render('index');
	}

	public function actionTimelinePost()
	{
		$fb = self::fbinit();
		$helper = $fb->getRedirectLoginHelper();

		try {
			$accessToken = $helper->getAccessToken();
		} catch (Facebook\Exceptions\FacebookResponseException $e) {
			// When Graph returns an error
			echo 'Graph returned an error: ' . $e->getMessage();
			exit;
		} catch (Facebook\Exceptions\FacebookSDKException $e) {
			// When validation fails or other local issues
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}

		if (!isset($accessToken)) {
			if ($helper->getError()) {
				header('HTTP/1.0 401 Unauthorized');
				echo "Error: " . $helper->getError() . "\n";
				echo "Error Code: " . $helper->getErrorCode() . "\n";
				echo "Error Reason: " . $helper->getErrorReason() . "\n";
				echo "Error Description: " . $helper->getErrorDescription() . "\n";
			} else {
				header('HTTP/1.0 400 Bad Request');
				echo 'Bad request';
			}
			exit;
		}


// The OAuth 2.0 client handler helps us manage access tokens
		$oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
		$tokenMetadata = $oAuth2Client->debugToken($accessToken);

// Validation (these will throw FacebookSDKException's when they fail)
		$tokenMetadata->validateAppId(Yii::$app->params['facebook']['app_id']); // Replace {app-id} with your app id
// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
		$tokenMetadata->validateExpiration();

		if (!$accessToken->isLongLived()) {
			// Exchanges a short-lived access token for a long-lived one
			try {
				$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
			} catch (Facebook\Exceptions\FacebookSDKException $e) {
				echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
				exit;
			}

			echo '<h3>Long-lived</h3>';
			var_dump($accessToken->getValue());
		}

		$_SESSION['fb_access_token'] = (string)$accessToken;

// User is logged in with a long-lived access token.
// You can redirect them to a members-only page.
//header('Location: https://example.com/members.php');


// Since all the requests will be sent on behalf of the same user,
// we'll set the default fallback access token here.
		$fb->setDefaultAccessToken($accessToken->getValue());

		/**
		 * Generate some requests and then send them in a batch request.
		 */
// Post a status update with reference to the user's name
		$message = 'My name is {result=user-profile:$.name}.' . "\n\n";
		//$message .= 'I like this page: {result=user-likes:$.data.0.name}.' . "\n\n";
		//$message .= 'My next 2 events are {result=user-events:$.data.*.name}.';
		$statusUpdate = ['message' => $message];
		$requestPostToFeed = $fb->request('POST', '/me/feed', $statusUpdate);

// Get the name of the logged in user
		$requestUserName = $fb->request('GET', '/me?fields=id,email,first_name,last_name,picture');
// Get user photos
		$requestUserPhotos = $fb->request('GET', '/me/photos?fields=id,source,name&amp;limit=2');
// Get user likes
		$requestUserLikes = $fb->request('GET', '/me/likes?fields=id,name&amp;limit=1');

// Get user events
		$requestUserEvents = $fb->request('GET', '/me/events?fields=id,name&amp;limit=2');

		$batch = [
			'user-profile' => $requestUserName,
			'user-likes' => $requestUserLikes,
			'user-events' => $requestUserEvents,
			//'post-to-feed' => $requestPostToFeed,
			'user-photos' => $requestUserPhotos,
		];


		try {
			$responses = $fb->sendBatchRequest($batch);
		} catch (Facebook\Exceptions\FacebookResponseException $e) {
			// When Graph returns an error
			echo 'Graph returned an error: ' . $e->getMessage();
			exit;
		} catch (Facebook\Exceptions\FacebookSDKException $e) {
			// When validation fails or other local issues
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}

		foreach ($responses as $key => $response) {
			if ($response->isError()) {
				$e = $response->getThrownException();
				echo '<p>Error! Facebook SDK Said: ' . $e->getMessage() . "\n\n";
				echo '<p>Graph Said: ' . "\n\n";
				var_dump($e->getResponse());
			} else {
				$userData = $response->getDecodedBody();
				print_r($userData);
			}
		}
	}


	public function actionCanvas()
	{
		$this->layout = 'facebook';
		if(!Yii::$app->request->post('signed_request')) throw new HttpException(500,'Доступ только из приложения facebook');
		$fb = new FacebookApp(Yii::$app->params['facebook']['app_id'],Yii::$app->params['facebook']['app_secret']);
		$signedRequest = new SignedRequest($fb, Yii::$app->request->post('signed_request'));
		return $this->render('canvas',['fb'=>self::fbinit()]);
	}
}