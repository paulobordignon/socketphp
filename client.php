<?php
	$host = "127.0.0.1";
	$socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
	$porta = 65003;
	if(socket_connect($socket, $host, $porta)){
		echo "Conectado na porta: " . $porta;
		//envio de dados
		if(empty($_POST['paciente'])){
			//envio de busca
			$buscar = @$_POST['buscar'];
			$fullbuscar = ("SELECT * FROM consulta WHERE paciente LIKE '%".$buscar."%'");
			socket_write($socket,$fullbuscar,strlen($fullbuscar));
		}else{
			//envio de dados
			$paciente = @$_POST['paciente'];
			$receita = @$_POST['receita']; 
			$full = ("INSERT INTO consulta(paciente,receita)VALUES('$paciente','$receita')");
			socket_write($socket,$full,strlen($full));
		}
		// receber resposta do servidor
		$operacao_receber = socket_recv($socket,$buffer_resposta,1024,MSG_WAITALL);
		echo "<center><br> Servidor disse: ". $buffer_resposta;
	}else{
		echo "Nao tem conexao com porta ".$porta;	
	}
	socket_close($socket);
?>
<html>
<head><title> Cliente </title></head>
<body>
 	<form id="envio" name="envio" action="#" method="post"> 
		<center>
			<label>Paciente:</label>
			<input type="text" class="inputtext" name="paciente" id = "paciente"  tabindex="1">
			</br> </br>
			<textarea rows="10" cols="100" name="receita"  id= "receita" tabindex="2" > </textarea>
			</br> </br>
			<input type="submit"  name="enviar" value="Enviar" tabindex="3">
		</center>
	</form> 
	<form id="relatorio" name="relatorio" action="" method="post">
		<center> -----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------		</center>
		</br></br>
		<center>
			<label>Digite o nome do Paciente</label>
			<input type="text" class="inputtext" name="buscar" id="buscar" tabindex="4">
			<input type="submit" value="Buscar" tabindex="5">
		</center>
	</form>
</body>
</html>