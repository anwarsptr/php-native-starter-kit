<?php
require_once DIR_PATH . 'init.php';

if (!empty($_GET['proses'])) {
  include "_proses.php"; exit;
}

$showAdd = checkPermission('setup-user-role-add', true);
$showEdit = checkPermission('setup-user-role-edit', true);
$showActive = checkPermission('setup-user-role-active', true);
$showDelete = checkPermission('setup-user-role-delete', true);

$title = "Setup User Role";

include $dir_users."/includes/header.php";

$url_proses = "setup/user-role?proses=";
?>
<section class="content-header">
  <h1> <i class="fa fa-users"></i> <?= $title ?> <small></small>
  </h1>
  <ol class="breadcrumb">
    <li>
      <a href="dashboard"> <i class="fa fa-dashboard"></i> Dashboard </a>
    </li>
    <li><a href="setup"> <i class="fa fa-gears"></i> Setup </a></li>
    <li class="active"> <i class="fa fa-users"></i> User Role</li>
  </ol>
</section>
<section class="content">

  <div class="row">
    <div class="col-xs-12">
      <div class="nav-tabs-custom border-radius">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab_1" onclick="showData(1)" data-toggle="tab"> <i class="fa fa-check text-success"></i> Active</a></li>
          <li><a href="#tab_2" onclick="showData(0)" data-toggle="tab"> <i class="fa fa-ban text-danger"></i> Not Active</a></li>
          <?php if ($showAdd): ?>
            <li class="pull-right">
              <button type="button" id="createNew" class="btn btn-primary btn-sm border-radius">
                <i class="fa fa-plus" ></i>&nbsp; Add User Role
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

include $dir_users."/includes/footer.php";

include $dir_users."/includes/components/dataTables.php";
include $dir_users."/includes/components/iCheck.php";
include $dir_users."/includes/components/select2.php";
?>

<script type="text/javascript">
$('input[type="checkbox"].flat-checkbox').iCheck({
  checkboxClass: 'icheckbox_flat-green'
});
$('.flat-checkbox').on('ifChanged', function(event){
    $(this).val($(this).prop('checked') ? 1:0);
});

$(document).ready(function () {
  showData(1);
});

var tab=1;
async function showData(stt=1)
{
  tab=stt;
  columnWidths = ['25', '30', '15', '20'];
  columnHeads = ['Username', 'Full Name', 'Role', 'Last&nbsp;Login'];
  columns = ['username', 'name', 'role_name', 'last_login'];
  await prosesDatatable({
    url: '<?= $url_proses ?>get&status='+stt,
    columnHeads: columnHeads,
    columnWidths: columnWidths, //Persen
    columns: columns,
    order:[[0, 'asc'],[1, 'asc']],
    actions: function(data, type, row) {
      id=data.id; btnAksi='';
      <?php if ($showEdit) : ?>
        btnAksi += `<a href="javascript:void(0)" data-placement="top" data-toggle="tooltip"
              class="btn btn-success btn-xs editButton m3px"
              data-id="${id}" title="Edit">
              <i class="fa fa-edit"></i>
          </a>`;
      <?php endif; ?>
      <?php if ($showDelete) : ?>
        var myArray = ['<?= get_session('id_user') ?>', 1];
        if(jQuery.inArray(id, myArray) === -1) {
          btnAksi += `<a href="javascript:void(0)" data-placement="top" data-toggle="tooltip"
            class="btn btn-danger btn-xs deleteButton m3px"
            data-id="${id}" title="Delete">
            <i class="fa fa-trash"></i>
          </a>`;
        }
      <?php endif; ?>
      return btnAksi;
    }
  });
}


$(function () {

  <?php if ($showAdd) : ?>
    /* Modal Create */
   $('#createNew').click(function () {
       $('#modelHeading').html("Add User");
       $('#dataForm').trigger("reset");
       $('#form_id').val('');
       $('#vActive').hide();
       $('#is_active').val(1).iCheck('update');
       $('#is_active').prop('checked', true).iCheck('update');
       $('.form-control.select2').select2();
       $('#dataForm').parsley().reset();
       $('#role_id').removeAttr('disabled');
       $('#pwdRequired').show();
       $('#password').attr('required', true);
       $('#ajaxModal').modal('show');
   });
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
          $('#modelHeading').html("Edit User");
          $('#pwdRequired').hide();
          $('#password').removeAttr('required');
          $('#form_id').val(data.id);
          $('#username').val(data.username);
          $('#password').val('');
          $('#name').val(data.name);
          $('#role_id').val(data.role_id);
          <?php if ($showActive) { ?>
            $('#vActive').show();
          <?php }else{ ?>
            $('#vActive').hide();
          <?php } ?>
          if(data.is_active == 1) {
            $('#is_active').val(1).iCheck('update');
            $('#is_active').prop('checked', true).iCheck('update');
          } else {
            $('#is_active').val(0).iCheck('update');
            $('#is_active').prop('checked', false).iCheck('update');
          }
          $('.form-control.select2').select2();
          $('#dataForm').parsley().reset();
          $('#ajaxModal').modal('show');
        }
      });
  });
  <?php endif; ?>

  <?php if ($showAdd || $showEdit) : ?>
  $('#dataForm').submit(async function (e) {
      e.preventDefault();
      form='dataForm';
      var fd = new FormData();
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

  <?php if ($showDelete) : ?>
  /* Hapus */
  $('body').on('click', '.deleteButton', function () {
      var id = $(this).data("id");
      prosesSubmitConfirm({
        title: 'Are you sure ?',
        html: 'Permanently delete the user and all data associated with this user!',
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
  <?php endif; ?>

});
</script>
