<?php
\Yii::$app->view->registerMetaTag(['property'=>'og:title', 'content'=>'Abrikos', 'id'=>'og-title'], 'og-title');
\Yii::$app->view->registerMetaTag(['property' => 'og:image', 'content' => 'http://'.$_SERVER['SERVER_NAME'].$model->file],'og-image');
\Yii::$app->view->registerMetaTag(['property' => 'og:image:secure_url', 'content' => 'http://'.$_SERVER['SERVER_NAME'].$model->file],'og-imagesec');
\Yii::$app->view->registerLinkTag(['rel' => 'image_src', 'href' => 'http://'.$_SERVER['SERVER_NAME'].$model->file],'og-image-rel');
\Yii::$app->view->registerMetaTag(['property'=>'og:description', 'content'=>\yii\bootstrap\Html::encode($model->text)], 'og-desc');
echo \yii\bootstrap\Html::img($model->file);
echo '<br/>';
echo $model->text;
