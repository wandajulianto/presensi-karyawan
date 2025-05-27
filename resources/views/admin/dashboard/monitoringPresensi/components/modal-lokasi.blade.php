<!-- Modal Lokasi Absensi -->
<div class="modal modal-blur fade" id="lokasiModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Lokasi Absensi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-3">
        <div class="row mb-2">
          <div class="col-md-6">
            <h6 class="mb-1">Informasi Karyawan</h6>
            <table class="table table-borderless table-sm mb-2">
              <tr>
                <td width="35%"><small><strong>Nama</strong></small></td>
                <td width="5%"><small>:</small></td>
                <td><small id="modal-nama">-</small></td>
              </tr>
              <tr>
                <td><small><strong>Tanggal</strong></small></td>
                <td><small>:</small></td>
                <td><small id="modal-tanggal">-</small></td>
              </tr>
            </table>
          </div>
          <div class="col-md-6">
            <h6 class="mb-1">Waktu Absensi</h6>
            <table class="table table-borderless table-sm mb-2">
              <tr>
                <td width="35%"><small><strong>Jam Masuk</strong></small></td>
                <td width="5%"><small>:</small></td>
                <td><small id="modal-jam-masuk">-</small></td>
              </tr>
              <tr>
                <td><small><strong>Jam Keluar</strong></small></td>
                <td><small>:</small></td>
                <td><small id="modal-jam-keluar">-</small></td>
              </tr>
            </table>
          </div>
        </div>

        <!-- Peta Lokasi -->
        <div class="row">
          <div class="col-12">
            <h6 class="mb-2">Peta Lokasi Absensi</h6>
            <div id="map-lokasi" style="height: 250px; border-radius: 6px;"></div>
          </div>
        </div>

        <!-- Koordinat -->
        <div class="row mt-2">
          <div class="col-md-6">
            <div class="card mb-0">
              <div class="card-body p-2">
                <h6 class="card-title text-success mb-1 fs-6">📍 Lokasi Masuk</h6>
                <small class="text-muted" id="koordinat-masuk">-</small>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card mb-0">
              <div class="card-body p-2">
                <h6 class="card-title text-danger mb-1 fs-6">📍 Lokasi Keluar</h6>
                <small class="text-muted" id="koordinat-keluar">-</small>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer p-2">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div> 