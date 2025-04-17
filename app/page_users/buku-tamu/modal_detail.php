<div class="modal fade" id="ajaxModalDetail" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content border-radius">
            <div class="modal-header">
                <button type="button" class="close closeButton" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="modelHeadingDetail"></h4>
            </div>
            <div class="modal-body">
              <table class="table table-bordered table-striped table-hover" width="100%">
                <tbody>
                  <tr>
                    <th width="14%">Nomor</th>
                    <th width="1%">:</th>
                    <td width="85%" id="d_nomor"></td>
                  </tr>
                  <tr>
                    <th>Tanggal Kunjungan</th>
                    <th>:</th>
                    <td id="d_tgl_kunjungan"></td>
                  </tr>
                  <tr>
                    <th>Jam Kunjungan</th>
                    <th>:</th>
                    <td id="d_jam_kunjungan"></td>
                  </tr>
                  <tr>
                    <th>Nama Tamu</th>
                    <th>:</th>
                    <td id="d_nama_tamu"></td>
                  </tr>
                  <tr>
                    <th>No.Telp Tamu</th>
                    <th>:</th>
                    <td id="d_no_telp_tamu"></td>
                  </tr>
                  <tr>
                    <th>Membawa&nbsp;Kendaraan</th>
                    <th>:</th>
                    <td id="d_membawa_kendaraan"></td>
                  </tr>
                  <tr>
                    <th>Nomor&nbsp;Kendaraan</th>
                    <th>:</th>
                    <td id="d_nomor_kendaraan"></td>
                  </tr>
                  <tr>
                    <th>Jenis&nbsp;Identitas</th>
                    <th>:</th>
                    <td id="d_jenis_identitas"></td>
                  </tr>
                  <tr>
                    <th>Nama&nbsp;yang dikunjungi</th>
                    <th>:</th>
                    <td id="d_nama_yang_dikunjungi"></td>
                  </tr>
                  <tr>
                    <th>No&nbsp;Tanda&nbsp;Masuk Perumahan</th>
                    <th>:</th>
                    <td id="d_no_tanda_masuk"></td>
                  </tr>
                  <tr>
                    <th>No&nbsp;Tanda&nbsp;Masuk Perumahan sudah dikembalikan</th>
                    <th>:</th>
                    <td id="d_no_tanda_masuk_dikembalikan"></td>
                  </tr>
                  <tr>
                    <th>Keterangan</th>
                    <th>:</th>
                    <td id="d_keterangan"></td>
                  </tr>
                  <tr>
                    <th>Created&nbsp;at</th>
                    <th>:</th>
                    <td id="d_created_at"></td>
                  </tr>
                  <tr>
                    <th>Created&nbsp;by</th>
                    <th>:</th>
                    <td id="d_created_by"></td>
                  </tr>
                  <tr>
                    <th>Updated&nbsp;at</th>
                    <th>:</th>
                    <td id="d_updated_at"></td>
                  </tr>
                  <tr>
                    <th>Updated&nbsp;by</th>
                    <th>:</th>
                    <td id="d_updated_by"></td>
                  </tr>
                </tbody>
              </table>

              <div id="v_foto_tanda_pengenal" style="max-height: 300px; overflow-y: auto;">
                <b>Foto Tanda Pengenal</b>
                <table id="d_foto_tanda_pengenal" class="table table-bordered table-striped table-hover" width="100%">
                  <thead>
                    <tr>
                      <th width="1%">No</th>
                      <th width="94%">Foto</th>
                      <?php if ($showDelete) { ?>
                      <th width="5%"></th>
                      <?php } ?>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>

              <div id="v_foto_kendaraan" style="max-height: 300px; overflow-y: auto;margin-top:10px">
                <b>Foto Kendaraan</b>
                <table id="d_foto_kendaraan" class="table table-bordered table-striped table-hover" width="100%">
                  <thead>
                    <tr>
                      <th width="1%">No</th>
                      <th width="94%">Foto</th>
                      <?php if ($showDelete) { ?>
                      <th width="5%"></th>
                      <?php } ?>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
        </div>
    </div>
</div>
