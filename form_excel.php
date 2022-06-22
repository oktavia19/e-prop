<div class="container-fluid">
    <!-- OVERVIEW -->
    <div class="panel panel-headline">
        <div class="panel-heading">
            <h3 class="panel-title">Pengajuan</h3>
            <p class="panel-subtitle">form pengajuan hibah dan bansos</p>
            <br />
            <a class="btn btn-default" href="<?= url('pengajuan') ?>"><i class="lnr lnr-arrow-left-circle"></i></a>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <form action="<?= url('bansos/uploadexcel') ?>" method='POST' enctype="multipart/form-data">
                        <input type="hidden" name="acc" value="impor" />
                        <div class="alert alert-danger alert-i" style="display: none"></div>
                        <div class="form-group">
                            <label>Pilih Jenis Pengajuan</label>
                            <select name="jenis_pengajuan" class="form-control">
                                <option value="HIBAH">Hibah</option>
                            </select>
                        </div>
                        <div class="form-group ">
                            <label>Tahun Pengajuan</label>
                            <?= form_dropdown('tahun_pengajuan',Factory::tahun('Belum Pernah',2010,date('Y')),0,"class='form-control'") ?>
                        </div>
                        <div class="form-group">
                            <label>Jenis</label>
                            <?= form_dropdown('peruntukan',array('uang'=>'Uang','barang'=>'Barang','jasa'=>'Jasa'),0,"class='form-control  perutukan'") ?>
                        </div>
                        <div class="form-group">
                            <label>Kelompok Penerima</label>
                            <?= form_dropdown('kelompok_penerima',array('kelompok_masyarakat'=>'Kelompok Masyarakat','perseorangan'=>'Perseorangan'),0,"class='form-control  kelompok_penerima'") ?>
                        </div>
                        <div class="form-group">
                            <label>Kecamatan</label>
                            <select class="form-control" name="kecamatan" id="kecamatan" required>
                                <option value disabled selected="">-PILIH-</option>
                                <?php foreach($kecamatan->result() as $row):?>
                                    <option value="<?php echo $row->id_kecamatan;?>"><?php echo $row->nama_kecamatan;?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Desa</label>
                            <select class="form-control" name="desa" id="desa" required>
                                <option value="">-PILIH-</option>
                            </select>
                        </div>
                        <div class="form-group ">
                            <label>Program</label>
                            <select class="form-control" name="program" id="program" required>
                                <option value disabled selected="">-PILIH-</option>
                                <?php foreach($program->result() as $row):?>
                                    <option value="<?php echo $row->id_program;?>"><?php echo $row->program;?></option>
                                <?php endforeach;?>
                            </select>
                        </div>

                        <div class="form-group ">
                            <label>Kegiatan</label>
                            <select class="form-control" name="kegiatan" id="kegiatan">
                                <option value="">-PILIH-</option>
                            </select>
                        </div>

                        <div class="form-group ">
                            <label>Sub Kegiatan</label>
                            <select class="form-control" name="sub_kegiatan" id="sub_kegiatan">
                                <option value="">-PILIH-</option>
                            </select>
                        </div>
                        <div class="form-group ">
                            <label>OPD yang Memberi Rekomendasi</label>
                            <select class="form-control" name="opd_rekomendasi" id="opd_rekomendasi" required>
                                <option value disabled selected="">-PILIH-</option>
                                <?php foreach($skpd->result() as $row):?>
                                    <option value="<?php echo $row->id_skpd;?>"><?php echo $row->nama_skpd;?></option>
                                <?php endforeach;?>
                            </select>
                        </div>

                        <div class="form-group ">
                            <label>OPD Pelaksana</label>
                            <select class="form-control" name="opd_pelaksana" id="opd_pelaksana" required>
                                <option value disabled selected="">-PILIH-</option>
                                <?php foreach($skpd->result() as $row):?>
                                    <option value="<?php echo $row->id_skpd;?>"><?php echo $row->nama_skpd;?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>File Usulan (Format Excel 2007 .xlsx)</label>
                            <input type="file" name="impor" class="form-control " />
                        </div>
                        <button class="btn btn-primary">Simpan</button>
                        <button class="btn btn-danger" type="button">Batal</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-2.0.2.min.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap.js')?>"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#kecamatan').change(function(){
            var id=$(this).val();
            $.ajax({
                url : "<?php echo base_url();?>hibahbansos/get_desa",
                method : "POST",
                data : {id: id},
                async : false,
                dataType : 'json',
                success: function(data){
                    var html = '';
                    var i;
                    for(i=0; i<data.length; i++){
                        html += '<option value="'+data[i].id_desa+'">'+data[i].nama_desa+'</option>';
                    }
                    $('#desa').html(html);

                }
            });
        });
    });

    $('#program').change(function(){
        var id=$(this).val();
        $.ajax({
            url : "<?php echo base_url();?>hibahbansos/get_kegiatan",
            method : "POST",
            data : {id: id},
            async : false,
            dataType : 'json',
            success: function(data){
                var html = '';
                var i;
                for(i=0; i<data.length; i++){
                    html += '<option value="'+data[i].id_kegiatan+'">'+data[i].kegiatan+'</option>';
                }
                $('#kegiatan').html(html);

            }
        });
        //tambahan
        var id=$('#kegiatan').val();
        $.ajax({
            url : "<?php echo base_url();?>hibahbansos/get_sub_kegiatan",
            method : "POST",
            data : {id: id},
            async : false,
            dataType : 'json',
            success: function(data){
                var html = '';
                var i;
                for(i=0; i<data.length; i++){
                    html += '<option value="'+data[i].id_sub_keg+'">'+data[i].sub_kegiatan+'</option>';
                }
                $('#sub_kegiatan').html(html);

            }
        });
    });

    $('#kegiatan').change(function(){
        var id=$(this).val();
        $.ajax({
            url : "<?php echo base_url();?>hibahbansos/get_sub_kegiatan",
            method : "POST",
            data : {id: id},
            async : false,
            dataType : 'json',
            success: function(data){
                var html = '';
                var i;
                for(i=0; i<data.length; i++){
                    html += '<option value="'+data[i].id_sub_keg+'">'+data[i].sub_kegiatan+'</option>';
                }
                $('#sub_kegiatan').html(html);

            }
        });
    });

</script>

