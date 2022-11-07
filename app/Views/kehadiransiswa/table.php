<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
            rel="stylesheet" crosorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.6.1.min.js"
            crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
            crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
            crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/gh/agoenxz2186/submitAjax@develop/submit_ajax.js"
            ></script>
            <link href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet"> 
            <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

            <div class="container">
                <button class="float-end btn btn-sm btn-primary" id="btn-tambah">Tambah</button>
                <table id='table-kehadiransiswa' class="datatable table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Waktu Pelajaran</th>
                            <th>Siswa</th>
                            <th>Status Hadir</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <div id="modalForm" class="modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Kehadiran Siswa</h4>
                            <button class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="formKehadiranSiswa" method="post" action="<?=base_url('kehadiransiswa') ?>">
                            <input type="hidden" name="id" />
                            <input type="hidden" name="_method" />
                           
                            <div class="mb-3">
                                <label class="form-label">Waktu Pelajaran</label>
                                <select name="kehadiran_guru_id" class="form-control">
                                <?php
                                        use App\Models\KehadiranguruModel;


                                        $r = (new KehadiranguruModel())->findAll();
                                        
                                        foreach($r as $k){
                                            echo "<option value='{$k['id']}'>{$k['waktu_masuk']} - 
                                            {$k['waktu_keluar']}</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Nama Siswa</label>
                                <select name="siswa_id" class="form-control">
                                <?php
                                        use App\Models\SiswaModel;


                                        $r = (new SiswaModel())->findAll();
                                        
                                        foreach($r as $k){
                                            echo "<option value='{$k['id']}'>{$k['nis']} - 
                                            {$k['nama_depan']}</option>";
                                        }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status Hadir</label>
                                <select name="status_hadir" class="form-control">
                                    <option>Kehadiran Siswa</option>
                                    <option value="Y">Hadir</option>
                                    <option value="T">Tidak Hadir</option>
                                </select>
                            </div>

                        </form>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-success" id="btn-menambahkan" >Menambahkan</button>
                        </div>
                    </div>
                </div>
            </div>

<script>
    $(document).ready(function(){
        
        
        $('form#formKehadiranSiswa').submitAjax({
        pre:()=>{
            $('button#btn-menambahkan').hide();
            
        },
        pasca:()=>{
            $('button#btn-menambahkan').show();

        },

        success:(response, status)=>{
            $("#modalForm").modal('hide');
            $("table#table-kehadiransiswa").DataTable().ajax.reload();
        },

        error:(xhr, status)=>{
            alert('Maaf data salah');
        }

        });


        $('button#btn-menambahkan').on('click' , function(){
            $('form#formKehadiranSiswa').submit();

        });


        $('button#btn-tambah').on('click' , function(){
            $('#modalForm').modal('show');
            $('form#formKehadiranSiswa').trigger('reset');
            $('input[name=_method]').val('');
        });

        $('table#table-kehadiransiswa').on('click', '.btn-light', function (){
            let id = $(this).data('id');
            let baseurl = "<?=base_url()?>";
            $.get(`${baseurl}/kehadiransiswa/${id}`).done((e)=>{
                $('input[name=id]').val(e.id);
                $('input[name=kehadiran_guru_id]').val(e.kehadiran_guru_id);
                $('input[name=siswa_id]').val(e.siswa_id);
                $('input[name=status_hadir]').val(e.status_hadir);
                $('#modalForm').modal('show');
                $('input[name=_method]').val('patch');

            });
        });

        $('table#table-kehadiransiswa').on('click', '.btn-danger', function (){
            let konfirmasi = confirm ('yakin hapus data?');
            if(konfirmasi === true){
                let _id = $(this).data('id');
                let baseurl = "<?=base_url()?>";


                $.post(`${baseurl}/kehadiransiswa`, {id:_id, _method:'delete'}).done(function(e){
                    $('table#table-kehadiransiswa').DataTable().ajax.reload();
                });
            }
        });


        $('table#table-kehadiransiswa').DataTable({
            processing: true,
            serverSide: true,
            ajax:{
                url: "<?=base_url('kehadiransiswa/all')?>",
                method: 'GET'
            },
            columns:[
                {data: 'id',sortable:false, searchable:false,
                    render: (data,type,row,meta)=>{
                        return meta.settings._iDisplayStart + meta.row + 1;
                    }
                },
                {data: 'waktu_masuk', render:(data,type,row,meta)=>{
                    return `${data} - ${row['waktu_keluar']}`;
                }},
                {data: 'nis', render:(data,type,row,meta)=>{
                    return `${data} - ${row['nama_depan']}`;
                }},
                
                {data: 'status_hadir',
                    render: (data,type,row,meta)=>{
                        if(data === 'Y'){
                            return 'Hadir';
                        }
                        else if(data === 'T'){
                            return 'Tidak Hadir';
                        }
                        return data;
                    }
                },

                {data: 'id',
                    render: (data,type,meta,row)=>{
                        var btnEdit     = `<button class= 'btn-light' data-id='${data}'> Edit</button>`;
                        var btnHapus    = `<button class = 'btn-danger'data-id='${data}'> Hapus </button>`;
                        return btnEdit + btnHapus;
                    }

                },
            ]
        });
    });
</script>