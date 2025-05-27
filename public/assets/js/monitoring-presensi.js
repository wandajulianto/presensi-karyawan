let map;
let markerMasuk, markerKeluar;
let kantorData = null; // Will be set from backend

$(document).ready(function() {
  // Inisialisasi flatpickr
  flatpickr(".flatpickr", {
    dateFormat: "Y-m-d",
    allowInput: true,
    locale: "id"
  });
});

// Function to set kantor data from backend
function setKantorData(data) {
  kantorData = data;
}

function showLokasiModal(nama, lokasiMasuk, lokasiKeluar, tanggal, jamMasuk, jamKeluar) {
  // Set informasi karyawan
  document.getElementById('modal-nama').textContent = nama;
  document.getElementById('modal-tanggal').textContent = tanggal;
  document.getElementById('modal-jam-masuk').textContent = jamMasuk;
  document.getElementById('modal-jam-keluar').textContent = jamKeluar;

  // Parse koordinat
  if (lokasiMasuk) {
    const [latMasuk, lngMasuk] = lokasiMasuk.split(',');
    document.getElementById('koordinat-masuk').textContent = `${latMasuk}, ${lngMasuk}`;
  } else {
    document.getElementById('koordinat-masuk').textContent = 'Tidak tersedia';
  }

  if (lokasiKeluar) {
    const [latKeluar, lngKeluar] = lokasiKeluar.split(',');
    document.getElementById('koordinat-keluar').textContent = `${latKeluar}, ${lngKeluar}`;
  } else {
    document.getElementById('koordinat-keluar').textContent = 'Belum absen keluar';
  }

  // Inisialisasi peta setelah modal ditampilkan
  setTimeout(function() {
    initMap(lokasiMasuk, lokasiKeluar);
  }, 300);
}

function initMap(lokasiMasuk, lokasiKeluar) {
  // Hapus peta sebelumnya jika ada
  if (map) {
    map.remove();
  }

  // Lokasi kantor dari database atau fallback ke koordinat default
  let kantorLat = -7.33351589751558;
  let kantorLng = 108.22279680492574;
  let kantorNama = 'Kantor Pusat';
  let kantorAlamat = 'Lokasi Kantor';

  if (kantorData) {
    kantorLat = parseFloat(kantorData.latitude);
    kantorLng = parseFloat(kantorData.longitude);
    kantorNama = kantorData.nama_kantor;
    kantorAlamat = kantorData.alamat;
  }

  // Tentukan center peta
  let centerLat = kantorLat;
  let centerLng = kantorLng;

  if (lokasiMasuk) {
    const [latMasuk, lngMasuk] = lokasiMasuk.split(',');
    centerLat = parseFloat(latMasuk);
    centerLng = parseFloat(lngMasuk);
  }

  // Inisialisasi peta
  map = L.map('map-lokasi').setView([centerLat, centerLng], 16);

  // Tambahkan tile layer
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(map);

  // Marker kantor
  const kantorIcon = L.divIcon({
    html: '<div style="background-color: #1f2937; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 12px;">🏢</div>',
    iconSize: [30, 30],
    className: 'custom-marker'
  });

  L.marker([kantorLat, kantorLng], {icon: kantorIcon})
    .addTo(map)
    .bindPopup(`<b>📍 ${kantorNama}</b><br>${kantorAlamat}`)
    .openPopup();

  // Marker lokasi masuk
  if (lokasiMasuk) {
    const [latMasuk, lngMasuk] = lokasiMasuk.split(',');
    
    const masukIcon = L.divIcon({
      html: '<div style="background-color: #22c55e; color: white; border-radius: 50%; width: 25px; height: 25px; display: flex; align-items: center; justify-content: center; font-size: 10px;">🟢</div>',
      iconSize: [25, 25],
      className: 'custom-marker'
    });

    markerMasuk = L.marker([parseFloat(latMasuk), parseFloat(lngMasuk)], {icon: masukIcon})
      .addTo(map)
      .bindPopup('<b>📍 Lokasi Absen Masuk</b><br>Koordinat: ' + lokasiMasuk);
  }

  // Marker lokasi keluar
  if (lokasiKeluar) {
    const [latKeluar, lngKeluar] = lokasiKeluar.split(',');
    
    const keluarIcon = L.divIcon({
      html: '<div style="background-color: #ef4444; color: white; border-radius: 50%; width: 25px; height: 25px; display: flex; align-items: center; justify-content: center; font-size: 10px;">🔴</div>',
      iconSize: [25, 25],
      className: 'custom-marker'
    });

    markerKeluar = L.marker([parseFloat(latKeluar), parseFloat(lngKeluar)], {icon: keluarIcon})
      .addTo(map)
      .bindPopup('<b>📍 Lokasi Absen Keluar</b><br>Koordinat: ' + lokasiKeluar);
  }

  // Auto fit bounds jika ada marker
  const allMarkers = [];
  if (lokasiMasuk) {
    const [latMasuk, lngMasuk] = lokasiMasuk.split(',');
    allMarkers.push([parseFloat(latMasuk), parseFloat(lngMasuk)]);
  }
  if (lokasiKeluar) {
    const [latKeluar, lngKeluar] = lokasiKeluar.split(',');
    allMarkers.push([parseFloat(latKeluar), parseFloat(lngKeluar)]);
  }
  allMarkers.push([kantorLat, kantorLng]);

  if (allMarkers.length > 1) {
    const group = new L.featureGroup(allMarkers.map(coords => L.marker(coords)));
    map.fitBounds(group.getBounds().pad(0.1));
  }

  // Force map resize after modal is shown
  setTimeout(function() {
    map.invalidateSize();
  }, 100);
}

// Event listener untuk modal
$('#lokasiModal').on('shown.bs.modal', function () {
  if (map) {
    setTimeout(function() {
      map.invalidateSize();
    }, 100);
  }
}); 