<?php

namespace frontend\modules\quiz;
use Facebook\Facebook;
use frontend\modules\quiz\models\Stat;
use Yii;
use yii\helpers\VarDumper;

/**
 * quiz module definition class
 */
class Quiz extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'frontend\modules\quiz\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

	private function fbookStat()
	{
		$quiz = \frontend\modules\quiz\models\Quiz::find()->all();
		$ids = [];
		foreach ($quiz as $q){
			$fb = new Facebook([
				'app_id' => Yii::$app->params['facebook']['app_id'],
				'app_secret' => Yii::$app->params['facebook']['app_secret'],
				'default_graph_version' => 'v2.6',
				'default_access_token' => '935323086485431|wpSTdFTOPTrRqVSj_I0x9ZsoD4E', // optional
			]);
			$getid = $fb->get($q->link);
			$og_object = $getid->getDecodedBody();
			$stat = new Stat();
			$stat->tour = date('Ym');
			//print_r($og_object);
			if(isset($og_object['share'])) {
				$stat->share = $og_object['share']['share_count'];
				$stat->comment = $og_object['share']['comment_count'];
				$stat->quiz = $q->id;
				if($stat->share + $stat->comment)
					if (!$stat->save()) {
						VarDumper::dump($stat->errors);
					}
			}
			$ids[] = $q->link;
		}
		//$url = 'https://graph.facebook.com/?ids=' . implode(',',$ids);
		//$fdata = \yii\helpers\Json::decode(file_get_contents($url));
		//print_r($fdata);

	}

	static public function daily()
	{
		//self::fbookStat();
	}

	static public function hourly()
	{
		self::fbookStat();
		$path = Yii::getAlias('@frontend/web/uploads/quiz/quest/');
		$files = scandir($path);
		for($i=2;$i<count($files);$i++){
			$id = str_replace('.jpeg','',$files[$i]);
			$quest = \frontend\modules\quiz\models\Quest::findOne($id);
			if(!$quest){
				unlink($path.$files[$i]);
			}
		}
		$path = Yii::getAlias('@frontend/web/uploads/quiz/answer/');
		$files = scandir($path);
		for($i=2;$i<count($files);$i++){
			$id = str_replace('.jpeg','',$files[$i]);
			$model = \frontend\modules\quiz\models\Answer::findOne($id);
			if(!$model){
				unlink($path.$files[$i]);
			}
		}

	}
}
