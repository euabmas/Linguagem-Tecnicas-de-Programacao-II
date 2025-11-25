<?php
    

        $dsn = "mysql:host=localhost;dbname=ltpii";
        $usuario = "root";
        $senha = "";

        try{
            $conexao = new PDO($dsn , $usuario, $senha);
            
            //echo "Conexão Realizada com Sucesso!!!!!";
        }catch (PDOException $erro){
            echo "Não foi possível se conectar com o banco de dados: ".$erro->getMessage();
        }
    
?>