<?php
	function sendAlarm($msg, $number='79265786794')
	{
		$req_str = "http://sms.ru/sms/send?api_id=49a0de9e-4565-a6f4-298d-5b45b0548316&to=%NUMBER%&text=%TEXT%";

		$req = str_replace("%NUMBER%", $number, $req_str);
		$req = str_replace("%TEXT%", urlencode($msg), $req);
		$body=file_get_contents($req);
		//echo $body;
		//$body=file_get_contents("http://sms.ru/sms/send?api_id=49a0de9e-4565-a6f4-298d-5b45b0548316&to=79265786794&text=".urlencode("Привет мир!"));

	}
?>