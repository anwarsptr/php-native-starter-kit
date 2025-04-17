<style>
  #tgl_kunjungan:disabled {
    background: #eeeeee !important;
  }
</style>
<?php
$sql = getData('md_jenis_identitas', "is_active=1 AND deleted_at is null");
$getJenisIdentitas = mysqli_fetch_all($sql, MYSQLI_ASSOC);
?>
<div class="modal fade" id="ajaxModal" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-radius">
            <div class="modal-header">
                <button type="button" class="close closeButton" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="dataForm" name="dataForm" class="form-vertical" data-parsley-validate="true">
                  <input type="hidden" name="form_id" id="form_id" value="">
                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group has-feedback">
                          <label for="nomor" class="control-label">Nomor : <span class="text-danger">*</span></label>
                          <input type="text" class="form-control border-radius uppercase" id="nomor" name="nomor" placeholder="Enter Nomor" value="" maxlength="100" readonly required>
                          <span class="fa fa-link form-control-feedback"></span>
                      </div>
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group has-feedback">
                          <label for="tgl_kunjungan" class="control-label">Tanggal Kunjungan : <span class="text-danger">*</span></label>
                          <input type="text" class="form-control border-radius" id="tgl_kunjungan" name="tgl_kunjungan" placeholder="Enter Tanggal" value="" maxlength="10" required readonly style="background:white">
                          <span class="fa fa-calendar form-control-feedback"></span>
                      </div>
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group has-feedback bootstrap-timepicker">
                          <label for="jam_kunjungan" class="control-label">Jam Kunjungan : <span class="text-danger">*</span></label>
                          <input type="text" class="form-control border-radius timepicker" id="jam_kunjungan" name="jam_kunjungan" placeholder="Enter Jam" value="" maxlength="5" pattern="[0-9]{2}:[0-9]{2}" required>
                          <span class="glyphicon glyphicon-time form-control-feedback"></span>
                      </div>
                    </div>
                    <div class="col-md-3 col-xs-12">
                      <div class="form-group has-feedback">
                          <label for="input_by" class="control-label">Input By : <span class="text-danger">*</span></label>
                          <input type="text" class="form-control border-radius" id="input_by" name="input_by" placeholder="Enter Input By" value="<?= get_session('name'); ?>" readonly>
                          <span class="glyphicon glyphicon-user form-control-feedback"></span>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group has-feedback">
                          <label for="nama_tamu" class="control-label">Nama Tamu : <span class="text-danger">*</span></label>
                          <input type="text" class="form-control border-radius" id="nama_tamu" name="nama_tamu" required placeholder="Enter Nama Tamu" value="" required maxlength="100">
                          <span class="glyphicon glyphicon-user form-control-feedback"></span>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group has-feedback">
                          <label for="no_telp_tamu" class="control-label">No.Telp Tamu : <span class="text-danger">*</span></label>
                          <input type="text" class="form-control border-radius" id="no_telp_tamu" name="no_telp_tamu" required placeholder="Enter No.Telp Tamu" value="" required pattern="[0-9]*" inputmode="numeric" onkeypress="return hanyaAngka(this)">
                          <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="from-group">
                        <label class="control-label">Membawa Kendaraan : <span class="text-danger">*</span></label>
                        <select class="form-control select2" id="membawa_kendaraan" name="membawa_kendaraan" required data-parsley-errors-container="#error_membawa_kendaraan" onchange="onChangeMembawaKendaraan()">
                          <option value="">-- Select --</option>
                          <option value="YA">YA</option>
                          <option value="TIDAK">TIDAK</option>
                        </select>
                        <small id="error_membawa_kendaraan"></small>
                      </div>
                      <br>
                    </div>
                    <div class="col-md-6" id="vNomorKendaraan" style="display:none">
                      <div class="form-group">
                          <label class="control-label">Nomor Kendaraan : <span class="text-danger">*</span></label>
                          <div class="row">
                            <div class="col-md-2 col-xs-3" style="padding-right:0px;">
                              <input id="no1" name="no1_kendaraan" value="" class="form-control uppercase text-center" placeholder="B" maxlength="3" oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"/>
                            </div>
                            <div class="col-md-3 col-xs-4">
                              <input id="no2" name="no2_kendaraan" value="" class="form-control uppercase text-center" placeholder="1234" maxlength="5" pattern="[0-9]*" inputmode="numeric" onkeypress="return hanyaAngka(this)"/>
                            </div>
                            <div class="col-md-2 col-xs-3" style="padding-left:0px;">
                              <input id="no3" name="no3_kendaraan" value="" class="form-control uppercase text-center" placeholder="XX" maxlength="3" oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"/>
                            </div>
                          </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-6">
                      <div class="from-group">
                        <label class="control-label">Jenis Identitas : <span class="text-danger">*</span></label>
                        <select class="form-control select2" id="jenis_identitas_id" name="jenis_identitas_id" required data-parsley-errors-container="#error_jenis_identitas_id">
                          <option value="">-- Select Jenis Identitas --</option>
                          <?php foreach ($getJenisIdentitas as $key => $value): ?>
                            <option value="<?= $value['id'] ?>"><?= $value['kode'] ?> - <?= $value['keterangan'] ?></option>
                          <?php endforeach; ?>
                        </select>
                        <small id="error_jenis_identitas_id"></small>
                      </div>
                      <br>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group has-feedback">
                          <label for="nama_yang_dikunjungi" class="control-label">Nama yang dikunjungi : <span class="text-danger">*</span></label>
                          <input type="text" class="form-control border-radius" id="nama_yang_dikunjungi" name="nama_yang_dikunjungi" placeholder="Enter Nama yang dikunjungi" value="" required maxlength="100">
                          <span class="glyphicon glyphicon-user form-control-feedback"></span>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group has-feedback">
                          <label for="no_tanda_masuk" class="control-label">No Tanda Masuk Perumahan : <span class="text-danger">*</span></label>
                          <input type="text" class="form-control border-radius" id="no_tanda_masuk" name="no_tanda_masuk" placeholder="Enter No Tanda Masuk Perumahan" value="" required maxlength="20">
                          <span class="glyphicon glyphicon-bookmark form-control-feedback"></span>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group" id="vno_tanda_masuk_dikembalikan" style="display:none">
                        <label>
                          <input type="checkbox" class="flat-checkbox" name="no_tanda_masuk_dikembalikan" id="no_tanda_masuk_dikembalikan" value="0">
                          &nbsp;No Tanda Masuk Perumahan sudah dikembalikan?
                        </label>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="foto_tanda_pengenal" class="control-label">Foto Tanda Pengenal : <span class="text-danger" id="r_foto_tanda_pengenal">*</span></label>
                        <input type="file" class="form-control border-radius uploadFoto" name="foto_tanda_pengenal[]" id="foto_tanda_pengenal" required multiple accept=".jpeg, .jpg, image/png">
                        <small class="text-danger">* Ekstensi : <b>jpeg, jpg, png</b> | Maks.Per-Upload : <b><?= formatFromMB(maxUploadFile('buku-tamu')) ?></b></small>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="foto_kendaraan" class="control-label">Foto Kendaraan : <span class="text-danger" id="r_foto_kendaraan" style="display:none">*</span></label>
                        <input type="file" class="form-control border-radius uploadFoto" name="foto_kendaraan[]" id="foto_kendaraan" multiple accept=".jpeg, .jpg, image/png" disabled>
                        <small class="text-danger">* Ekstensi : <b>jpeg, jpg, png</b> | Maks.Per-Upload : <b><?= formatFromMB(maxUploadFile('buku-tamu')) ?></b></small>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group has-feedback">
                          <label for="keterangan" class="control-label">Keterangan :</label>
                          <textarea class="form-control border-radius" id="keterangan" name="keterangan" placeholder="Enter Keterangan" style="resize: vertical;"></textarea>
                          <span class="glyphicon glyphicon-edit form-control-feedback"></span>
                      </div>
                    </div>
                  </div>
                  <button type="submit" id="saveBtn" class="btn btn-primary btn-block border-radius"> <i class="fa fa-save"></i>&nbsp; Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
