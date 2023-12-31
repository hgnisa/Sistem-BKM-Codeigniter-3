<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class kavlingModel extends CI_Model
{

    private $table = 'kavling';
    private $index = 'kav_id';

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

    public function validate($name, $shm){
        $this->db->where('kav_name', $name);
        $this->db->where('kav_shm', $shm);
        
        $result = $this->db->get($this->table)->row();
        return $result;
    }

    public function validateUpdate($id, $name, $shm)
    {
        $this->db->where('kav_name', $name);
        $this->db->where('kav_shm', $shm);
        $this->db->where('kav_id !=', $id);
        
        $result = $this->db->get($this->table)->row();
        return $result;
    }
}