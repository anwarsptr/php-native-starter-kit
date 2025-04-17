<?php
require_once DIR_PATH . 'init.php';

if (!empty($_GET['proses'])) {
  include "_proses.php"; exit;
}

$showAdd = checkPermission('buku-tamu-add', true);
$showEdit = checkPermission('buku-tamu-edit', true);
$showDetail = checkPermission('buku-tamu-detail', true);
$showDelete = checkPermission('buku-tamu-delete', true);

$title = "Buku Tamu";

include $dir_users."/includes/header.php";

$url_proses = "buku-tamu?proses=";
?>
<section class="content-header">
  <h1> <i class="fa fa-book"></i> <?= $title ?> <small></small>
  </h1>
  <ol class="breadcrumb">
    <li>
      <a href="dashboard"> <i class="fa fa-dashboard"></i> Dashboard </a>
    </li>
    <li class="active"> <i class="fa fa-book"></i> <?= $title ?></li>
  </ol>
</section>
<section class="content">

  <div class="row">
    <div class="col-xs-12">
      <div class="nav-tabs-custom border-radius">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab_1" onclick="showData(0)" data-toggle="tab"> <i class="fa fa-spin fa-spinner text-warning"></i> Belum kembalikan Tanda Masuk</a></li>
          <li><a href="#tab_2" onclick="showData(1)" data-toggle="tab"> <i class="fa fa-check text-success"></i> Sudah kembalikan Tanda Masuk</a></li>
          <?php if ($showAdd): ?>
            <li class="pull-right">
              <button type="button" id="createNew" class="btn btn-primary btn-sm border-radius pull-right" style="margin:10px;">
                <i class="fa fa-plus" ></i>&nbsp; Add <?= $title ?>
              </button>
            </li>
          <?php endif; ?>
        </ul>
        <div class="tab-content table-responsive">
          <table id="fileData" class="table table-bordered table-striped" cellspacing="0" width="100%">
            <tr><td><i class="fa fa-spin fa-spinner text-warning"></i> Loading . . .</td></tr>
          </table>
        </div>
      </div>
    </div>
  </div>

</section>

<?php
if ($showAdd || $showEdit) :
  include "modal_form.php";
endif;
if ($showDetail) :
  include "modal_detail.php";
endif;

include $dir_users."/includes/footer.php";

include $dir_users."/includes/components/dataTables.php";
include $dir_users."/includes/components/select2.php";
include $dir_users."/includes/components/datepicker.php";
include $dir_users."/includes/components/timepicker.php";
include $dir_users."/includes/components/iCheck.php";
?>

<script type="text/javascript">
$(document).ready(function () {
  showData(0);
  $("#tgl_kunjungan").datepicker({ format: 'dd-mm-yyyy', autoclose: true, });
  $('.timepicker').timepicker({
      showInputs: false,
      showMeridian: false,
      showSeconds: false
  });
});

$('input[type="checkbox"].flat-checkbox').iCheck({
  checkboxClass: 'icheckbox_flat-green'
});
$('.flat-checkbox').on('ifChanged', function(event){
    $(this).val($(this).prop('checked') ? 1:0);
});


var tab=0;
async function showData(stt=0)
{
  tab=stt;
  columnWidths = ['10', '10', '10', '10', '10', '15'];
  columnHeads = ['Foto Kendaraan', 'Nomor', 'Tanggal Kunjungan', 'Jam Kunjungan', 'Nomor Kendaraan', 'Nama yang dikunjungi'];
  columns = ['foto_kendaraan', 'nomor', 'tgl_kunjungan', 'jam_kunjungan', 'nomor_kendaraan', 'nama_yang_dikunjungi'];
  await prosesDatatable({
    url: '<?= $url_proses ?>get&status='+stt,
    columnHeads: columnHeads,
    columnWidths: columnWidths, //Persen
    columns: columns,
    columnDefs: [
      {
        className: "uk-text-center", "targets": 1,
        render: function (data, type, row, meta) {
          if (data && data!="[]") {
            return `<a href="#" class="btn btn-info btn-xs detailButton" data-id="${row.id}" data-action="foto"><i class="fa fa-image"></i> Lihat</a>`;
          }
          return '';
        }
      }
    ],
    order:[[3, 'desc'],[4, 'desc']],
    actions: function(data, type, row) {
      id=data.id; btnAksi='';
      <?php if ($showDetail) : ?>
        btnAksi += `<a href="javascript:void(0)" data-placement="top" data-toggle="tooltip"
              class="btn btn-info btn-xs detailButton m3px"
              data-id="${id}" title="Detail">
              <i class="fa fa-list"></i>
          </a>`;
      <?php endif; ?>
      <?php if ($showEdit) : ?>
        btnAksi += `<a href="javascript:void(0)" data-placement="top" data-toggle="tooltip"
              class="btn btn-success btn-xs editButton m3px"
              data-id="${id}" title="Edit">
              <i class="fa fa-edit"></i>
          </a>`;
      <?php endif; ?>
      <?php if ($showDelete) : ?>
          btnAksi += `<a href="javascript:void(0)" data-placement="top" data-toggle="tooltip"
            class="btn btn-danger btn-xs deleteButton m3px"
            data-id="${id}" title="Delete">
            <i class="fa fa-trash"></i>
          </a>`;
      <?php endif; ?>
      return btnAksi;
    }
  });
}


$(function () {

  <?php if ($showAdd) : ?>
    /* Modal Create */
   $('#createNew').click(async function () {
       $('#modelHeading').html("Add Buku Tamu");
       $('#dataForm').trigger("reset");
       $('#form_id').val('');
       $('#no_tanda_masuk_dikembalikan').val(0).iCheck('update');
       $('#no_tanda_masuk_dikembalikan').prop('checked', false).iCheck('update');
       $('.form-control.select2').select2();
       $('#dataForm').parsley().reset();

       let today = new Date();
       let day = String(today.getDate()).padStart(2, '0');
       let month = String(today.getMonth() + 1).padStart(2, '0');
       let year = today.getFullYear();
       let formattedDate = day + '-' + month + '-' + year;
       // $('#tanggal').val(formattedDate);
       $('#tgl_kunjungan').datepicker('setDate', formattedDate);
       hours = today.getHours() < 10 ? '0'+today.getHours():today.getHours();
       minutes = today.getMinutes() < 10 ? '0'+today.getMinutes():today.getMinutes();
       $('#jam_kunjungan').val(hours +':'+minutes);
       await getNewNomor();
       form_disabled('dataForm', false, 'all');
       $('select').attr('required', true);
       onChangeMembawaKendaraan();
       $('#vno_tanda_masuk_dikembalikan').hide();
       $('#ajaxModal').modal('show');
   });

   async function getNewNomor() {
     await prosesSubmit({
       csrf: true, method: "GET",
       url: "<?= $url_proses ?>generate-number&name=buku-tamu",
       callbackSuccess: function(response) {
         $('#nomor').val(response.message);
       }
     });
   }
  <?php endif; ?>

  <?php if ($showEdit) : ?>
  /* Modal Edit */
  $('body').on('click', '.editButton', async function () {
      var id = $(this).data('id');
      await prosesSubmit({
        csrf: true, method: "GET",
        url: "<?= $url_proses ?>edit&id=" + id,
        callbackSuccess: function(response) {
          data = response.message;
          $('#modelHeading').html("Edit Buku Tamu");
          $('#form_id').val(data.id);
          $('#nomor').val(data.nomor);
          $('#tgl_kunjungan').datepicker('setDate', data.tgl_kunjungan ? format_tglnya(data.tgl_kunjungan, 'dd-mm-yyyy') : '');
          $('#jam_kunjungan').val(data.jam_kunjungan.substring(0, 5));
          $('#input_by').val(data.created_by);
          $('#nama_tamu').val(data.nama_tamu);
          $('#no_telp_tamu').val(data.no_telp_tamu);
          $('#membawa_kendaraan').val(data.membawa_kendaraan);
          $('#tipe_tamu_id').val(data.tipe_tamu_id);
          $('#gate_masuk').val(data.gate_masuk);
          $('#jenis_identitas_id').val(data.jenis_identitas_id);
          $('#tipe_kedatangan_id').val(data.tipe_kedatangan_id);
          $('#blok_perumahan_id').val(data.blok_perumahan_id);
          $('#nama_yang_dikunjungi').val(data.nama_yang_dikunjungi);
          $('#no_tanda_masuk').val(data.no_tanda_masuk);
          $('#no_tanda_masuk_dikembalikan').val(data.no_tanda_masuk_dikembalikan).iCheck('update');
          $('#no_tanda_masuk_dikembalikan').prop('checked', data.no_tanda_masuk_dikembalikan==1?true:false).iCheck('update');
          $('#keterangan').val(data.keterangan);
          $('#foto_tanda_pengenal').val('');
          $('#foto_kendaraan').val('');
          $('.form-control.select2').select2();
          $('#dataForm').parsley().reset();
          onChangeMembawaKendaraan();
          $('#foto_tanda_pengenal').removeAttr('required');
          $('#r_foto_tanda_pengenal').hide();
          $('#foto_kendaraan').removeAttr('required');
          $('#r_foto_kendaraan').hide();
          if (data.nomor_kendaraan != "") {
            no_kendaraan = data.nomor_kendaraan.split(" ");
            if (no_kendaraan[0]) {
              $('#no1').val(no_kendaraan[0]);
            }
            if (no_kendaraan[1]) {
              $('#no2').val(no_kendaraan[1]);
            }
            if (no_kendaraan[2]) {
              $('#no3').val(no_kendaraan[2]);
            }
          }
          form_disabled('dataForm', true, 'all');
          form_disabled('no_tanda_masuk_dikembalikan', false);
          $('#saveBtn').removeAttr('disabled');
          $('#vno_tanda_masuk_dikembalikan').show();
          $('select').removeAttr('required');
          $('#ajaxModal').modal('show');
        }
      });
  });
  <?php endif; ?>

  <?php if ($showAdd || $showEdit) :
    $maxUpload = maxUploadFile('buku-tamu') * 1000;
  ?>
  const maxPhotos = 10; // Maksimal jumlah foto
  const maxSize = <?= $maxUpload ?> * 1024; // Maksimal ukuran file (KB)
  const photoInput = document.getElementById('foto_tanda_pengenal');
  const photoInput2 = document.getElementById('foto_kendaraan');
  // Event listener untuk menghitung jumlah foto
  photoInput.addEventListener('change', () => {
      const files = Array.from(photoInput.files); // Konversi FileList ke array
      const totalPhotos = files.length; // Hitung jumlah file

      if (totalPhotos > maxPhotos) {
          swalResponse('warning', `Anda hanya bisa mengunggah maksimal <b>${maxPhotos} foto</b>.`);
          photoInput.value="";
          return;
      }

      const oversizedFiles = files.filter(file => file.size > maxSize);
      if (oversizedFiles.length > 0) {
          swalResponse('warning', `Beberapa file melebihi ukuran maksimal <b><?= $maxUpload ?> KB</b>: ${oversizedFiles.map(f => f.name).join(', ')}`);
          photoInput.value="";
          return;
      }
  });

  // Event listener untuk menghitung jumlah foto
  photoInput2.addEventListener('change', () => {
      const files = Array.from(photoInput2.files); // Konversi FileList ke array
      const totalPhotos = files.length; // Hitung jumlah file

      if (totalPhotos > maxPhotos) {
          swalResponse('warning', `Anda hanya bisa mengunggah maksimal <b>${maxPhotos} foto</b>.`);
          photoInput2.value="";
          return;
      }

      const oversizedFiles = files.filter(file => file.size > maxSize);
      if (oversizedFiles.length > 0) {
          swalResponse('warning', `Beberapa file melebihi ukuran maksimal <b><?= $maxUpload ?> KB</b>: ${oversizedFiles.map(f => f.name).join(', ')}`);
          photoInput2.value="";
          return;
      }
  });

  $('#dataForm').submit(async function (e) {
      e.preventDefault();
      form='dataForm';
      var fd = new FormData($('#'+form)[0]);
      $('#'+form+' *').each(function(key, field) {
        var field_name = field.name;
        if ($('[name="'+field_name+'"]').length!=0) {
          if ($('[name="'+field_name+'"] required').val() == '') {
            return false;
          }
          fd.append(field_name, $('[name="'+field_name+'"]').val());
        }
      });
      url = "<?= $url_proses ?>save";
      await prosesSubmit({
        csrf: true, method:"POST", form:form, url:url, fd:fd,
        callbackSuccess: function(data) {
          swalResponse('success', data.message, false, 'x');
          $('#ajaxModal').modal('hide');
          setTimeout(function () { showData(tab); }, 2500);
        }
      });
  });
  <?php endif; ?>

  <?php if ($showDetail) : ?>
  /* Modal Detail */
  $('body').on('click', '.detailButton', async function () {
      var id = $(this).data('id');
      var action = $(this).data('action');
      await prosesSubmit({
        csrf: true, method: "GET",
        url: "<?= $url_proses ?>detail&id=" + id,
        callbackSuccess: function(response) {
          data = response.message;
          $('#modelHeadingDetail').html("Detail Buku Tamu");
          $('#d_nomor').html(data.nomor);
          $('#d_tgl_kunjungan').html(data.tgl_kunjungan ? format_tglnya(data.tgl_kunjungan, 'dd-mm-yyyy') : '');
          $('#d_jam_kunjungan').html(data.jam_kunjungan.substring(0, 5));
          $('#d_nama_tamu').html(data.nama_tamu);
          $('#d_no_telp_tamu').html(data.no_telp_tamu);
          $('#d_membawa_kendaraan').html(data.membawa_kendaraan);
          $('#d_nomor_kendaraan').html(data.nomor_kendaraan);
          $('#d_tipe_tamu').html(data.tipe_tamu);
          $('#d_gate_masuk').html(data.gate_masuk);
          $('#d_jenis_identitas').html(data.jenis_identitas);
          $('#d_tipe_kedatangan').html(data.tipe_kedatangan);
          $('#d_blok_perumahan').html(data.blok_perumahan);
          $('#d_nama_yang_dikunjungi').html(data.nama_yang_dikunjungi);
          $('#d_no_tanda_masuk').html(data.no_tanda_masuk);
          $('#d_no_tanda_masuk_dikembalikan').html(data.no_tanda_masuk_dikembalikan==1 ? 'Sudah':'Belum');
          $('#d_keterangan').html(data.keterangan);

          var dataFotoTandaPengenal = data.foto_tanda_pengenal && data.foto_tanda_pengenal!="[]" ? JSON.parse(data.foto_tanda_pengenal):"";
          dFotoTandaPengenal = $('#d_foto_tanda_pengenal tbody');
          dFotoTandaPengenal.html('');
          if (dataFotoTandaPengenal) {
            // $('#d_foto_tanda_pengenal').html(`<a href="${data.foto_tanda_pengenal}" class="btn btn-info btn-xs" target="_blank"><i class="fa fa-image"></i> Lihat</a>`);
            $('#v_foto_tanda_pengenal').show();
            dataFotoTandaPengenal.forEach((item, index) => {
                no = index + 1;
                const row = `
                    <tr id="foto_tanda_pengenal_${id}_${no}">
                        <td>${no}</td>
                        <td><a href="${item}" target="_blank"><img src="${item}" alt="Gambar ${no}" style="width: auto; height: 100px;"></a></td>
                        <?php if ($showDelete) { ?>
                        <td><button class="btn btn-danger btn-xs deleteImgButton" data-id="${id}" data-img="${item}" data-no="${no}" data-tipe="tanda_pengenal"><i class="fa fa-trash"></i></button></td>
                        <?php } ?>
                    </tr>
                `;
                dFotoTandaPengenal.append(row);
            });
          }else {
            // $('#d_foto_tanda_pengenal').html('-');
            $('#v_foto_tanda_pengenal').hide();
          }

          var dataFotoKendaraan = data.foto_kendaraan && data.foto_kendaraan!="[]" ? JSON.parse(data.foto_kendaraan):"";
          dFotoKendaraan = $('#d_foto_kendaraan tbody');
          dFotoKendaraan.html('');
          if (dataFotoKendaraan) {
            // $('#d_foto_kendaraan').html(`<a href="${data.foto_kendaraan}" class="btn btn-info btn-xs" target="_blank"><i class="fa fa-image"></i> Lihat</a>`);
            $('#v_foto_kendaraan').show();
            dataFotoKendaraan.forEach((item, index) => {
                no = index + 1;
                const row = `
                    <tr id="foto_kendaraan_${id}_${no}">
                        <td>${no}</td>
                        <td><a href="${item}" target="_blank"><img src="${item}" alt="Gambar ${no}" style="width: auto; height: 100px;"></a></td>
                        <?php if ($showDelete) { ?>
                        <td><button class="btn btn-danger btn-xs deleteImgButton" data-id="${id}" data-img="${item}" data-no="${no}" data-tipe="kendaraan"><i class="fa fa-trash"></i></button></td>
                        <?php } ?>
                    </tr>
                `;
                dFotoKendaraan.append(row);
            });
          }else {
            // $('#d_foto_kendaraan').html('-');
            $('#v_foto_kendaraan').hide();
          }

          $('#d_created_at').html(data.created_at ? format_tglnya(data.created_at, 'waktu') : '-');
          $('#d_created_by').html(data.created_by ? data.created_by : '-');
          $('#d_updated_at').html(data.updated_at ? format_tglnya(data.updated_at, 'waktu') : '-');
          $('#d_updated_by').html(data.updated_by ? data.updated_by : '-');
          $('#ajaxModalDetail').modal('show');
          if (action=='foto') {
            setTimeout(function() {
              $('#ajaxModalDetail').scrollTop($('#v_foto_kendaraan').offset().top - $('#ajaxModalDetail').offset().top);
            }, 1000);
          }
        }
      });
  });
  <?php endif; ?>

  <?php if ($showDelete) : ?>
  /* Hapus */
  $('body').on('click', '.deleteButton', function () {
      var id = $(this).data("id");
      prosesSubmitConfirm({
        title: 'Are you sure ?',
        html: "Permanently delete '<b>Buku Tamu</b>'!",
        send: {
          csrf: true, method: "DELETE",
          url: "<?= $url_proses ?>delete&id=" + id,
          callbackSuccess: function(data) {
            swalResponse('success', data.message, false, 'x');
            setTimeout(function() { showData(tab); }, 2500);
          }
        }
      });
  });

  $('body').on('click', '.deleteImgButton', async function () {
    var form = 'dataForm';
    var id = $(this).data('id');
    var img = $(this).data('img');
    var no = $(this).data('no');
    var tipe = $(this).data('tipe');
    prosesSubmitConfirm({
      title: 'Are you sure ?',
      html: `Permanently delete '<b>${img}</b>'!`,
      send: {
        csrf: true, method: "DELETE",
        url: "<?= $url_proses ?>delete-foto&id=" + id + "&img="+img+"&tipe="+tipe,
        callbackResponse: function(data) {
          if (data.status==1) {
            nameFoto = $(`#foto_${tipe}_${id}_${no}`);
            if (nameFoto.length) {
              nameFoto.remove();
            }
            swalResponse('success', data.message, false, 'x');
            setTimeout(function() { showData(tab); }, 2500);
          }else {
            swalResponse('warning', data.message);
            form_disabled(form, false, 'all');
            onChangeMembawaKendaraan();
          }
        },
        callbackError: function(error) {
          if (error.responseJSON && error.responseJSON.message) {
            swalResponse('error', error.responseJSON.message.toString());
          }else {
            if (error.status == 401) { window.location.href = baseUrl('login'); }
            swalResponse('error', 'Error! There was an error, please try again!');
          }
          form_disabled(form, false, 'all');
          onChangeMembawaKendaraan();
        }
      }
    });
  });
  <?php endif; ?>
});

function onChangeMembawaKendaraan() {
  membawa_kendaraan = $('#membawa_kendaraan :selected').val();
  vNomorKendaraan = $('#vNomorKendaraan');
  no1 = $('#no1');
  no2 = $('#no2');
  no3 = $('#no3');
  r_foto_kendaraan = $('#r_foto_kendaraan');
  foto_kendaraan = $('#foto_kendaraan');
  no1.val('');
  no2.val('');
  no3.val('');
  if (membawa_kendaraan=='YES' || membawa_kendaraan=='YA') {
    no1.attr('required', true);
    no2.attr('required', true);
    no3.attr('required', true);
    vNomorKendaraan.show();
    r_foto_kendaraan.show();
    foto_kendaraan.attr('required', true);
    foto_kendaraan.removeAttr('disabled');
  }else {
    no1.removeAttr('required');
    no2.removeAttr('required');
    no3.removeAttr('required');
    vNomorKendaraan.hide();
    r_foto_kendaraan.hide();
    foto_kendaraan.removeAttr('required');
    foto_kendaraan.attr('disabled', true);
  }
}
</script>
