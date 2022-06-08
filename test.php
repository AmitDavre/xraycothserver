<?php

		$terms_consent_date_value =  '05-12-2022 00:00:00'; // m-d-Y format

		$formatDate = DateTime::createFromFormat("m-d-Y H:i:s" , $terms_consent_date_value);
		$formatDate->format('Y-m-d H:i:s');


		$hours = '24'; // hours amount (integer) you want to add
		$modified = $formatDate->add(new DateInterval("PT{$hours}H")); // use clone to avoid modification of $now object

		echo '<pre>';
		print_r($modified);
		echo '</pre>';



?>