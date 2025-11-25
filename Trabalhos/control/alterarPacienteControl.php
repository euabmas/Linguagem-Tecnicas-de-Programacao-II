<?php

    require_once '../model/DTO/PacienteDTO.php';
    require_once '../model/DAO/PacienteDAO.php';
    require_once '../model/DAO/conexao.php';

    if (!empty($_POST['idAlterar'])){

        $id = $_POST['idAlterar'];

        $PacienteDAO = new PacienteDAO($conexao);
        $PacienteDAO->consultarId($id);

        $PacienteDTO = new ProdutoDTO();
        $PacienteDTO->__setNome($_POST['Nome']);
        $PacienteDTO->__setCPF($_POST['CPF']);
        $PacienteDTO->__setTelefone($_POST['Telefone']);
        $PacienteDTO->__setEmail($_POST['Email']);
    
       $PacienteDAO->alterarPaciente($PacienteDTO, $id);
    }
    header("Location: ../view/consultarPaciente.php");
?>