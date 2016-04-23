<?php

namespace app\modules\poll\controllers;

use app\modules\poll\models\Answer;
use app\modules\poll\models\Question;
use app\modules\poll\models\Vote;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\BaseVarDumper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `Poll` module
 */
class DefaultController extends Controller
{
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => ['index','view', 'script' , 'draw', 'result'],
						'allow' => true,
					],
					[
						//'actions' => ['cabinet', ],
						'allow' => true,
						'roles' => ['user'],
					],
				],
			],

			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'answer-delete' => ['post'],
					'delete' => ['post'],
					'create' => ['post'],
					'sort' => ['post'],
				],
			],
		];
	}

	public function actionList()
	{
		$dataProvider = new ActiveDataProvider([
			'query'=> Question::find()->where(['user'=>Yii::$app->user->id])->orderBy('date desc')
		]);
		return $this->render('list',['dataProvider'=>$dataProvider]);
	}

	/**
	 * Displays a single Question model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id,$type='')
	{
		$question = $this->findQuestion($id);
		\Yii::$app->view->registerMetaTag(['property'=>'og:title', 'content'=>$question->text , 'id'=>'og-title'], 'og-title');
		\Yii::$app->view->registerMetaTag(['property'=>'og:description', 'content'=>Yii::$app->name . '::Опросы'], 'og-desc');

		if($type=='plain'){
			return $this->renderPartial('view', [
				'model' => $question,
			]);
		}elseif($type=='json') {
			return Json::encode($question);
		}else{
			return $this->render('view', [
				'model' => $question,
			]);
		}
	}

	public function actionDraw($id, $callback){
		$arr = [];
		$arr['name'] = "response";
		$model = Question::findOne($id);
		$arr['html'] = $this->renderPartial('view',['model'=>$model]);

		return $callback."(".  Json::encode($arr) .");";
	}

	/**
	 * Creates a new Question model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Question();
		$model->user = Yii::$app->user->id*1;
		$model->text = 'Новый опрос';
		$model->save(true,['user','text']);
		return $this->redirect(['update', 'id' => $model->id]);
	}

	/**
	 * Updates an existing Question model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 * @throws ForbiddenHttpException if the model not belongs to user
	 */
	public function actionUpdate($id)
	{
		$question = $this->findQuestion($id);
		$this->checkAccess($question->user);
		if ($question->load(Yii::$app->request->post()) ) {
			if($question->save()){
				if(!Yii::$app->request->isAjax) {
					return $this->redirect(['update', 'id' => $question->id]);
				}
			}else{
				Yii::$app->getSession()->setFlash('error', BaseVarDumper::export($question->errors));
			}

		} else {
			return $this->render('update', [
				'model' => $question,
			]);
		}
	}

	public function actionResult($id, $callback){
		$model = Question::find()->where(['id'=>$id])->one();
		if(count(Yii::$app->request->get('answer')) && $model->active) {
			foreach (Yii::$app->request->get('answer') as $a) {
				$vote = new Vote();
				$vote->answer = $a;
				$vote->ip = $_SERVER['REMOTE_ADDR'];
				$vote->save(true, ['answer', 'ip']);
			}
		}
		$arr['html'] = $this->renderPartial('result',['model'=>$model]);
		return $callback."(".  Json::encode($arr) .");";
	}

	/**
	 * Deletes an existing Question model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$question = $this->findQuestion($id);
		$this->checkAccess($question->user);
		$question->delete();
		return $this->redirect(['/poll/list']);
	}

	public function actionSort($id)
	{
		$question = $this->findQuestion($id);
		$this->checkAccess($question->user);
		foreach (Yii::$app->request->post('sort') as $order){
			$a = Answer::findOne($order['id']);
			$a->order = $order['order'];
			if(!$a->save(true,['order'])){
				print_r($a->errors);
			}
			print "$order[id] - $order[order]\n";
		}
	}

	public function actionScript($id){
		return $this->renderPartial('script',['id'=>$id]);
	}

	public function actionTest()
	{
		print '<div id="poll-19"></div><script src="//code.jquery.com/jquery-1.10.2.js"></script><script src="http://hub.abrikos.su/poll/script/19" type="application/javascript"></script>';
	}

	public function actionAnswerList($id){
		$answers = Answer::find()->where(['question'=>$id])->orderBy('order')->all();
		return Json::encode($answers);
	}

	public function actionAnswerAdd()
	{
		$question = $this->findQuestion(Yii::$app->request->post('question')*1);
		$this->checkAccess($question->user);
		if(! isset($question)){ throw new ForbiddenHttpException('Доступ к вопросу отсутствует'); }
		$maxorder = Answer::find()->where(['question'=>$question->id])->orderBy('order desc')->one();
		$model = new Answer();
		$model->order = isset($maxorder)? $maxorder->order + 1 : 0;
		$model->question = $question->id;
		$model->text = Yii::$app->request->post('text');
		if($model->save(true,['text','question', 'order'])){
			$question->count++;
			$question->save(true,['count']);
		}
		return Json::encode($model);
	}

	/**
	 * Deletes an existing Answer model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 * @throws ForbiddenHttpException if the question not belongs to user
	 */
	public function actionAnswerDelete($id)
	{
		$model = $this->findAnswer($id);
		$this->checkAccess($model->question0->user);
		$model->question0->count--;
		$model->question0->save(true,['count']);
		$model->delete();
	}

	/**
	 * Updates an existing Answer model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 * @throws ForbiddenHttpException if the question not belongs to user
	 */
	public function actionAnswerUpdate($id)
	{
		$question = $this->findQuestion(Yii::$app->request->post('question')*1);
		$this->checkAccess($question->user);
		if(! isset($question)){ throw new ForbiddenHttpException('Доступ к опросу отсутствует'); }
		$model = Answer::findOne(['id'=>$id, 'question'=>$question->id]);
		if(! isset($model)){ throw new NotFoundHttpException('Запрошенный ответ отсутствует'); }
		$model->text = Yii::$app->request->post('text');
		$model->save(true,['text']);
		return $model->id;
	}

	/**
	 * Finds the Question model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Question the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findQuestion($id)
	{
		if (($model = Question::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('Опрос не существует.');
		}
	}

	/**
	 * Finds the Question model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Question the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findAnswer($id)
	{
		if (($model = Answer::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('Опрос не существует.');
		}
	}

	/**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

	private function checkAccess($user)
	{
		if($user !== Yii::$app->user->id) throw new ForbiddenHttpException('Доступ запрещен');
	}
}
