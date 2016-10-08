<?php
$arquivo = fopen ('jogos.txt', 'r');

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

function Quick(&$vetor, $inicio, $fim) {
   
	$i = $inicio;
	$j = $fim;
   
	$meio = (($i + $j) / 2);

	$pivo = $vetor[$meio];
   
	do {
    	while ( compare($vetor[$i], $pivo) ) $i = $i + 1;

    	while ( compare($pivo, $vetor[$j]) ) $j = $j - 1;
      
    	if($i <= $j) {
        	$aux = $vetor[$i];
        	$vetor[$i] = $vetor[$j];
        	$vetor[$j] = $aux;
        	$i = $i + 1;
        	$j = $j - 1;
      	}

    } while( $j > $i );
  	
  	if($inicio < $j) {
		Quick($vetor, $inicio, $j);
  	}

    if($i < $fim) {
    	Quick($vetor, $i, $fim);   
    }

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
		$time[$linhas[0]]['nome'] = $linhas[0];
		$time[$linhas[0]]['gols_pro'] = $linhas[1];
		$time[$linhas[0]]['gols_contra'] = $linhas[3];
	}

	//Verifico o Time 2
	if ( isset($time[$linhas[2]]['gols_pro']) ) {
		$time[$linhas[2]]['gols_pro'] += $linhas[3];
		$time[$linhas[2]]['gols_contra'] += $linhas[1];
	} else {
		$time[$linhas[2]]['nome'] = $linhas[2];
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

foreach($time as $k => $v) {
	$times[] = $v;
}
// Fecha arquivo aberto
fclose($arquivo);

?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/css/bootstrap.min.css" integrity="sha384-2hfp1SzUoho7/TsGGGDaFdsuuDL0LX2hnUp6VkX3CUQ2K4K+xjboZdsXyp4oUHZj" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="main.css">
	<title></title>
</head>
<body>
	<div class="container">
		<div class="col-md-6">
			<h1>Não ordenado</h1>
			<hr>
			<table class="table table-striped table-bordered">
				<thead>
					<th>Nome</th>
					<th>Gols Pro</th>
					<th>Gols Contra</th>
					<th>Pontos</th>
					<th>Saldo</th>
				</thead>
				<tbody>
				<?php foreach ($times as $key => $value) : ?>
					<tr>
						<td><?php echo $value['nome']; ?></td>
						<td><?php echo $value['gols_pro']; ?></td>
						<td><?php echo $value['gols_contra']; ?></td>
						<td><?php echo $value['pontos']; ?></td>
						<td><?php echo $value['gols_pro'] - $value['gols_contra']; ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php

		Quick($times, 0, count($times)-1);

		?>
		<div class="col-md-6">
		<h1>Ordenado</h1>
		<hr>
		<table class="table table-striped table-bordered">
			<thead>
				<th>Nome</th>
				<th>Gols Pro</th>
				<th>Gols Contra</th>
				<th>Pontos</th>
				<th>Saldo</th>
			</thead>
			<tbody>
			<?php foreach ($times as $key => $value) : ?>
				<tr>
					<td><?php echo $value['nome']; ?></td>
					<td><?php echo $value['gols_pro']; ?></td>
					<td><?php echo $value['gols_contra']; ?></td>
					<td><?php echo $value['pontos']; ?></td>
					<td><?php echo $value['gols_pro'] - $value['gols_contra']; ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		</div>
	</div>
	<footer>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js" integrity="sha384-THPy051/pYDQGanwU6poAc/hOdQxjnOEXzbT+OuUAFqNqFjL+4IGLBgCJC3ZOShY" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.2.0/js/tether.min.js" integrity="sha384-Plbmg8JY28KFelvJVai01l8WyZzrYWG825m+cZ0eDDS1f7d/js6ikvy1+X+guPIB" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/js/bootstrap.min.js" integrity="sha384-VjEeINv9OSwtWFLAtmc4JCtEJXXBub00gtSnszmspDLCtC0I4z4nqz7rEFbIZLLU" crossorigin="anonymous"></script>
	</footer>
</body>
</html>