<?php
    require_once '../model/DTO/PacienteDTO.php';
    require_once '../model/DAO/PacienteDAO.php';
    require_once '../model/DAO/conexao.php';
    

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if($_POST['btnCadastrar']){
            $PacienteDTO = new PacienteDTO();
            $PacienteDTO->__setNome($_POST['Nome']);
            $PacienteDTO->__setCPF($_POST['CPF']);
            $PacienteDTO->__setTelefone($_POST['Telefone']);
            $PacienteDTO->__setEmail($_POST['Email']);
            
            $PacienteDAO = new PacienteDAO($conexao);
            $PacienteDAO->cadastrarPaciente($PacienteDTO);
        }

        header("Location: ../view/consultarPaciente.php");
    }

   


?>