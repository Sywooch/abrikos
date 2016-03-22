<h3 class="banner"><?=  yii\helpers\Html::a('Сапер',['game/minerGame'])?></h3>
<table class="table table-hover table-condensed" >
    <tr>
        <th>#</th><th>Имя</th><th>Поле/бомб</th><th>Ходов</th><th>Секунд</th><th>рейтинг</th><th>Дата</th>
    </tr>
    <tbody  id="minerTopRows<?=$uid?>"></tbody>
</table>

<script>
    $(function() {
        $.getJSON('/game/minerGame-table', {from: '<?=$from?>', to: '<?=$to?>', registred: <?=$registred*1?>}, function (json) {
            $.each(json, function (i, item) {
                $('#minerTopRows<?=$uid?>').append('<tr class="counting"><td></td><td><a href="/game/minerGame-result?id='+item.id+'">'+item.player+'</a></td><td>'+item.rows+'x'+item.cols+'/'+item.mines+'</td><td>'+item.turn+'</td><td>'+item.time+'</td><td>'+item.rate.toFixed(2)+'</td><td>'+item.date+'</td></tr>');

            })
        })
    });

</script>