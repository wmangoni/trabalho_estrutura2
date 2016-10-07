<?php
//ini_set('memory_limit', 65499);

$arquivo = fopen ('jogos.txt', 'r');

$cont = 0; //auxiliar para controlar iteraçoes dentor do while

function getPoints($gol1, $gol2){
	if ($gol1 > $gol2) {
		$return[] = 3;
		$return[] = 0;

	} else if ($gol1 == $gol1) {
		$return[] = 1;
		$return[] = 1;

	} else {
		$return[] = 0;
		$return[] = 3;
	}
	return $return;
}

while(!feof($arquivo)) {

	$linha = fgets($arquivo, 1024);
	$linhas = explode(' ', $linha);

	/*Primeiro vou somando os gols pros e contra de cada time*/

	//Verifico o Time 1
	if ( isset($time[$linhas[0]]['gols_pro']) ) {
		$time[$linhas[0]]['gols_pro'] += $linhas[1];
		$time[$linhas[0]]['gols_contra'] += $linhas[3];
	} else {
		$time[$linhas[0]]['gols_pro'] = $linhas[1];
		$time[$linhas[0]]['gols_contra'] = $linhas[3];
	}

	//Verifico o Time 2
	if ( isset($time[$linhas[2]]['gols_pro']) ) {
		$time[$linhas[2]]['gols_pro'] += $linhas[3];
		$time[$linhas[2]]['gols_contra'] += $linhas[1];
	} else {
		$time[$linhas[2]]['gols_pro'] = $linhas[3];
		$time[$linhas[2]]['gols_contra'] = $linhas[1];
	}


	/*Depois Verifico a vitória ou derrota de cada equipe e atribuo os pts corretos*/

	$pts = getPoints( $linhas[1] , $linhas[3] );

	//Verifico pts do Time 1
	if ( isset($time[$linhas[0]]['pontos']) ) {
		$time[$linhas[0]]['pontos'] += $pts[0];
	} else {
		$time[$linhas[0]]['pontos'] = $pts[0];
	}

	//Verifico pts o Time 2
	if ( isset($time[$linhas[2]]['pontos']) ) {
		$time[$linhas[2]]['pontos'] += $pts[1];
	} else {
		$time[$linhas[2]]['pontos'] = $pts[1];
	}
}
// Fecha arquivo aberto
fclose($arquivo);


function compare($time1, $time2) {
	return comparePts($time1, $time2);
}

function comparePts($time1, $time2) {
	if ($time1['pontos'] > $time2['pontos']) {
		return TRUE;
	} else if ($time1['pontos'] < $time2['pontos']){
		return FALSE;
	} else {
		return compareSaldo($time1, $time2);
	}
}

function compareSaldo($time1, $time2) {
	if ($time1['gols_pro'] > $time2['gols_pro']) {
		return TRUE;
	} else if ($time1['gols_pro'] < $time2['gols_pro']){
		return FALSE;
	} else {
		return compareMaiorGols($time1, $time2);
	}
}
function compareMaiorGols($time1, $time2) {
	$gol1 = $time1['gols_pro'] - $time1['gols_contra'];
	$gol2 = $time2['gols_pro'] - $time2['gols_contra'];
	if ( $gol1 > $gol2 ) {
		return TRUE;
	} else {
		return FALSE;
	}
}

function quick_sort($array)
{
	$length = count($array);
	
	if($length <= 1){
		return $array;
	}
	else{
	
		//pega primeiro indice do array
		$index = array_keys($array);
		$pivot = $array[ (string) $index[0] ];
		
		$left = $right = array();

		foreach ($array as $key => $val) {
			if( compare( $val, $pivot ) ) {
				$left[] = $array[$key];
			}
			else{
				$right[] = $array[$key];
			}
		}
		
		return array_merge(quick_sort($left), array($pivot), quick_sort($right));
	}
}

echo '<pre>';
print_r($time);

quick_sort($time, 0, count($time));

print_r($time);