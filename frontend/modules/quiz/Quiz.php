<?php

namespace frontend\modules\quiz;
use Yii;

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

	static public function cron()
	{
		$path = Yii::getAlias('@frontend/web/uploads/quiz/quest/');
		$files = scandir($path);
		for($i=2;$i<count($files);$i++){
			$id = str_replace('.jpeg','',$files[$i]);
			print "$id\n";
			$quest = \frontend\modules\quiz\models\Quest::findOne($id);
			if(!$quest){
				unlink($path.$files[$i]);
			}
		}
		print_r($files);
	}
}
