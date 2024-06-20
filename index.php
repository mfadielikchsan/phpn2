<?php
require_once 'get_data.php';
$kampus = getKampus();

// Set judul halaman
$pageTitle = "Home";

// Konten halaman dimulai di sini
ob_start();
?>
<div class="container mt-5">
    <h1>User</h1>
    <div class="col-md-12 mt-3">
        <button type="button" class="btn btn-primary mb-3" onclick="tambahData()"><i class="fas fa-plus"></i> Tambah Data</button>
        <table id="tblMaster" class="table table-striped">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Hobi</th>
                    <th>Kampus</th>
                    <th>Active</th>
                    <th>Edit</th>
                </tr>
            </thead>
            <tbody>
                <!-- Isi tabel akan diisi dengan data dari DataTables -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal_form">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_formLabel"><span id="judulmodal"></span> User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="form_id">
                <input type="hidden" id="primarykey" name="primarykey">
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="nama" class="col-sm-3 col-form-label">Nama</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="nama" name="nama" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="hobi" class="col-sm-3 col-form-label">Hobi</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="hobi" name="hobi" autocomplete="off" required>
                        </div>
                    </div>
                    <?php if(count($kampus) > 0) {?>
                        <div class="form-group row">
                            <label for="kampus" class="col-sm-3 col-form-label">Kampus</label>
                            <div class="col-sm-9">
                                <select name="kampus" id="kampus" class="form-control select2" style="width:100%" required>
                                    <option value=""></option>
                                    <?php
                                    foreach ($kampus as $k) {
                                    ?>
                                        <option value="<?= $k['id'] ?>"><?= $k['nama_kampus'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group row">
                        <label for="active" class="col-sm-3 col-form-label">Active</label>
                        <div class="col-sm-9">
                            <select name="active" id="active" class="form-control select2" style="width:100%" required>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// Simpan konten ke dalam variabel $content
$content = ob_get_clean();

// Skrip khusus halaman ini
ob_start();
?>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select"
        });

        $('#tblMaster').DataTable({
            "ajax": {
                "url": "get_users.php",
                "type": "GET",
                "dataSrc": ""
            },
            "columns": [
                { "data": "nama", "class":"dt-center" },
                { "data": "hobi", "class":"dt-center" },
                { "data": "nama_kampus", "class":"dt-center" },
                { "data": "ketaktif", "class":"dt-center" },
                { "data": "btn", "class":"dt-center" },
            ],
            "error": function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!'
                });
            }
        });

        document.getElementById("form_id").addEventListener("submit", (e) => {
            e.preventDefault()
            validate()
        })
    });

    function tambahData() {
        $("#judulmodal").text("Tambah");
        $('#primarykey').val('');
        $('#nama').val('');
        $('#hobi').val('');
        $('#kampus').val('').change();
        $('#active').val('1').change();
        $('#modal_form').modal('show');
    }

    function edit(d) {
        $("#judulmodal").text("Edit");
        $('#primarykey').val(d.id);
        $('#nama').val(d.nama);
        $('#hobi').val(d.hobi);
        $('#kampus').val(d.id_kampus).change();
        $('#active').val(d.active).change();
        $('#modal_form').modal('show');
    }

    function validate() {
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin mengirimkan formulir?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then(function(result) {
            if (result.value) {
                let form = $('#form_id')[0];
                let formData = new FormData(form);
                $("#loading").show();
                $.ajax({
                    type: "POST",
                    url: "store.php",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $("#loading").hide();
                        Swal.fire(response.head, response.msg, response.status);
                        $('#modal_form').modal('hide');
                        $('#tblMaster').DataTable().ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        $("#loading").hide();
                        Swal.fire('Error', 'Terjadi kesalahan saat mengirim data: ' + error, 'error');
                    }
                });
            }
        });
    }
</script>
<?php
$customScripts = ob_get_clean();

// Menyertakan template.php untuk menampilkan halaman lengkap
include 'template.php';
?>
