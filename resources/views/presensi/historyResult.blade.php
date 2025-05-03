{{-- Tampilkan pesan jika data riwayat kosong --}}
@if ($history->isEmpty())
  <div class="alert alert-warning">
    <p>Data Tidak Tersedia</p>
  </div>
@endif

{{-- Daftar riwayat presensi --}}
<ul class="listview image-listview">
  @foreach ($history as $item)
    <li>
      <div class="item">

        {{-- Ambil URL gambar foto masuk --}}
        @php
          $fotoMasuk = Storage::url('uploads/absention/' . $item->foto_masuk);
        @endphp

        {{-- Gambar foto masuk --}}
        <img src="{{ url($fotoMasuk) }}" alt="Foto Masuk" class="image">

        {{-- Informasi presensi (tanggal, jam masuk, jam keluar) --}}
        <div class="in d-flex justify-content-between align-items-center w-100">

          {{-- Kolom kiri: Tanggal --}}
          <div class="text-start" style="flex: 1;">
            <b>{{ date('d-m-Y', strtotime($item->tanggal_presensi)) }}</b>
          </div>

          {{-- Kolom tengah: Jam Masuk (warna hijau jika < 07:00, merah jika >= 07:00) --}}
          <div class="text-center" style="flex: 1;">
            <span class="badge {{ $item->jam_masuk < '07:00' ? 'bg-success' : 'bg-danger' }}">
              {{ $item->jam_masuk }}
            </span>
          </div>

          {{-- Kolom kanan: Jam Keluar (tampilkan '-' jika null) --}}
          <div class="text-end" style="flex: 1;">
            <span class="badge bg-primary">
              {{ $item->jam_keluar ?? '-' }}
            </span>
          </div>

        </div>
      </div>
    </li>
  @endforeach
</ul>
