<?php

    require_once '../model/DTO/PacienteDTO.php';
    require_once '../model/DAO/PacienteDAO.php';
    require_once '../model/DAO/conexao.php';

    if (!empty($_GET['id'])){

        $id = $_GET['id'];

        $PacienteDAO = new PacienteDAO($conexao);
        $PacienteDAO->excluirPaciente( $id);
    }
     header("Location: ../view/consultarPaciente.php");
?>