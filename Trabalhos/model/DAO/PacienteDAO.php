<?php
    //require_once 'conexao.php';
    
    class PacienteDAO{
        public $conexao;

        public function __construct($conexao){
           $this->conexao = $conexao;
        }

        public function cadastrarPaciente($PacienteDTO){
            
            try{
                $sql = 'INSERT INTO Paciente (Nome , CPF , Telefone , Email) VALUES (:Nome, :CPF , :Telefone , :Email)';
            
                $insert = $this->conexao->prepare($sql);

                $Nome = $PacienteDTO->__getNome();
                $CPF = $PacienteDTO->__getCPF();
                $Telefone = $PacienteDTO->__getTelefone();
                $Email = $PacienteDTO->__getEmail();

                $insert->bindValue(":Nome",$Nome);
                $insert->bindValue(":CPF",$CPF);
                $insert->bindValue(":Telefone",$Telefone);
                $insert->bindValue(":Email",$Email);

                if($insert->execute()){
                    echo "Paciente Cadastrado com Sucesso!!!!!";
                }else{
                    echo "Erro ao Cadastrar Paciente!!!!!";
                }

            }catch (PDOException $erro){

            }
            
        }
        
        public function consultarPaciente($PacienteDTO){
            try{

                $sql = 'SELECT * FROM Paciente';
                $consulta = $this->conexao->prepare($sql);
                if($consulta->execute()){
                    if($consulta->rowcount() > 0){
                        $listaPaciente = $consulta->fetchAll();
                        $PacienteDTO->__setListaPaciente($listaPaciente);
                    }
                }else{
                    echo "Erro ao Consultar Paciente";
                }

            }catch(PDOException $erro){

            }

        }

        public function consultarId(int $id){
            include_once '../model/DTO/PacienteDTO.php';
        
            $sql = 'SELECT * FROM Paciente WHERE id = :id';
            $consultaId = $this->conexao->prepare($sql);
            $consultaId->bindValue(":id",$id);
            $consultaId->execute();
        
            return $consultaId->fetchObject("PacienteDTO");
        }

        public function excluirProduto($id){
            try{

                $sql = 'DELETE FROM Paciente WHERE id = :id';
                $delete = $this->conexao->prepare($sql);
                $delete->bindValue(":id",$id);
                $delete->execute();

            }catch(PDOException){

            }
        }

         public function alterarPaciente($PacienteDTO, $id){
            try{

                $sql = 'UPDATE Paciente SET Nome=:Nome, CPF=:CPF, Telefone=:Telefone, Email=:Email WHERE id=:id';
                $altera = $this->conexao->prepare($sql);

                $Nome = $PacienteDTO->__getNome();
                $CPF = $PacienteDTO->__getCPF();
                $Telefone = $PacienteDTO->__getTelefone();
                $Email = $PacienteDTO->__getEmail();
                
                $altera->bindValue(":Nome",$Nome);
                $altera->bindValue(":CPF",$CPF);
                $altera->bindValue(":Telefone",$Telefone);
                $altera->bindValue(":Email",$Email);
                $altera->bindValue(":id",$id);

                $altera->execute();

            }catch(PDOException){

            }
        }

       
        
    }
?>