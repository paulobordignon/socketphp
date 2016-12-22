<html>
<head> <title> Servidor </title> </head>
<body>
<?php
    $host = "localhost";
    $user = "root";
    $pass = "";
    $banco = "apsredes";
    $conexao = mysql_connect($host, $user, $pass) or die(mysql_error());
    mysql_select_db($banco) or die(mysql_error());
?>
<?php
	$socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
	socket_bind($socket,'127.0.0.1',65003);
	socket_listen($socket);
	$conn=false;
	echo "Esperando conexao<br>";
	switch (@socket_select($r = array($socket), $w = array($socket), $e = array($socket),60)){
		case 1:
			echo"Sucesso na conexao com cliente";
			$conn = socket_accept($socket);
			break;
		case 2:
			echo"Nao houve conexao";
			break;
		case 0:
			echo "Tempo de espera terminou";
			break;
	}
	while ( @socket_recv($conn,$buffer_resposta,1024,MSG_WAITALL)){	
		// receber solicitacao do cliente	
		echo "<br>Cliente diz : ".$buffer_resposta; 
		$sql = mysql_query("$buffer_resposta");
		// retornar consulta
		if  (strstr("$buffer_resposta","LIKE",true)){
			$row = mysql_num_rows($sql);
				if($row > 0){
					while ($linha = mysql_fetch_array($sql)){
					$paciente = $linha['paciente'];
					$receita = $linha['receita'];
					$rep = ("<br>Paciente:".$paciente. "<br>Receita:".$receita."<br><br>");
					socket_write(@$conn, @$rep, strlen(@$rep));
				}
		}else{
			$in = "nao encontramos paciente com esse nome";
			socket_write(@$conn, @$in, strlen(@$in));
		}
	}
	// retornar registro de dados com sucesso
	if (strstr("$buffer_resposta","INTO",true)){
		$en = "Seu registro foi gravado";
		socket_write(@$conn, @$en, strlen(@$en));
	}
}  
	echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=server.php'>";				
?>
</body>	
</html>