<?php
require_once '../model/DAO/PacienteDAO.php';
    class PacienteDTO{
        private $id;
        private $Nome;
        private $CPF;
        private $Telefone;
        private $Email;
        private $listaPaciente;

        public function __construct(){

        }

        public function __getId(){
            return $this->id;
        }

        public function __setId($id){
            return $this->id = $id;
        }

        public function __getNome(){
            return $this->Nome;
        }

        public function __setNome($Nome){
            return $this->Nome = $Nome;
        }

        public function __getCPF(){
            return $this->CPF;
        }

        public function __setCPF($CPF){
            return $this->CPF = $CPF;
        }

        public function __getTelefone(){
            return $this->Telefone;
        }

        public function __setTelefone($Telefone){
            $this->Telefone = $Telefone; 
        }

        public function __getEmail(){
            return $this->Email;
        }

        public function __setEmail($Email){
            $this->Email = $Email;
        }

        public function __setListaPaciente($listaPaciente){
            $this->listaPaciente = $listaPaciente;
        }

        public function __getListaPaciente(){
            return $this->listaPaciente;
        }

        public function __getById(int $id){

            $PacienteDAO = new PacienteDAO();

            $PacienteDAO = $PacienteDAO->consultarId((int) $_GET['id'] );

            var_dump($PacienteDAO);

        }
    }
?>