<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class pekerjaanModel extends CI_Model
{

    private $table = 'pekerjaan';
    private $index = 'pekerjaan_id';

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

    public function validateEdit($id, $name){
        $this->db->where('pekerjaan_name', $name);
        $this->db->where('pekerjaan_id !=', $id);
        
        $result = $this->db->get($this->table)->row();
        return $result;
    }

    public function validate($name){
        $this->db->where('pekerjaan_name', $name);
        
        $result = $this->db->get($this->table)->row();
        return $result;
    }
}