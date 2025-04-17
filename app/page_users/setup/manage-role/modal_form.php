<div class="modal fade" id="ajaxModal" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content border-radius">
            <div class="modal-header">
                <button type="button" class="close closeButton" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="dataForm" name="dataForm" class="form-vertical" data-parsley-validate="true">
                  <input type="hidden" name="form_id" id="form_id" value="">
                  <div class="form-group has-feedback">
                      <label for="name" class="control-label">Name : <span class="text-danger">*</span></label>
                      <input type="text" class="form-control border-radius" id="name" name="name" placeholder="Enter Name"  oninput="validateAbjad(this)" value="" maxlength="100" required>
                      <span class="glyphicon glyphicon-bookmark form-control-feedback"></span>
                  </div>
                  <button type="submit" id="saveBtn" class="btn btn-primary btn-block border-radius"> <i class="fa fa-save"></i>&nbsp; Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
