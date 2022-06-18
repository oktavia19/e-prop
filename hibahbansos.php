<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
class : hibahbansos
dev : Oktavia
date : 07/04/2022
co : BAPPEDA Tuban
version : 0.5
*/

class hibahbansos extends Models{

    protected $table = 'hibah_2022';
    protected $primarykey = 'id';
    protected $datetime = false;
    

    public function all(){

        $tahun=$this->session->userdata('set_tahun');
        $skpd_id = $this->session->userdata('skpd');
        if ($this->session->userdata('group')==1) {
         $hasil= $this->db->query("SELECT hibah_2022.*,skpd.nama_skpd AS skpd,
            program_2022.program AS program,kegiatan_2022.kegiatan AS kegiatan,sub_kegiatan_2022.sub_kegiatan FROM hibah_2022
            INNER JOIN skpd ON hibah_2022.opd_rekomendasi = skpd.id_skpd
            INNER JOIN program_2022 ON hibah_2022.program = program_2022.id_program
            INNER JOIN kegiatan_2022 ON hibah_2022.kegiatan = kegiatan_2022.id_kegiatan AND program_2022.id_program = kegiatan_2022.program_id
            INNER JOIN sub_kegiatan_2022 ON hibah_2022.sub_kegiatan = sub_kegiatan_2022.id_sub_keg AND kegiatan_2022.id_kegiatan = sub_kegiatan_2022.kegiatan_id where hibah_2022.tahun_pengajuan='$tahun' and hibah_2022.jenis_pengajuan='hibah'
            ORDER BY hibah_2022.created_by 
            ");
     } else {
         $hasil= $this->db->query("select hibah_2022.*,skpd.nama_skpd as skpd from hibah_2022 LEFT JOIN skpd on hibah_2022.opd_rekomendasi=skpd.id_skpd where hibah_2022.opd_rekomendasi='$skpd_id'");
     }
     return $hasil->result_array();

 }

 public function get_kecamatan(){
    $hasil=$this->db->query("SELECT * FROM kecamatan");
    return $hasil;
}

function get_desa($id_kecamatan){
    $query = $this->db->get_where('desa', array('kecamatan_id' => $id_kecamatan));
    return $query;
}

function skpd(){
    $tahun=$this->session->userdata('set_tahun');
    $hasil=$this->db->query("SELECT * FROM skpd where tahun='$tahun'");
    return $hasil;
}

function program(){
    $tahun=$this->session->userdata('set_tahun');
    $skpd_id = $this->session->userdata('skpd');
    if ($this->session->userdata('group')==1) {
        $hasil=$this->db->query("SELECT * FROM program_2022 where tahun='$tahun'");
    } else {
        $hasil=$this->db->query("SELECT * FROM program_2022 where tahun='$tahun' and skpd_id='$skpd_id'");
    }
    return $hasil;
}

function get_kegiatan($id_program){
    $query = $this->db->get_where('kegiatan_2022', array('program_id' => $id_program));
    return $query;
}

function get_sub_kegiatan($id_kegiatan){
    $query = $this->db->get_where('sub_kegiatan_2022', array('kegiatan_id' => $id_kegiatan));
    return $query;
}

public function getID()
{
    $startdate = date('Y-m-d');

    $this->startTransaction();
    $query = $this->exec("SELECT MAX(id) as id FROM hibah_2022 WHERE DATE(created_at)='".$startdate."'");
    $this->completeTransaction();

    $max = $query->result();

    if(count($max) > 0 && $max[0]->id > 0)
    {
        $id = $max[0]->id+1;
            //str_replace('-', '', $startdate).((substr($max[0]->id, 9,4))+1);
    }else{
        $startpoint  = '1111';
        $id          = str_replace('-', '', $startdate).$startpoint;
    }

    return $id;
}

function simpan($tabelname,$data){
    $res=$this->db->insert($tabelname,$data);
    return $res;
}

public function hapus($tabelname,$where){
    $res=$this->db->delete($tabelname,$where);
    return $res;
}

public function GetData($where=""){
    $data=$this->db->query('select*from hibah_2022 '.$where);
    return $data->result_array();
}

function laporanHibah(){
    $tahun=$this->session->userdata('set_tahun');
    $skpd_id = $this->session->userdata('skpd');
    if ($this->session->userdata('group')==1) {
     $hasil= $this->db->query("select hibah_2022.*,skpd.nama_skpd as skpd,program_2022.program as program, kegiatan_2022.kegiatan as kegiatan, sub_kegiatan_2022.sub_kegiatan as sub_kegiatan 
        from hibah_2022 
        LEFT JOIN skpd on hibah_2022.opd_rekomendasi=skpd.id_skpd
        LEFT join program_2022 on hibah_2022.program=program_2022.id_program
        LEFT JOIN kegiatan_2022 on program_2022.id_program= kegiatan_2022.program_id
        LEFT JOIN sub_kegiatan_2022 on kegiatan_2022.id_kegiatan=sub_kegiatan_2022.kegiatan_id group by hibah_2022.opd_rekomendasi order by hibah_2022.program
        ");
 } else {
     $hasil= $this->db->query("select hibah_2022.*,skpd.nama_skpd as skpd from hibah_2022 LEFT JOIN skpd on hibah_2022.opd_rekomendasi=skpd.id_skpd where hibah_2022.opd_rekomendasi='$skpd_id'");
 }
 return $hasil->result_array();
}

function pilih_hibah($opd_rekomendasi){
    // $data=$this->db->query("select*from hibah_2022 where opd_rekomendasi='$opd_rekomendasi' and jenis_pengajuan='hibah'");
    $tahun=$this->session->userdata('set_tahun');
    $session = $this->session->userdata('username');
    if ($session=="admin") {
        $data=$this->db->query("SELECT hibah_2022.*,skpd.nama_skpd AS skpd,
            program_2022.program AS program,kegiatan_2022.kegiatan AS kegiatan,sub_kegiatan_2022.sub_kegiatan FROM hibah_2022
            INNER JOIN skpd ON hibah_2022.opd_rekomendasi = skpd.id_skpd
            INNER JOIN program_2022 ON hibah_2022.program = program_2022.id_program
            INNER JOIN kegiatan_2022 ON hibah_2022.kegiatan = kegiatan_2022.id_kegiatan AND program_2022.id_program = kegiatan_2022.program_id
            INNER JOIN sub_kegiatan_2022 ON hibah_2022.sub_kegiatan = sub_kegiatan_2022.id_sub_keg AND kegiatan_2022.id_kegiatan = sub_kegiatan_2022.kegiatan_id where hibah_2022.tahun_pengajuan='$tahun' and hibah_2022.jenis_pengajuan='hibah'
            and hibah_2022.opd_rekomendasi='$opd_rekomendasi'");
    } else {
        $data=$this->db->query("select hibah_2022.*, skpd.nama_skpd as skpd from hibah_2022 INNER JOIN skpd ON hibah_2022.opd_rekomendasi = skpd.id_skpd where opd_rekomendasi='$opd_rekomendasi' and jenis_pengajuan='hibah' and created_by='$session'");
    }
    return $data->result_array();
}

function exportData(){
    $tahun=$this->session->userdata('set_tahun');
    $hasil= $this->db->query("SELECT hibah_2022.created_by, skpd.nama_skpd as skpd FROM hibah_2022 INNER JOIN skpd ON hibah_2022.opd_rekomendasi = skpd.id_skpd where hibah_2022.tahun_pengajuan='$tahun' group by hibah_2022.created_by");
    return $hasil->result_array();
}

function exportDataBansos(){
    $tahun=$this->session->userdata('set_tahun');
    $hasil= $this->db->query("SELECT bansos_2022.created_by, skpd.nama_skpd as skpd FROM bansos_2022 INNER JOIN skpd ON bansos_2022.opd_rekomendasi = skpd.id_skpd where bansos_2022.tahun_pengajuan='$tahun' group by bansos_2022.created_by");
    return $hasil->result_array();
}

function exportLaporan($created_by){
    $tahun=$this->session->userdata('set_tahun');
    $hasil= $this->db->query("SELECT program_2022.program AS program, kegiatan_2022.kegiatan AS kegiatan, sub_kegiatan_2022.sub_kegiatan AS sub_kegiatan, hibah_2022.tahun_pengajuan, hibah_2022.jenis_pengajuan, hibah_2022.peruntukan, hibah_2022.penerima, hibah_2022.pimpinan,hibah_2022.bhi, hibah_2022.alamat, hibah_2022.nominal, skpd.nama_skpd AS skpd, hibah_2022.uraian_keg_satuan, hibah_2022.pejabat_penerbitan_rekomendasi, hibah_2022.isi_disposisi_ketua_tapd, hibah_2022.tanggal_disposisi_bupati FROM hibah_2022
        INNER JOIN program_2022 ON hibah_2022.program = program_2022.id_program
        INNER JOIN kegiatan_2022 ON hibah_2022.kegiatan = kegiatan_2022.id_kegiatan
        INNER JOIN sub_kegiatan_2022 ON hibah_2022.sub_kegiatan = sub_kegiatan_2022.id_sub_keg
        INNER JOIN skpd ON hibah_2022.opd_rekomendasi = skpd.id_skpd
        where hibah_2022.tahun_pengajuan='$tahun' and hibah_2022.jenis_pengajuan='HIBAH' and hibah_2022.created_by='$created_by'");
    return $hasil->result_array();
}

function exportLaporanBansos($created_by){
    $tahun=$this->session->userdata('set_tahun');
    $hasil= $this->db->query("SELECT program_2022.program AS program, kegiatan_2022.kegiatan AS kegiatan, sub_kegiatan_2022.sub_kegiatan as sub_kegiatan, bansos_2022.tahun_pengajuan, bansos_2022.jenis_pengajuan, bansos_2022.peruntukan, bansos_2022.penerima, bansos_2022.pimpinan,bansos_2022.bhi, bansos_2022.alamat, bansos_2022.nominal, bansos_2022.kk, bansos_2022.nik,  skpd.nama_skpd AS skpd, bansos_2022.uraian_keg_satuan, bansos_2022.pejabat_penerbitan_rekomendasi, bansos_2022.isi_disposisi_ketua_tapd, bansos_2022.tanggal_disposisi_bupati FROM bansos_2022
        INNER JOIN program_2022 ON bansos_2022.program = program_2022.id_program
        INNER JOIN kegiatan_2022 ON bansos_2022.kegiatan = kegiatan_2022.id_kegiatan
        INNER JOIN skpd ON bansos_2022.opd_rekomendasi = skpd.id_skpd
        INNER JOIN sub_kegiatan_2022 ON bansos_2022.sub_kegiatan = sub_kegiatan_2022.id_sub_keg
        where bansos_2022.tahun_pengajuan='$tahun'and bansos_2022.created_by='$created_by'
        ");
    return $hasil->result_array();
}

function RekapNominal(){
    $data=$this->db->query("SELECT hibah_2022.jenis_pengajuan, hibah_2022.opd_rekomendasi, skpd.nama_skpd AS skpd, SUM(nominal) as TotalHibah FROM hibah_2022 INNER JOIN skpd ON hibah_2022.opd_rekomendasi = skpd.id_skpd GROUP BY hibah_2022.opd_rekomendasi, hibah_2022.jenis_pengajuan");
    return $data->result_array();
}

//bansos

function bansos(){
    $tahun=$this->session->userdata('set_tahun');
    $skpd_id = $this->session->userdata('skpd');
    if ($this->session->userdata('group')==1) {
     $hasil= $this->db->query("SELECT hibah_2022.*,skpd.nama_skpd AS skpd,
        program_2022.program AS program,kegiatan_2022.kegiatan AS kegiatan,sub_kegiatan_2022.sub_kegiatan FROM hibah_2022
        INNER JOIN skpd ON hibah_2022.opd_rekomendasi = skpd.id_skpd
        INNER JOIN program_2022 ON hibah_2022.program = program_2022.id_program
        INNER JOIN kegiatan_2022 ON hibah_2022.kegiatan = kegiatan_2022.id_kegiatan AND program_2022.id_program = kegiatan_2022.program_id
        INNER JOIN sub_kegiatan_2022 ON hibah_2022.sub_kegiatan = sub_kegiatan_2022.id_sub_keg AND kegiatan_2022.id_kegiatan = sub_kegiatan_2022.kegiatan_id where hibah_2022.tahun_pengajuan='$tahun' and hibah_2022.jenis_pengajuan='hibah'
        ORDER BY hibah_2022.created_by 
        ");
 } else {
    $hasil= $this->db->query("SELECT hibah_2022.*,skpd.nama_skpd AS skpd,
        program_2022.program AS program,kegiatan_2022.kegiatan AS kegiatan,sub_kegiatan_2022.sub_kegiatan FROM hibah_2022
        INNER JOIN skpd ON hibah_2022.opd_rekomendasi = skpd.id_skpd
        INNER JOIN program_2022 ON hibah_2022.program = program_2022.id_program
        INNER JOIN kegiatan_2022 ON hibah_2022.kegiatan = kegiatan_2022.id_kegiatan AND program_2022.id_program = kegiatan_2022.program_id
        INNER JOIN sub_kegiatan_2022 ON hibah_2022.sub_kegiatan = sub_kegiatan_2022.id_sub_keg AND kegiatan_2022.id_kegiatan = sub_kegiatan_2022.kegiatan_id where hibah_2022.opd_rekomendasi='$skpd_id'");
}
return $hasil->result_array();
}

public function getIDBansos()
{
    $startdate = date('Y-m-d');

    $this->startTransaction();
    $query = $this->exec("SELECT MAX(id) as id FROM bansos_2022 WHERE DATE(jenis_pengajuan)='".$startdate."'");
    $this->completeTransaction();

    $max = $query->result();

    if(count($max) > 0 && $max[0]->id > 0)
    {
        $id = $max[0]->id+1;
        str_replace('-', '', $startdate).((substr($max[0]->id, 9,4))+1);
    }else{
        $startpoint  = '1111';
        $id          = str_replace('-', '', $startdate).$startpoint;
    }

    return $id;
}
}