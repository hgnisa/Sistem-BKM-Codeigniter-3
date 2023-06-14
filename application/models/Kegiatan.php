<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kegiatan extends CI_Model
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

    ################################################################################
    
    public function getnotify($data = []){
        $data = $this->db->query("SELECT * FROM kegiatan WHERE keg_status = 'p' GROUP BY keg_date");
        return $data->result();
    }

    public function month($data) {
        if($data == '1'){
            $result = "Januari";
        }elseif($data == '2'){
            $result = "Fabruari";
        }elseif($data == '3'){
            $result = "Maret";
        }elseif($data == '4'){
            $result = "April";
        }elseif($data == '5'){
            $result = "Mei";
        }elseif($data == '6'){
            $result = "Juni";
        }elseif($data == '7'){
            $result = "Juli";
        }elseif($data == '8'){
            $result = "Agustus";
        }elseif($data == '9'){
            $result = "September";
        }elseif($data == '10'){
            $result = "Oktober";
        }elseif($data == '11'){
            $result = "November";
        }elseif($data == '12'){
            $result = "Desember";
        }
        return $result;
	}

    public function getRekap($month, $year, $data = []){
        $month = $month ? $month : date('m');
        $year = $year ? $year : date('Y');

        $start = $year."-".$month."-01";
        $end = $year."-".$month."-".date("t", strtotime($year."-".$month."-01"));

        $data = $this->db->query("SELECT  * FROM kegiatan WHERE keg_date BETWEEN '$start' AND '$end' GROUP BY keg_date ORDER BY keg_date DESC");
        return $data->result();
    }

    public function showRekap($month, $year){
        $rekap = $this->reports->getrekap($month, $year, '');

        foreach($rekap as $r){
            ## get pekerjaan name
            $name = $this->db->query("SELECT pekerjaan_name FROM pekerjaan WHERE pekerjaan_id = '$r->pekerjaan_id'")->row()->pekerjaan_name;

            ## get total volume
            $volume = $this->db->query("SELECT SUM(keg_volume) as volume FROM kegiatan WHERE keg_date = '$r->keg_date'")->row()->volume;

            ## get all kavling
            $kavlings = $this->db->query("SELECT b.kav_name as kavling FROM kegiatan a, kavling b WHERE a.kav_id = b.kav_id AND keg_date = '$r->keg_date'")->result();

            foreach($kavlings as $k){
                $kav[] = $k->kavling;
            }

            $kav = array_unique($kav);
            $datarekap[] = [
                'date'     => $r->keg_date,
                'name'     => $name,
                'volume'   => $volume,
                'kavling'  => implode(", ", $kav),
                'status'   => $r->keg_status
            ];
            // var_dump($datarekap); print "<br><br>";
            
        }
        return $datarekap;
    }

}