<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RecuperarSenha extends CI_Model
{
    private $table;

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->table = "recuperar_senha";
    }


    public function find($id)
    {
        $query = $this->db->get_where($this->table, array("id" => $id));
        
        if($query->num_rows() > 0)
            return $query->result()[0];
        else
            return null;
    }

    public function findByHash($hash)
    {
        $query = $this->db->get_where($this->table, array("hash" => $hash));
        
        if($query->num_rows() > 0)
            return $query->result()[0];
        else
            return null;
    }


    public function create($hash, $usuario_id)
    {
        $recuperar["hash"] = $hash;
        $recuperar["usuario_id"] = $usuario_id;

        $this->db->insert($this->table, $recuperar);
    }

    public function update($data, $id)
    {
        $usuario["nome"] = $data["nome"];
        $usuario["email"] = $data["email"];
        $usuario["login"] = $data["login"];

        if(!empty($data["senha"])){
            $usuario["senha"] = md5($data["senha"]);
        }
        
        $usuario["status"] = $data["status"];
        $usuario["admin"] = $data["admin"];

        $this->db->update($this->table, $usuario, array('id' => $id));
    }

    public function delete($id){
        $this->db->delete($this->table, array('id' => $id));
    }

    public function countAll(){
        return $this->db->count_all($this->table);
    }    
}
