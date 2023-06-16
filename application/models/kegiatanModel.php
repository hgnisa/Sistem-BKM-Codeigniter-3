<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class kegiatanModel extends CI_Model
{

    private $table = 'kegiatan';
    private $index = 'keg_id';

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

    public function groupBy($where, $group, $order = NULL, $ordersort = 'ASC', $data  = [])
    {
        $this->db->from($this->table);
        $this->db->where($where);
        $this->db->group_by($group);
        if($order){
            $this->db->order_by($order, $ordersort);
        }
        $data=$this->db->get();

        return $data->result();
    }

    public function sum($sum, $where, $data = []){
        $this->db->from($this->table);
        $this->db->select_sum($sum);
        $this->db->where($where);
        $data = $this->db->get();

        return $data->result();
    }

    public function join($select = '*', $join, $on, $where, $data = []){
        $this->db->select($select); 
        $this->db->from($this->table);
        $this->db->join($join, $on);
        $this->db->where($where);
        $data = $this->db->get();

        return $data->result();
    }

    public function findOrder($select = '*', $where, $order = '', $ordersort = 'ASC', $data  = [])
    {
        $this->db->from($this->table);
        $this->db->select($select);
        $this->db->where($where);
        if($order){
            $this->db->order_by($order, $ordersort);
        }
        $data = $this->db->get();

        return $data->result();
    }

    public function verifyKegiatan($status, $date){
        $this->db->set('keg_status', $status);
        $this->db->where('keg_date', $date);
        $this->db->update($this->table);
    }
}