<div class="modal fade" id="ajaxModal" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content border-radius">
            <div class="modal-header">
                <button type="button" class="close closeButton" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="dataForm" name="dataForm" class="form-vertical" data-parsley-validate="true">
                  <input type="hidden" name="form_id" id="form_id" value="">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group has-feedback">
                          <label for="kode" class="control-label">Kode : <span class="text-danger">*</span></label>
                          <input type="text" class="form-control border-radius uppercase" id="kode" name="kode" placeholder="Enter Kode" value="" maxlength="100" readonly required>
                          <span class="fa fa-link form-control-feedback"></span>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group has-feedback">
                          <label for="keterangan" class="control-label">Keterangan : <span class="text-danger">*</span></label>
                          <input type="text" class="form-control border-radius" id="keterangan" name="keterangan" placeholder="Enter Keterangan" value="" required>
                          <span class="glyphicon glyphicon-bookmark form-control-feedback"></span>
                      </div>
                    </div>
                  </div>
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
