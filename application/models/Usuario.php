<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usuario extends CI_Model
{
    private $table;

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->table = "usuarios";
    }

    public function validate($login, $senha){
        $this->db->where('login', $login);
        $this->db->where('senha', $senha);
        
        $query = $this->db->get($this->table);

        if($query->num_rows() > 0){
            return $query->result()[0];
        }else{
            return null;
        }
    }

    public function allWithPagination($limit = null, $offset = null)
    {
        if($limit)
            $this->db->limit($limit, $offset);

        $query = $this->db->get($this->table);

        if($query->num_rows() > 0)
            return $query->result();
        else
            return null;
    }

    public function find($id)
    {
        $query = $this->db->get_where($this->table, array("id" => $id));
        
        if($query->num_rows() > 0)
            return $query->result()[0];
        else
            return null;
    }

    public function findByLogin($login){
        $query = $this->db->get_where($this->table, array("login" => $login));
        
        if($query->num_rows() > 0)
            return $query->result()[0];
        else
            return null;
    }

    public function findByEmail($email){
        $query = $this->db->get_where($this->table, array("email" => $email));
        
        if($query->num_rows() > 0)
            return $query->result()[0];
        else
            return null;
    }

    public function create($data)
    {
        $usuario["nome"] = $data["nome"];
        $usuario["email"] = $data["email"];
        $usuario["login"] = $data["login"];
        $usuario["senha"] = md5($data["senha"]);
        $usuario["status"] = $data["status"];
        $usuario["admin"] = $data["admin"];
        
        $this->db->insert($this->table, $usuario);
    }

    public function update($data, $id)
    {
        $usuario["nome"] = $data["nome"];
        $usuario["email"] = $data["email"];
        $usuario["login"] = $data["login"];

        if(!empty($data["senha"])){
            $usuario["senha"] = md5($data["senha"]);
        }
        
        if($this->session->userdata("loggedUser")->admin){
            $usuario["status"] = $data["status"];
            $usuario["admin"] = $data["admin"];
        }

        $this->db->update($this->table, $usuario, array('id' => $id));
    }

    public function delete($id){
        $this->db->delete($this->table, array('id' => $id));
    }

    public function alterarSenha($senha, $id)
    {
        $usuario["senha"] = $senha;

        $this->db->update($this->table, $usuario, array('id' => $id));
    }

    public function countAll(){
        return $this->db->count_all($this->table);
    }    
}
