<?php \app\modules\cows\CowsAsset::register($this)?>
<?php
$this->title = 'Быки и коровы';
?>

<div class="well">
    <strong>Правила игры</strong>
    <p>
Компьютер задумывает четырехзначное число. Цифры в числе не повторяются, 0 может стоять на первом месте. Игрок делает ходы, чтобы узнать это число. В ответ на каждый ход компьютер показывает число отгаданных цифр, стоящих на своих местах (число быков) и число отгаданных цифр, стоящих не на своих местах (число коров).
</p>
<strong>Пример</strong>
<p>
Компьютер задумал 0834. Игрок походил 8134. Компьютер ответил: 2 быка (цифры 3 и 4) и 1 корова (цифра 8).
</p>
</div>

<div  id="startButton">
<?php if(\Yii::$app->user->isGuest):?>
Введите Ваше имя для таблицы результатов: <input id="playerName" />  и нажмите
<?php endif?>
<a href="javascript:start()" class="btn btn-success">Начать игру!</a>
    <br/><br/>
</div>


<div id="gameContainer" class="hide">
    <form method="post" id="gameForm" onsubmit="return false">
    Введите число <input name="test" id="testField"/>
    <a href="javascript:testNumber()" class="btn btn-default">Сделать ход!</a>
    <span id="error" style="color: red"></span>
    <input name="_csrf" type="hidden" value="<?=Yii::$app->request->getCsrfToken()?>" />
    </form>
    <div class="table-responsive">
    	<table class="" id="clickDigits">
    		<tbody>
    			<tr>
                    <?php for($i=0;$i<10;$i++):?>
    				<td onclick="addDigit(<?=$i?>)" id="digit<?=$i?>" class="btn btn-default"><?=$i?></td>
                    <?php endfor?>
    			</tr>
    		</tbody>
    	</table>
    </div>

    <table class="table table-hover" style="width: 400px">
        <tr>
            <th>#</th><th>Число</th><th>Быки</th><th>Коровы</th>
        </tr>
        <tbody  id="gameResults"></tbody>
    </table>
</div>    

