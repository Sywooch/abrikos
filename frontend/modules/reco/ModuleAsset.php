<?php
namespace frontend\modules\reco;

use Yii;
use yii\web\View;
use yii\web\AssetBundle;

class ModuleAsset extends AssetBundle
{
	public $sourcePath = '@app/modules/reco/assets';
	public $baseUrl = '@web';
	public $css = [];
	public $js = [];
	public $jsOptions = ['position' => View::POS_HEAD];

	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
	];

	public function init()
	{
		switch (Yii::$app->controller->action->id){
			case 'view':
			case 'quest':
				$this->css = ['css/view.css'];
				$this->js = ['js/tmpl.min.js','js/view.js'];
				break;
			case 'update':
				$this->css = ['css/update.css'];
				$this->js = ['js/tmpl.min.js','js/update.js',
					'js/drop-upload.js'
				];
				break;
			case 'index':
				$this->css = ['css/index.css'];
				break;
			case 'list':
				$this->css = ['css/list.css'];
				break;
		}
		
		
		parent::init();
	}
}