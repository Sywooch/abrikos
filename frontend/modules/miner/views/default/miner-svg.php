<?php $bundle = \app\modules\minerGame\MinerAsset::register($this)?>

<svg id="svg-container" style="background-color:silver" oncontextmenu="return false;">
	<style>
		.mineCell{ width: 30px; height:30px; text-align: center; font-size: 1.3em; font-weight: bolder; cursor: pointer;}
		.initial{ border: 2px outset; fill: #808080;}
		.mine{ fill: red; }
		.Empty{fill: #ffffff; border:1px solid black;}
		.Info{fill: silver; border:1px solid black;}
		.flag{background-image:url(/images/miner/flag.png)}
	</style>
	<use xlink:href="<?=$bundle->baseUrl?>/images/bomb.svg#layer1" class="blue" id="bomb-proto" />
	<use xlink:href="<?=$bundle->baseUrl?>/images/flag.svg#flag" class="blue" id="flag-proto" />
</svg>

<a href="javascript:void(0)" onclick="fillTable(9,9,12)" class="btn btn-primary">Новая игра</a>
<script>
	$(function(){ fillTable(9,9,12)})
</script>
