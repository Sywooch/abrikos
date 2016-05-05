<?php
use frontend\assets\ToolsAsset;
$this->title = 'IP инструменты ';
$this->params['breadcrumbs'][] = $this->title;

ToolsAsset::register($this);
?>
<h1><?=$this->title?></h1>
<div class="row">
	<div class="col-md-3" id="tools-menu">
		<dl>
			<dt>Мой IP:</dt>
			<dd><?=$_SERVER['REMOTE_ADDR']?></dd>
		</dl>
		<dl>
			<dt>Гео-информация об адресе:</dt>
			<dd><input value="" data-type="geoip" /><button>Отправить</button> </dd>
		</dl>
		<dl>
			<dt>Nslookup:</dt>
			<dd><input value="" data-type="nslookup" /><button>Отправить</button> </dd>
		</dl>
		<dl>
			<dt>WHOIS:</dt>
			<dd><input value="" data-type="whois" /><button>Отправить</button> </dd>
		</dl>		
		<dl>
			<dt>Ping:</dt>
			<dd><input value="" data-type="ping" /><button>Отправить</button> </dd>
		</dl>

	</div>
	<div class="col-md-9">
		<dl class="dl-horizontal" id="tools-result"></dl>
	</div>
</div>