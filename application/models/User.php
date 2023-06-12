<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Model
{

    private $table = 'user';
    private $index = 'user_id';

    public function __construct()
    {
        parent::__construct();
    }

    public function get($data = [])
    {
        $data = $this->db->get($this->table, $data);
        return $data->result();
    }

    public function find($data  = [])
    {
        $data = $this->db->get_where($this->table, $data);
        return $data->row();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $data;
    }

    public function update($data, $id)
    {
        $data = $this->db->where($this->index, $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        $this->db->delete($this->table, array($this->index => $id));
    }

    public function validate($username,$password,$type){
        $this->db->where('user_username', $username);
        $this->db->where('user_password', md5($password));
        $this->db->where('user_type', $type);
        
        $result = $this->db->get($this->table)->row();
        return $result;
    }
}