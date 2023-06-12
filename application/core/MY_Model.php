<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {
    function __construct()
    {
        parent::__construct();
    }

    function insert($table, $data)
    {
        $data = $this->db->insert($table, $data);
        return $data;
    }

    function update($table, $data, $id = '')
    {
        $data = $this->db->where('id', $id)->update($table, $data);
    }

    function delete($table, $id)
    {
        $this->db->delete($table, $id);
    }

    function find($table, $data = [])
    {
        $data = $this->db->where($table, $data)->result();
        return $data;
    }

    function findOne($table, $data = [])
    {
        $data = $this->db->where($table, $data)->row();
        return $data;
    }

}