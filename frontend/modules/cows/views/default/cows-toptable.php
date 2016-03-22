<h3 class="banner"><?=  yii\helpers\Html::a('Быки и коровы',['game/cows'])?></h3>
<table class="table table-hover table-condensed" >
    <tr>
        <th>#</th><th>Имя</th><th>Ходов</th><th>Секунд</th><th>Дата</th><th></th>
    </tr>
    <tbody  id="toptablerows<?=$uid?>"></tbody>
</table>
<script>
    $(function(){
        $('#toptable').show();
        $('#toptablerows<?=$uid?>').html('');
        $.getJSON('/game/cows-table',{'from':'<?=$from?>','to':'<?=$to?>', 'registred':<?=$registred*1?>},function(json){
            $.each(json,function(i,item){
                var result = '<div class="fb-share-button" data-href="/game/cows-result?id='+item.id+'" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>';
                $('#toptablerows<?=$uid?>').append('<tr class="counting"><td></td><td><a href="/game/cows-result?id='+item.id+'">'+item.player+'</a></td><td>'+item.steps+'</td><td>'+item.time+'</td><td>'+item.date+'</td><!--td>'+result+'</td--></tr>');
            })
        });
    });
</script>