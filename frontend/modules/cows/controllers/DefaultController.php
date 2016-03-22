<?php

namespace app\modules\cows\controllers;

use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
	    return $this->render('cows');
    }


	public function actionStart()
	{
		$numbers =  [0,1,2,3,4,5,6,7,8,9];
		for($i=0;$i<4;$i++){
			$key = array_rand($numbers);
			$number[] = $numbers[$key];
			unset($numbers[$key]);
		}
		$session = \Yii::$app->session;
		if(\Yii::$app->user->isGuest){
			$mysess['playerName'] = \Yii::$app->request->post('playerName');
		}else{
			$user = \common\models\User::findOne(\Yii::$app->user->id);
			$mysess['playerName'] = $user->username;
		}
		$mysess['number'] = $number;
		$mysess['start'] = time();
		$session->set('cows-session', $mysess);
		$session->set('cows-counter', 0);
		//return \yii\helpers\Json::encode($number);
	}

	public function actionTurn()
	{
		$test = \Yii::$app->request->post('test');
		$session = \Yii::$app->session;
		$mysess = $session['cows-session'];
		$number = $mysess['number'];
		$arr = str_split($test);
		if(count($arr)!=4){
			throw new \yii\web\ServerErrorHttpException('Число должно состоять из 4 цифр');
		}elseif( count(array_unique($arr))!=4 ){
			throw new HttpException(500,'Число не должно содержать повторяющикся цифры');
		}elseif(!preg_match('![\d]{4}!', $test)){
			throw new HttpException(500,'Число должно содержать только цифры');
		}
		$counter = $session['cows-counter']+1;
		$session->set('cows-counter', $counter);
		$bulls = 0; $cows = 0;
		for($i=0;$i<4;$i++){
			if($arr[$i] == $number[$i]){ $bulls++;}else { $arr1[] = $arr[$i];}
		}
		$diff = array_diff($arr, $number);
		$cows = 4-count($diff)-$bulls;
		if($bulls==4 && $mysess['start']>0){
			$cows = new \common\models\Cows;
			$cows->time = time() - $mysess['start'];
			$cows->player = $mysess['playerName'];
			$cows->user = \Yii::$app->user->id*1;
			$cows->steps = $counter;
			$cows->rate = $counter /$cows->time;
			$cows->save();
		}
		return \yii\helpers\Json::encode(['bulls'=>$bulls, 'cows'=>$cows, 'test'=>$test]);
	}

	public function actionTable($from=0,$to=0, $registred = 0) {
		$query = \common\models\Cows::find();
		$query->where('steps > 3 ');
		if($from){ $query->andWhere(' date >"' . $from .'"'); }
		if($to){ $query->andWhere(' date < "' . $to .'"'); }
		if($registred){ $query->andWhere(' user > 0 '); }
		$cows = $query->orderBy('steps, time')->limit(50)->all();
		return \yii\helpers\Json::encode($cows);
	}

	public function actionResult($id) {
		$model = \common\models\Cows::findOne($id);
		return $this->render('cows-result',['model'=>$model]);
	}

}
