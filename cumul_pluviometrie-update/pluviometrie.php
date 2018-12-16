<?php
/*
 * @ScriptName:
 * pluviometrie.php
 *
 * @ScriptDescription:
 * Script de cummul mensuel et annuel de la pluviometrie
 *
 * @ScriptVersion:
 * V 1.1
 *
 * @ScriptAuthor:
 * Auteur original Aurel
 *
 * 
 */

include ('parametres.php');
 
	//URL locale
	$url =  "http://".$IPeedomus."/api/get?action=periph.caract&periph_id=".$pluiedujour."&api_user=".$api_user."&api_secret=".$api_secret."";
	//URL Web -- $url =  "http://api.eedomus.com/get?action=periph.caract&periph_id=".$pluiedujour."&api_user=".$api_user."&api_secret=".$api_secret."";
          $arr = json_decode(utf8_encode(file_get_contents($url)));
 		  $valuej = $arr->body->last_value;
		  $datemaj = $arr->body->last_value_change;

				$dateperiph = new DateTime($datemaj);
				$datemaj = $dateperiph->format('m');


	$datetoday = date("m");
	$datedaytoday = date("d");

	if ($datedaytoday == '01') {
		//URL locale
		$url =  "http://".$IPeedomus."/api/get?action=periph.caract&periph_id=".$cumulmois."&api_user=".$api_user."&api_secret=".$api_secret."";
		//URL Web -- $url =  "http://api.eedomus.com/get?action=periph.caract&periph_id=".$cumulmois."&api_user=".$api_user."&api_secret=".$api_secret."";
	          $arr = json_decode(utf8_encode(file_get_contents($url)));
	 		  $valuem = $arr->body->last_value;
			  $datemajm = $arr->body->last_value_change;

		//URL locale
		$url =  "http://".$IPeedomus."/api/get?action=periph.caract&periph_id=".$cumulannuel."&api_user=".$api_user."&api_secret=".$api_secret."";
		//URL Web -- $url =  "http://api.eedomus.com/get?action=periph.caract&periph_id=".$cumulannuel."&api_user=".$api_user."&api_secret=".$api_secret."";
	          $arr = json_decode(utf8_encode(file_get_contents($url)));
	 		  $valuea = $arr->body->last_value;
			  $datemaja = $arr->body->last_value_change;

			  		$valuean = ($valuea + $valuem);


				$url = "http://$IPeedomus/api/set?action=periph.value";
				$url .= "&api_user=$api_user";
				$url .= "&api_secret=$api_secret";
				$url .= "&periph_id=$cumulannuel";
				$url .= "&value=$valuean";

				$result = file_get_contents($url);

				if (strpos($result, '"success": 1') == false)
				{
				  echo "<div id='nok'>Une erreur est survenue sur l'update du cumul annuel: [".$result."]</div>";
				}
				else
				{
				 echo "<div id='ok'>Update cumul annuel OK</div><br/>";
				}

				$reset = 0;

					$url = "http://$IPeedomus/api/set?action=periph.value";
					$url .= "&api_user=$api_user";
					$url .= "&api_secret=$api_secret";
					$url .= "&periph_id=$cumulmois";
					$url .= "&value=$reset";

					$result = file_get_contents($url);

					if (strpos($result, '"success": 1') == false)
					{
					  echo "<div id='nok'>Une erreur est survenue lors du reset du cumul mensuel: [".$result."]</div>";
					}
					else
					{
					 echo "<div id='ok'>Reset cumul mensuel OK</div><br/>";
					}
	}
	elseif ($datedaytoday <> '01') {
		//URL locale
		$url =  "http://".$IPeedomus."/api/get?action=periph.caract&periph_id=".$cumulmois."&api_user=".$api_user."&api_secret=".$api_secret."";
		//URL Web -- $url =  "http://api.eedomus.com/get?action=periph.caract&periph_id=".$cumulmois."&api_user=".$api_user."&api_secret=".$api_secret."";
	          $arr = json_decode(utf8_encode(file_get_contents($url)));
	 		  $valuem = $arr->body->last_value;
			  $datemajm = $arr->body->last_value_change;

			  		$valuetotal = ($valuej + $valuem);

				$url = "http://$IPeedomus/api/set?action=periph.value";
				$url .= "&api_user=$api_user";
				$url .= "&api_secret=$api_secret";
				$url .= "&periph_id=$cumulmois";
				$url .= "&value=$valuetotal";

				$result = file_get_contents($url);

				if (strpos($result, '"success": 1') == false)
				{
				  echo "<div id='nok'>Une erreur est survenue sur l'update du cumul mensuel: [".$result."]</div>";
				}
				else
				{
				 echo "<div id='ok'>Update cumul mensuel OK</div><br/>";
				}

	}
