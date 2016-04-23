<?php
use yii\bootstrap\Html;

$this->title = 'Сервис создания опросов.';
$this->params['breadcrumbs'][] = $this->title;

?>
<h1><?= Html::encode($this->title) ?></h1>





<p>
Построил царь новый дворец. Решил выкрасить его. Какой цвет выбрать чтоб всем жителям пригож был? <br/>
Что бы узнать какой цвет любит население его страны послал он троих слуг распросить об этом.<br/>
Месяц носились слуги и принесли царю рассказы о тысячах разнообразных цветов.<br/>
Слушал царь их рассказаы, да только запутывался в цветах каких даже и не слыхивал ни разу.<br/>
	<br/>
Узнал о неспокойстве владыки один ученый муж и дал ему совет.<br/>
Посмотреть каких красок у придворных маляров достаточно для покраски замка.<br/>
Покрасить столько бочек сколько есть таких красок и выставить их на площади.<br/>
Повелеть всем жителям кинуть самую мелкую монетку в ту бочку какой цвет ему более люб.<br/>
Когда не останется больше того кто мог бы кинуть монетку  нужно посчитать сколько в каждой бочке собралось денег.<br/>
Какая бочка окажется самая богатая - тот цвет и более люб жителям. Замку и быть того цвета.<br/>
Сделав так как задумано выкрасил царь замок в зеленый цвет.<br/>
Хоть и не к радости пришлось тем, кто не любил зеленый, а всеж горевать не от чего - пусть будет как многим нравится.
</p>

<p>
Такова суть предлагаемого сервиса: Вы задаете вопрос, добавляете варианты ответа, публикуете у себя на сайте специальный код или просто ссылку в соц-сетях.<br/>
По прошествии времени анализируете данные участниками опроса ответы.<br/>
	Все что для этого нужно: 1) <a href="/site/login">Войти в систему</a> и 2) <?=\yii\bootstrap\Html::a('Создать новый опрос','/poll/create',['data'=>['method'=>'post'], 'class'=>['btn','btn-success']])?>
</p>
<?=Yii::$app->user->isGuest ? '' : \yii\bootstrap\Html::a('Мои опросы','/poll/list',['class'=>['btn','btn-primary']])?>