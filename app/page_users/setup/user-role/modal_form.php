<?php
$sql = getData('roles', "is_active=1 AND deleted_at is null ORDER BY order_by ASC");
$result = mysqli_fetch_all($sql, MYSQLI_ASSOC);
?>
<div class="modal fade" id="ajaxModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-radius">
            <div class="modal-header">
                <button type="button" class="close closeButton" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="dataForm" name="dataForm" class="form-vertical" data-parsley-validate="true">
                  <input type="hidden" name="form_id" id="form_id" value="">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group has-feedback">
                          <label for="username" class="control-label">Username : <span class="text-danger">*</span></label>
                          <input type="text" class="form-control border-radius" id="username" name="username" placeholder="Enter Username"  oninput="onValidUsername('username')" value="" maxlength="100" required>
                          <span class="glyphicon glyphicon-user form-control-feedback"></span>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group has-feedback">
                          <label for="name" class="control-label">Name : <span class="text-danger">*</span></label>
                          <input type="text" class="form-control border-radius" id="name" name="name" placeholder="Enter Name"  oninput="validateAbjad(this)" value="" maxlength="100" required>
                          <span class="glyphicon glyphicon-bookmark form-control-feedback"></span>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group has-feedback">
                        <label for="password">Password <span id="pwdRequired">: <span class="text-danger">*</span></span></label>
                        <input type="password" name="password" id="password" class="form-control border-radius" placeholder="Password" required data-parsley-trigger="keyup" data-parsley-minlength="5">
                        <span class="glyphicon glyphicon-eye-open form-control-feedback iconPwd btnShowPwd" onclick="showPwd()"></span>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="from-group">
                        <label class="control-label">Role : <span class="text-danger">*</span></label>
                        <select class="form-control select2" id="role_id" name="role_id" required data-parsley-errors-container="#error_role">
                          <option value="">-- Select Role --</option>
                          <?php foreach ($result as $key => $value): ?>
                            <option value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
                          <?php endforeach; ?>
                        </select>
                        <small id="error_role"></small>
                      </div>
                    </div>
                  </div>
                  <br>
                  <div class="form-group" id="vActive">
                    <label>
                      <input type="checkbox" class="flat-checkbox" name="is_active" id="is_active" value="1">
                      &nbsp;Active?
                    </label>
                  </div>
                  <button type="submit" id="saveBtn" class="btn btn-primary btn-block border-radius"> <i class="fa fa-save"></i>&nbsp; Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
