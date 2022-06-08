<?php 

$years_value = getYears();


?>
<!-- Change year ---------------------------------------------------------------------->
<div class="btn-group" style="float:right">
	<button class="dropdown-toggle" data-toggle="dropdown">
		<?=$lng['Year'].' '.$_SESSION['rego']['year_'.$lang]?>
	</button>
	<div class="dropdown-menu">
		<? foreach($years_value as $k=>$v){ ?>
		<a data-year="<?=$k?>" class="dropdown-item changeYear"><?=$v?></a>
		<? } ?>
	</div>
</div>
