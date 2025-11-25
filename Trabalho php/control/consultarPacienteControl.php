<?php
    require_once '../model/DTO/PacienteDTO.php';
    require_once '../model/DAO/PacienteDAO.php';
    require_once '../model/DAO/conexao.php';

    $PacienteDTO = new PacienteDTO();
    $PacienteDAO = new PacienteDAO($conexao);
    $PacienteDAO->consultarPaciente($PacienteDTO);
    
?>