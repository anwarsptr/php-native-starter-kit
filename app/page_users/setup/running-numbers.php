<?php
require_once DIR_PATH . 'init.php';

if (!empty($_GET['proses'])) {
  include "_proses.php"; exit;
}

$showPermission = checkPermission('setup-running-numbers-edit', true);

$title = "Setup Running Numbers";

include $dir_users."/includes/header.php";

$sql = getData('setup_running_numbers');
$result = mysqli_fetch_all($sql, MYSQLI_ASSOC);
?>
<section class="content-header">
  <h1> <i class="fa fa-edit"></i> <?= $title ?> <small></small>
  </h1>
  <ol class="breadcrumb">
    <li>
      <a href="dashboard"> <i class="fa fa-dashboard"></i> Dashboard </a>
    </li>
    <li><a href="setup"> <i class="fa fa-gears"></i> Setup </a></li>
    <li class="active"> <i class="fa fa-edit"></i> Running Numbers</li>
  </ol>
</section>
<section class="content">
  <div class="row">
    <?php foreach ($result as $key => $value):
        $id = encrypt($value['id']);
        $random_allow = $value['random_allow'];
        if (empty($random_allow)) {
          $random_allow = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        }
        $type = $value['type'];
        $inisial = $value['inisial'];
        $length = $value['length'];
        $nomor = generateNumber($value, 'x');
      ?>
      <div class="col-md-6">
        <div class="box box-primary border-radius">
          <div class="box-header with-border">
            <h3 class="box-title"><b><?= $key+1; ?>. <?= $value['description'] ?></b></h3>
          </div>
          <div class="box-body">
            <form id="running-numbers_<?= $key ?>" action="javascript:postSubmit('running-numbers_<?= $key ?>', 'setup/running-numbers?proses=save-setup-running-numbers&id=<?= $id ?>')" method="post" data-parsley-validate="true">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group has-feedback">
                    <label for="inisial" class="control-label">Inisial : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control border-radius uppercase" name="inisial" id="inisial_<?= $key ?>" placeholder="Enter inisial" value="<?= $inisial ?>" maxlength="4" required oninput="showNumber('<?= $key ?>')" <?= $showPermission ? '':'readonly' ?>>
                    <span class="fa fa-edit form-control-feedback"></span>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group has-feedback">
                    <label for="length" class="control-label">Length : <span class="text-danger">*</span></label>
                    <input type="text" pattern="[0-9]*" inputmode="numeric" class="form-control border-radius" name="length" id="length_<?= $key ?>" placeholder="length" value="<?= $length ?>" maxlength="2" onkeypress="return hanyaAngka(this)" required oninput="cekMaxValue(this, '<?= $key ?>')" <?= $showPermission ? '':'readonly' ?>>
                    <span class="fa fa-calculator form-control-feedback"></span>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <label class="control-label" for="type">Type : <span class="text-danger">*</span></label>
                    <select class="form-control select2" id="type_<?= $key ?>" name="type" data-placeholder="-- Select Type --" required data-parsley-errors-container="#error_type" onchange="showNumber('<?= $key ?>')" <?= $showPermission ? '':'disabled' ?>>
                      <option value=""></option>
                      <option value="0" <?= $type==0 ? 'selected':'' ?>>Random</option>
                      <option value="1" <?= $type==1 ? 'selected':'' ?>>Sequence number</option>
                    </select>
                    <small id="error_type"></small>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group has-feedback">
                    <label for="random_allow" class="control-label">Permitted Characters (Random Type) : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control border-radius uppercase" name="random_allow" id="random_allow_<?= $key ?>" placeholder="Enter permitted characters" value="<?= $random_allow ?>" maxlength="100" <?= $type==1 ? 'disabled':'' ?> required oninput="showNumber('<?= $key ?>')" <?= $showPermission ? '':'readonly' ?>>
                    <span class="fa fa-edit form-control-feedback"></span>
                  </div>
                </div>
              </div>
              <div style="margin-bottom:10px">
                <b>Results : <span class="badge bg-green" id="result_<?= $key ?>"><?= $nomor ?></span></b>
              </div>
              <?php if ($showPermission): ?>
                <button type="submit" id="saveBtn" class="btn btn-primary btn-block border-radius"> <i class="fa fa-save"></i>&nbsp; Save</button>
              <?php endif; ?>
            </form>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php
include $dir_users."/includes/footer.php";
?>

<script type="text/javascript">
function cekMaxValue(input, i) {
    const maxValue = 10;
    if (input.value === "0") {
        input.value = 5; // Jika kosong, atur kembali ke 5
    }else if (parseInt(input.value, 10) > maxValue) {
        input.value = maxValue; // Jika lebih dari 10, atur kembali ke 10
    }
    showNumber(i);
}

function showNumber(i=0) {
  onValidUsername('inisial_'+i);
  onValidUsername('random_allow_'+i);
  sel_type = $('#type_'+i+' :selected').val();
  if (sel_type==1) {
    $('#random_allow_'+i).attr('disabled', true);
  }else {
    $('#random_allow_'+i).removeAttr('disabled');
  }
  const value = {
      type: sel_type, // Ubah ke 1 untuk nomor urut
      inisial: $('#inisial_'+i).val(),
      length: $('#length_'+i).val()
  };
  $('#result_'+i).html(generateNumberOrRandom(value));
}

function generateNumberOrRandom(value) {
    let randomAllow = value.random_allow || 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    const type = value.type;
    const inisial = value.inisial || '';
    const length = value.length || 5;
    let nomor = inisial;

    if (type === '1') { // Jika nomor urut
        nomor += String(1).padStart(length, '0');
    } else {
        const charactersLength = randomAllow.length;
        for (let i = 0; i < length; i++) {
            const randomIndex = Math.floor(Math.random() * charactersLength);
            nomor += randomAllow[randomIndex];
        }
    }

    return nomor.toUpperCase();
}
</script>
