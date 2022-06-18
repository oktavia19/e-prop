<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * class :BansosController
 * created by:kanghen
 * created at:2/11/2017 - 20/11/2017 - 01/02/2018
 * co BAPPEDA Tuban
 */
class  BansosController extends CI_Controller {

    function __construct(){
        parent::__construct();

        $model = array(
            'skpd',
            'kecamatan',
            'desa',
            'submenu',
            'usulanbansos',
            'program',
            'kegiatan',
            'usulanskpdbansos',
            'usulanakhir',
            'master',
            'hibahbansos'
        );

        $library = array(
            'factory'
        );

        $this->load->model($model); 
        $this->load->library($library);
    }

    public function edit($id)
    {
        $skpd      = array();
        $kecamatan = $this->kecamatan->listofkecamatanid();
        $role      = $this->session->userdata('role');
        $skpd_id   = $program = 0;
        $src = '';

        $k    = $this->input->get('k');
        $h    = $this->input->get('h');
        $d    = $this->input->get('d');
        $desa = $this->desa->listofDesa($k);
        

        $hiban = array(
            0 => 'bansos',
            1 => 'Bansos'
        );

        $col = 'col-md-6';

        if($role > 0)
        {
            $col = 'col-md-12';
            $src = 'skpd-search-program';
        }
        $jenis_belanja = 0;
        if($role == 1)
        {
            $skpd_id = $this->session->userdata('skpd');
            $skpd = $this->skpd->listofSkpd();
            $program = $this->program->getbySkpd($skpd_id,$this->session->userdata('set_tahun'));
            $item = $this->usulanbansos->find($id);
            $usulanSkpd = $this->usulanskpdbansos->getByUsulan($id);
            $jenis_belanja = $usulanSkpd->jenis_belanja;
            $kegiatanId = $this->kegiatan->find($usulanSkpd->kegiatan_id);
            $allKegiatan = array();

            if(count($kegiatanId) > 0)
            {
                $programId  = $kegiatanId->program_id;
                $allKegiatan = $this->kegiatan->getbyProgram($programId);
            }else{
                $programId = 0;
            }
            

        }elseif($role == 3 || $role == 2){
            $skpd = $this->skpd->listofSkpd();
            $program = $this->program->getbySkpd(0,$this->session->userdata('set_tahun'));
            $item = $this->usulanbansos->find($id);

            $usulanSkpd = $this->usulanskpdbansos->getByUsulan($id);
            $jenis_belanja = $usulanSkpd->jenis_belanja;
            $kegiatanId = $this->kegiatan->find($usulanSkpd->kegiatan_id);
            $allKegiatan = array();

            if(count($kegiatanId) > 0)
            {
                $programId  = $kegiatanId->program_id;
                $allKegiatan = $this->kegiatan->getbyProgram($programId);
            }else{
                $programId = 0;
            }
        }else{
            $skpd = $this->skpd->listofSkpd();
            $item = $this->usulanbansos->find($id);
        }

        $content = 'user/bansos/edit';

        if($k < 1)
        {
            $k = $item->kecamatan_id;
        }

        if($jenis_belanja == null)
        {
            $jenis_belanja = 'false';
        }

        $desa = $this->desa->listofDesa($k);
        $data = array(
            'item'      => $item,
            'itemSkpd'  => $usulanSkpd,
            'content'   => $content,
            'skpd'      => $skpd,
            'kecamatan' => $kecamatan,
            'desa'      => $desa,
            'hiban'     => $hiban,
            'k'         => $k,
            'h'         => $h,
            'd'         => $d,
            'col'       => $col,
            'skpd_id'   => $skpd_id,
            'program'   => $program,
            'kegiatan'  => $allKegiatan,
            'src_program'   => $src,
            'jenis_belanja' => $jenis_belanja,
            'prog_id'   => $programId,
            'keg_id'   => $usulanSkpd->kegiatan_id
        );
        // var_dump($data);
        // var_dump($item);
        
        $this->load->view('layouts/layout',$data);
    }

    public function quick($id)
    {

        $data = array(
            'skpd'    => $this->skpd->listofSkpd(),
            'item'    => $this->usulanbansos->find($id),
            'content' => 'user/bansos/quick'
        );

        $this->load->view('layouts/layout',$data);
    }

    public function save()
    {
        $confirm = false;
        $data    = $dataskpd = $dataakhir = array();
        $request = new Request;
        $onsave  = $request->post('acc');
        $date_created = date('Y-m-d H:i:s');
        $id_skpd = $id_akhir = $skp = $adm = 0;
        $id      = $this->usulanbansos->getID();

        if($onsave == 'impor')
        {

            $this->usulanbansos->startTransaction();
            $this->usulanbansos->create($data);
            $this->usulanbansos->completeTransaction();
        }else{
            $data = array(
                'id_usulan_bansos'                => $id,
                'kecamatan_id'                    => $request->post('kecamatan_id'),
                'desa_id'                         => $request->post('desa_id'),
                'users_id'                        => $request->session('id'),
                'nama_penerima'                   => $request->post('nama_penerima'),
                'no_ktp'                          => $request->post('no_ktp'),
                'no_kk'                           => $request->post('no_kk'),
                'alamat_lengkap'                  => $request->post('alamat_lengkap'),
                'tujuan_penggunaan'               => $request->post('tujuan_penggunaan'),
                'rencana_penggunaan'              => $request->post('rencana_penggunaan'),
                'nominal_pengajuan'               => $request->post('nominal_pengajuan'),
                'surat_permohonan_kades'          => $request->post('surat_permohonan_kades'),
                'surat_permohonan_camat'          => $request->post('surat_permohonan_camat'),
                'tahun_terakhir_menerima_bansos'  => $request->post('tahun_terakhir_menerima_bansos'),
                'tanggal_disposisi_bupati'        => $request->post('tanggal_disposisi_bupati'),
                'nomor_rekomendasi'               => $request->post('nomor_rekomendasi'),
                'tanggal_rekomendasi'             => $request->post('tanggal_rekomendasi'),
                'pejabat_penerbitan_rekomendasi'  => $request->post('pejabat_penerbitan_rekomendasi'),
                'tahun_pengajuan'                 => $request->session('set_tahun'),
                'created_at'                      => $date_created,
                'created_by'                      => $this->session->userdata('username'),
                'skpd_id'                         => $request->post('skpd_id'),
                //'status'                          => 0,
                'isi_disposisi_bupati'            => $request->post('isi_disposisi_bupati'),
                'jenis_penggunaan_disertai_satuan'=> $request->post('jenis_penggunaan_disertai_satuan'),
                'free_text'                       => $request->post('free_text')
            );

            /* skpd */
            if($request->session('role') > 0)
            {
                //$id_skpd = $this->usulanskpd->getID();
                $dataskpd = array(
                    'kegiatan_id'                       => $request->post('kegiatan_id'),
                    'users_id'                          => $request->session('id'),
                    'usulan_bansos_id'                  => $id,
                    'keterangan'                        => $request->post('keterangan'),
                    'disetujui'                         => 1,
                    'nominal_disetujui_skpd'            => $request->post('nominal_pengajuan'),
                    'rencana_penggunaan_perubahan_skpd' => $request->post('rencana_penggunaan'),
                    'jenis_belanja'                     => $request->post('jenis_belanja'),
                    'created_at'                        => $date_created,
                    'created_by'                        => $this->session->userdata('username'),
                    'skpd_pelaksana'                    => $request->post('skpd_pelaksana'),


                );

                $skp = 1;

                /* TAPD && admin*/
            }elseif($request->session('role') > 1){
                $adm = 1;
                //$id_akhir = $this->usulanakhir->getID();
                $dataakhir = array(
                    'usulan_skpd_id'            => $id_skpd,
                    'users_id'                  => $request->session('id'),
                    'keterangan'                => $request->post('keterangan'),
                    'disetujui'                 => 1,
                    'nominal_disetujui_akhir'   => $request->post('nominal_pengajuan'),
                    'rencana_penggunaan_akhir'  => $request->post('rencana_penggunaan'),
                    'created_at'                => $date_created,
                    'created_by'                => $this->session->userdata('username')
                );
            }

            //$this->usulanbansos->startTransaction();
            $this->db->trans_strict(FALSE);
            $this->db->trans_begin();
            $this->db->insert('usulan_bansos',$data);
            //$this->usulanbansos->save($data);

            if($skp > 0)
            {
                //$this->usulanskpdbansos->save($dataskpd);
                $this->db->insert('usulan_skpd_bansos',$dataskpd);
            }

            if($adm > 0)
            {
                //$this->usulanakhir->save($dataakhir);
            }

            $confirm = true;
            //$this->usulanbansos->completeTransaction();
            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo "gagal";
            }else{
                $this->db->trans_commit();
                $id_user = $this->session->userdata('id');
                redirect(url('pengajuan/skpd?pilihan_pengajuan=1'));
            }
        }
    }

    public function saveimpor()
    {
       ob_start();
       $this->load->library('reader');

       $request = new Request;

       $data    = $dataSkpd = array();
       $tempKec = $tempDesa = null;
       $kec     = $des = 0;
       $jenis_belanja = $request->post('jenis_belanja');
       $skpd_id      = $request->session('skpd');
       $date_created = date('Y-m-d H:i:s');
       $session_id   = $request->session('id');
       $session_name    = $request->session('username');
       $skpd_pelaksana  = $request->post('skpd_pelaksana');
       $tahun_pengajuan = $request->session('set_tahun');

       $excel = $_FILES['impor'];

       $reader = new Reader();
       $sheet  = $reader->execute($excel['tmp_name']);

       $id = $this->usulanbansos->getID();

       $maxRow = $id+$sheet->highRows+3;

       for($i=2; $i <= $sheet->highRows; $i++)
       {
         if($sheet->getValue(2,$i) <> '')
         {  
            if($tempKec <> $sheet->getValue(5,$i))
            {
                $tempKec = $sheet->getValue(5,$i);
                $dataKecamatan = $this->kecamatan->getByName($tempKec);
                $kec = 0;
                if($dataKecamatan <> false)
                {
                    $kec = $dataKecamatan->id_kecamatan;
                }
            }

            if($tempDesa <>  $sheet->getValue(6,$i))
            {
                $tempDesa = $sheet->getValue(6,$i);
                $dataDesa = $this->desa->getByName($tempDesa,$kec);
                $des = 0;

                if($dataDesa <> false)
                {
                    $des = $dataDesa->id_desa;
                }
            }

                //row 1
            if($i == 2)
            {
                $data = array(
                    'id_usulan_bansos'                => $maxRow,
                    'kecamatan_id'                    => $kec,
                    'desa_id'                         => $des,
                    'users_id'                        => $session_id,
                    'nama_penerima'                   => $sheet->getValue(2,$i),
                    'no_ktp'                          => $sheet->getValue(3,$i),
                    'no_kk'                           => $sheet->getValue(4,$i),
                    'alamat_lengkap'                  => $sheet->getValue(7,$i),
                    'tujuan_penggunaan'               => $sheet->getValue(8,$i),
                    'rencana_penggunaan'              => $sheet->getValue(9,$i),
                    'nominal_pengajuan'               => $sheet->getValue(10,$i),
                    'surat_permohonan_kades'          => $sheet->getValue(13,$i),
                    'surat_permohonan_camat'          => $sheet->getValue(14,$i),
                    'tanggal_disposisi_bupati'        => date('Y-m-d',strtotime($sheet->getValue(18,$i))),
                    'nomor_rekomendasi'               => $sheet->getValue(15,$i),
                    'tanggal_rekomendasi'             => date('Y-m-d',strtotime($sheet->getValue(16,$i))),
                    'pejabat_penerbitan_rekomendasi'  => $sheet->getValue(17,$i),
                    'tahun_terakhir_menerima_bansos'  => $sheet->getValue(12,$i),
                    'tahun_pengajuan'                 => $tahun_pengajuan,
                    'created_at'                      => $date_created,
                    'created_by'                      => $session_name,
                    'skpd_id'                         => $skpd_id,
                    'status'                          => 0,
                    'isi_disposisi_bupati'            => $sheet->getValue(19,$i),
                    'jenis_penggunaan_disertai_satuan'=> $sheet->getValue(11,$i),
                    'free_text'                       => $sheet->getValue(20,$i)
                );

                $dataSkpd = array(
                    'users_id'                          => $session_id,
                    'usulan_bansos_id'                  => $maxRow,
                    'keterangan'                        => '',
                    'disetujui'                         => 1,
                    'nominal_disetujui_skpd'            => $sheet->getValue(10,$i),
                    'rencana_penggunaan_perubahan_skpd' => $sheet->getValue(9,$i),
                    'jenis_belanja'                     => $jenis_belanja,
                    'created_at'                        => $date_created,
                    'created_by'                        => $session_name,
                    'skpd_pelaksana'                    => $skpd_pelaksana,
                    'kegiatan_id'                       => $request->post('kegiatan_id')
                );

                $this->db->trans_strict(FALSE);
                $this->db->trans_begin();
                $this->db->insert('usulan_bansos',$data);
                $this->db->insert('usulan_skpd_bansos',$dataSkpd);

                if ($this->db->trans_status() === FALSE)
                {
                    $this->db->trans_rollback();
                }else{
                    $this->db->trans_commit();
                }

                $data = $dataSkpd = array();
                //row 2
            }else{
                $id = $id+1;
                $data[] = array(
                    'id_usulan_bansos'                => $id,
                    'kecamatan_id'                    => $kec,
                    'desa_id'                         => $des,
                    'users_id'                        => $session_id,
                    'nama_penerima'                   => $sheet->getValue(2,$i),
                    'no_ktp'                          => $sheet->getValue(3,$i),
                    'no_kk'                           => $sheet->getValue(4,$i),
                    'alamat_lengkap'                  => $sheet->getValue(7,$i),
                    'tujuan_penggunaan'               => $sheet->getValue(8,$i),
                    'rencana_penggunaan'              => $sheet->getValue(9,$i),
                    'nominal_pengajuan'               => $sheet->getValue(10,$i),
                    'surat_permohonan_kades'          => $sheet->getValue(13,$i),
                    'surat_permohonan_camat'          => $sheet->getValue(14,$i),
                    'tanggal_disposisi_bupati'        => date('Y-m-d',strtotime($sheet->getValue(18,$i))),
                    'nomor_rekomendasi'               => $sheet->getValue(15,$i),
                    'tanggal_rekomendasi'             => date('Y-m-d',strtotime($sheet->getValue(16,$i))),
                    'pejabat_penerbitan_rekomendasi'  => $sheet->getValue(17,$i),
                    'tahun_terakhir_menerima_bansos'  => $sheet->getValue(12,$i),
                    'tahun_pengajuan'                 => $tahun_pengajuan,
                    'created_at'                      => $date_created,
                    'created_by'                      => $session_name,
                    'skpd_id'                         => $skpd_id,
                    'status'                          => 0,
                    'isi_disposisi_bupati'            => $sheet->getValue(19,$i),
                    'jenis_penggunaan_disertai_satuan'=> $sheet->getValue(11,$i)
                );

                $dataSkpd[] = array(
                    'users_id'                          => $session_id,
                    'usulan_bansos_id'                  => $id,
                    'keterangan'                        => '',
                    'disetujui'                         => 1,
                    'nominal_disetujui_skpd'            => $sheet->getValue(10,$i),
                    'rencana_penggunaan_perubahan_skpd' => $sheet->getValue(9,$i),
                    'jenis_belanja'                     => $jenis_belanja,
                    'created_at'                        => $date_created,
                    'created_by'                        => $session_name,
                    'skpd_pelaksana'                    => $skpd_pelaksana,
                    'kegiatan_id'                       => $request->post('kegiatan_id')
                );
            }
        }
    }

    $this->db->trans_strict(FALSE);
    $this->db->trans_begin();
    $this->db->insert_batch('usulan_bansos',$data);
    $this->db->insert_batch('usulan_skpd_bansos',$dataSkpd);

    if($this->db->trans_status() === TRUE)
    {
        $this->db->trans_commit();
        $filename = date('Y').rand(1111,9999).$excel['name'];
        $file = array(
            'nama_file'         => $filename,
            'created_at'        => date('Y-m-d H:i:s'),
            'created_by'        => $session_name,
            'skpd_id'           => $request->session('skpd'),
            'user_id'           => $session_id,
            'jenis_pengajuan'   => '1'
        );



        $move = $this->db->insert('file_impor',$file);

            //move_uploaded_file($excel['tmp_name'], 'public/files/'.$filename);
        if($move)
        {
            redirect(url('pengajuan/skpd?pilihan_pengajuan=1'));
        }
        ob_end_flush();

    }else {
        $this->db->trans_rollback();
        echo 'Error';
    }
}

function update($id){
    $request = new Request;
    $onsave  = $request->post('acc');
    $date_created = date('Y-m-d H:i:s');
    $users_id=$request->session('id');
    $data = array(
        'kecamatan_id'                    => $request->post('kecamatan_id'),
        'desa_id'                         => $request->post('desa_id'),
        'users_id'                        => $request->session('id'),
        'nama_penerima'                   => $request->post('nama_penerima'),
        'no_ktp'                          => $request->post('no_ktp'),
        'no_kk'                           => $request->post('no_kk'),
        'alamat_lengkap'                  => $request->post('alamat_lengkap'),
        'rencana_penggunaan'              => $request->post('rencana_penggunaan'),
        'nominal_pengajuan'               => $request->post('nominal_pengajuan'),
        'surat_permohonan_kades'          => $request->post('surat_permohonan_kades'),
        'surat_permohonan_camat'          => $request->post('surat_permohonan_camat'),
        'tahun_terakhir_menerima_bansos'  => $request->post('tahun_terakhir_menerima_bansos'),
        'tanggal_disposisi_bupati'        => $request->post('tanggal_disposisi_bupati'),
        'nomor_rekomendasi'               => $request->post('nomor_rekomendasi'),
        'tanggal_rekomendasi'             => $request->post('tanggal_rekomendasi'),
        'pejabat_penerbitan_rekomendasi'  => $request->post('pejabat_penerbitan_rekomendasi'),
        'updated_at'                      => $date_created,
        'updated_by'                      => $this->session->userdata('username'),
        'skpd_id'                         => $request->post('skpd_id'),
        'isi_disposisi_bupati'            => $request->post('isi_disposisi_bupati'),
        'skpd_pemberi_rekomendasi'        => $request->post('skpd_pelaksana'),
        'jenis_penggunaan_disertai_satuan'=> $request->post('jenis_penggunaan_disertai_satuan'),
        'free_text'                       => $request->post('free_text'),
    );

    $dataSkpd = array(
        'kegiatan_id'                     => $request->post('kegiatan_id'),
        'users_id'                        => $request->session('id'),
        'usulan_bansos_id'                => $id,
        'disetujui'                       => 1,
        'nominal_disetujui_skpd'          => $request->post('nominal_pengajuan'),
        'rencana_penggunaan_perubahan_skpd'=> $request->post('rencana_penggunaan'),
        'jenis_belanja'                    => $request->post('jenis_belanja'),
        'updated_at'                      => $date_created,
        'updated_by'                      => $this->session->userdata('username'),
        'skpd_pelaksana'                  => $request->post('skpd_pelaksana'),
    );
    $where=array('id_usulan_bansos'=>$id);
    $where1=array('usulan_bansos_id'=>$id);
    $res=$this->master->update('usulan_bansos',$data,$where);
    $res=$this->master->update('usulan_skpd_bansos',$dataSkpd,$where1);
    if (!$res) {
        redirect(url('pengajuan/skpd?pilihan_pengajuan=1'));
    }else {
        redirect(url('pengajuan/skpd?pilihan_pengajuan=1'));
    }
}

    // public function update($id)
    // {
    //     $confirm = false;
    //     $data    = $dataskpd = $dataakhir = array();
    //     $request = new Request;
    //     $onsave  = $request->post('acc');
    //     $date_created = date('Y-m-d H:i:s');
    //     $id_skpd = $id_akhir = $skp = $adm = 0;
    //     $data = array(
    //             'kecamatan_id'                    => $request->post('kecamatan_id'),
    //             'desa_id'                         => $request->post('desa_id'),
    //             'nama_penerima'                   => $request->post('nama_penerima'),
    //             'no_ktp'                          => $request->post('no_ktp'),
    //             'no_kk'                           => $request->post('no_kk'),
    //             'alamat_lengkap'                  => $request->post('alamat_lengkap'),
    //             'tujuan_penggunaan'               => $request->post('tujuan_penggunaan'),
    //             'rencana_penggunaan'              => $request->post('rencana_penggunaan'),
    //             'nominal_pengajuan'               => $request->post('nominal_pengajuan'),
    //             'surat_permohonan_kades'          => $request->post('surat_permohonan_kades'),
    //             'surat_permohonan_camat'          => $request->post('surat_permohonan_camat'),
    //             'tahun_terakhir_menerima_bansos'  => $request->post('tahun_terakhir_menerima_bansos'),
    //             'tanggal_disposisi_bupati'        => $request->post('tanggal_disposisi_bupati'),
    //             'nomor_rekomendasi'               => $request->post('nomor_rekomendasi'),
    //             'tanggal_rekomendasi'             => $request->post('tanggal_rekomendasi'),
    //             'pejabat_penerbitan_rekomendasi'  => $request->post('pejabat_penerbitan_rekomendasi'),
    //             'tahun_pengajuan'                 => $request->session('set_tahun'),
    //             'updated_at'                      => $date_created,
    //             'updated_by'                      => $this->session->userdata('username'),
    //             'skpd_id'                         => $request->post('skpd_id'),
    //             'isi_disposisi_bupati'            => $request->post('isi_disposisi_bupati'),
    //             'jenis_penggunaan_disertai_satuan'=> $request->post('jenis_penggunaan_disertai_satuan'),
    //             'free_text'                       => $request->post('free_text')
    //         );

    //         /* skpd */
    //         if($request->session('role') > 0)
    //         {
    //             //$id_skpd = $this->usulanskpd->getID();
    //             $dataskpd = array(
    //                 'kegiatan_id'                       => $request->post('kegiatan_id'),
    //                 'keterangan'                        => $request->post('keterangan'),
    //                 //'disetujui'                         => 1,
    //                 'nominal_disetujui_skpd'            => $request->post('nominal_pengajuan'),
    //                 'rencana_penggunaan_perubahan_skpd' => $request->post('rencana_penggunaan'),
    //                 'jenis_belanja'                     => $request->post('jenis_belanja'),
    //                 'updated_at'                        => $date_created,
    //                 'updated_by'                        => $this->session->userdata('username'),
    //                 'skpd_pelaksana'                    => $request->post('skpd_pelaksana')

    //             );

    //             $skp = 1;

    //         /* TAPD && admin*/
    //         }elseif($request->session('role') > 1){
    //             /*$adm = 1;
    //             //$id_akhir = $this->usulanakhir->getID();
    //             $dataakhir = array(
    //                 'usulan_skpd_id'            => $id_skpd,
    //                 'users_id'                  => $request->session('id'),
    //                 'keterangan'                => $request->post('keterangan'),
    //                 'disetujui'                 => 1,
    //                 'nominal_disetujui_akhir'   => $request->post('nominal_pengajuan'),
    //                 'rencana_penggunaan_akhir'  => $request->post('rencana_penggunaan'),
    //                 'updated_at'                => $date_created,
    //                 'updated_by'                => $this->session->userdata('username')
    //             );*/
    //         }

    //         //$this->usulanbansos->startTransaction();
    //         $this->db->trans_strict(FALSE);
    //         $this->db->trans_begin();
    //         $this->db->update('usulan_bansos',$data,array('id_usulan_bansos' => $id));
    //         //$this->usulanbansos->save($data);

    //         if($skp > 0)
    //         {
    //             //$this->usulanskpdbansos->save($dataskpd);
    //             $this->db->update('usulan_skpd_bansos',$dataskpd,array('usulan_bansos_id' => $id));
    //         }

    //         if($adm > 0)
    //         {
    //             //$this->usulanakhir->save($dataakhir);
    //         }

    //         $confirm = true;
    //         //$this->usulanbansos->completeTransaction();

    //         if ($this->db->trans_status() === FALSE)
    //         {
    //             $this->db->trans_rollback();
    //             echo "gagal";
    //         }
    //         else
    //         {
    //             $this->db->trans_commit();
    //             $id_user = $this->session->userdata('id');
    //             redirect(url('pengajuan/skpd?pilihan_pengajuan=1'));
    //         }



    // }

public function updatequick($id)
{
    $confirm = false;
    $data    = $dataskpd = $dataakhir = array();
    $request = new Request;
    $date_created = date('Y-m-d H:i:s');
    $id_skpd = $id_akhir = $skp = $adm = 0;
    $data = array(

        'status'  => $request->post('status')
    );

    /* skpd */
    if($request->session('role') > 0)
    {
                //$id_skpd = $this->usulanskpd->getID();
        $dataskpd = array(

            'disetujui' => $request->post('status')

        );

        $skp = 1;

        /* TAPD && admin*/
    }elseif($request->session('role') > 1){
                /*$adm = 1;
                //$id_akhir = $this->usulanakhir->getID();
                $dataakhir = array(
                    'usulan_skpd_id'            => $id_skpd,
                    'users_id'                  => $request->session('id'),
                    'keterangan'                => $request->post('keterangan'),
                    'disetujui'                 => 1,
                    'nominal_disetujui_akhir'   => $request->post('nominal_pengajuan'),
                    'rencana_penggunaan_akhir'  => $request->post('rencana_penggunaan'),
                    'updated_at'                => $date_created,
                    'updated_by'                => $this->session->userdata('username')
                );*/
            }

            //$this->usulanbansos->startTransaction();
            $this->db->trans_strict(FALSE);
            $this->db->trans_begin();
            $this->db->update('usulan_bansos',$data,array('id_usulan_bansos' => $id));
            //$this->usulanbansos->save($data);

            if($skp > 0)
            {
                //$this->usulanskpdbansos->save($dataskpd);
                $this->db->update('usulan_skpd_bansos',$dataskpd,array('usulan_bansos_id' => $id));
            }

            if($adm > 0)
            {
                //$this->usulanakhir->save($dataakhir);
            }

            $confirm = true;
            //$this->usulanbansos->completeTransaction();
            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo "gagal";
            }
            else
            {
                $this->db->trans_commit();
                $id_user = $this->session->userdata('id');
                redirect(url('pengajuan/skpd?pilihan_pengajuan=1'));
            }



        }

        public function delete($id)
        {
            $this->db->trans_off();
            $this->db->delete('usulan_skpd_bansos',array('usulan_bansos_id' => $id));
            $this->db->delete('usulan_bansos',array('id_usulan_bansos' => $id));

            redirect(url('pengajuan/skpd/?pilihan_pengajuan=1'));
        }

    //fungsi untuk multiple delete
        public function delete_all()
        {
           if($this->input->post('checkbox_value'))
           {
             $id = $this->input->post('checkbox_value');
             for($count = 0; $count < count($id); $count++)
             {
                $this->db->trans_off();
                $this->db->delete('usulan_skpd_bansos',array('usulan_bansos_id' => $id[$count]));
                $this->db->delete('usulan_bansos',array('id_usulan_bansos' => $id[$count]));
            }
        }
    }

    public function ExportExcelBansos()
    {
       ob_start();
       $this->load->library('reader');

       $request = new Request;

       $data    = array();
       $tahun_pengajuan  = $request->post('tahun_pengajuan');
       $peruntukan  = $request->post('peruntukan');
       $kecamatan  = $request->post('kecamatan');
       $desa  = $request->post('desa');
       $kelompok_penerima  = $request->post('kelompok_penerima');
       $opd_rekomendasi  = $request->post('opd_rekomendasi');
       $opd_pelaksana  = $request->post('opd_pelaksana');
       $program  = $request->post('program');
       $kegiatan  = $request->post('kegiatan');
       $sub_kegiatan  = $request->post('sub_kegiatan');
       $session_name    = $request->session('username');
       $date_created = date('Y-m-d H:i:s');
       $jenis_pengajuan="BANSOS";

       $excel = $_FILES['impor'];

       $reader = new Reader();
       $sheet  = $reader->execute($excel['tmp_name']);

       $idbansos = $this->hibahbansos->getIDBansos();

       $maxRow = $idbansos+$sheet->highRows+3;

       for($i=2; $i <= $sheet->highRows; $i++)
       {

        $data= array(
            'id'    =>$this->hibahbansos->getIDBansos(),
            'tahun_pengajuan'          => $tahun_pengajuan,
            'jenis_pengajuan'                => $jenis_pengajuan,
            'kecamatan'                => $kecamatan,
            'desa'                => $desa,
            'peruntukan'        => $peruntukan,
            'kelompok_penerima'                => $kelompok_penerima,
            'opd_rekomendasi'       => $opd_rekomendasi,
            'opd_pelaksana'       => $opd_pelaksana,
            'program'       => $program,
            'kegiatan'       => $kegiatan,
            'sub_kegiatan'       => $sub_kegiatan,
            'uraian_keg_satuan'                => $sheet->getValue(1, $i),
            'penerima'                => $sheet->getValue(2, $i),
            'pimpinan'                => $sheet->getValue(3, $i),
            'bhi'                => $sheet->getValue(4, $i),
            'alamat'                => $sheet->getValue(5, $i),
            'nominal'                => $sheet->getValue(6, $i),
            'nik'                => $sheet->getValue(7, $i),
            'kk'                => $sheet->getValue(8, $i),
            'tahun_terakhir_menerima'                => $sheet->getValue(9, $i),
            'tanggal_permohonan'                => date('Y-m-d',strtotime($sheet->getValue(10,$i))),
            'nomor_permohonan'                => $sheet->getValue(11, $i),
            'nomor_penerbitan_rekomendasi'                => $sheet->getValue(12, $i),
            'pejabat_penerbitan_rekomendasi'                => $sheet->getValue(13, $i),
            'tanggal_penerbitan_rekomendasi'                => date('Y-m-d',strtotime($sheet->getValue(14,$i))),
            'tanggal_disposisi_bupati'                => date('Y-m-d',strtotime($sheet->getValue(15,$i))),
            'tanggal_pertimbangan_ketua_tapd'                => date('Y-m-d',strtotime($sheet->getValue(16,$i))),
            'isi_disposisi_ketua_tapd'                => $sheet->getValue(17, $i),
            'created_by'        =>$session_name,
            'created_at'        =>$date_created
        );

        $this->db->trans_strict(FALSE);
        $this->db->trans_begin();
        $this->db->insert('bansos_2022',$data);
        // $res=$this->hibahbansos->simpan('bansos_2022',$data);
    }
}

}