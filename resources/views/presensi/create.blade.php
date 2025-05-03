@extends('layouts.presensi')

@section('header')
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">E-Presensi</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
    <style>
        .webcam-capture,
        .webcam-capture video {
            display: inline-block;
            width: 100% !important;
            margin: auto;
            height: auto !important;
            border-radius: 15px;
        }

        #map {
            height: 250px;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endsection

@section('content')
    <div class="row" style="margin-top: 70px">
        <div class="col">
            <input type="hidden" id="location">
            <div class="webcam-capture"></div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            @if ($isAbsent > 0)
                <button id="takeAbsent" class="btn btn-danger btn-block">
                    <ion-icon name="camera-outline"></ion-icon>
                    Absen Pulang
                </button>
            @else
                <button id="takeAbsent" class="btn btn-primary btn-block">
                    <ion-icon name="camera-outline"></ion-icon>
                    Absen Masuk
                </button>
            @endif
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <div id="map"></div>
        </div>
    </div>
@endsection

@push('myScript')
    <script>
        Webcam.set({
            height: 480,
            width: 640,
            imageFormat: 'jpeg',
            imageQuality: 80,
        });

        Webcam.attach('.webcam-capture');

        var locationInput = document.getElementById('location');

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
        }

        function successCallback(position) {
            locationInput.value = position.coords.latitude + ',' + position.coords.longitude;

            var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 18);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);

            var circle = L.circle([-7.33351589751558, 108.22279680492574], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: 20
            }).addTo(map);
        }

        function errorCallback() {
            alert("Geolocation gagal!");
        }

        $("#takeAbsent").click(function (e) {
            Webcam.snap(function (uri) {
                var image = uri;
                var locationId = $("#location").val();

                $.ajax({
                    type: 'POST',
                    url: '{{ route('presention.store') }}', // Gunakan route named
                    data: {
                        _token: "{{ csrf_token() }}",
                        image: image,
                        location: locationId,
                    },
                    cache: false,
                    success: function (respond) {
                        var status = respond.split("|")
                        if (status[0] == "success") {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: status[1],
                                icon: 'success',
                                confirmButtonText: 'OK',
                                timer: 3000, // Auto close setelah 3 detik
                                timerProgressBar: true, // Menampilkan progress bar
                                willClose: () => {
                                    // Redirect ketika alert ditutup (baik manual maupun auto close)
                                    window.location.href = '/dashboard';
                                }
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: status[1],
                                icon: 'error',
                                confirmButtonText: 'OK',
                                timer: 3000, // Auto close setelah 3 detik
                                timerProgressBar: true, // Menampilkan progress bar
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi masalah pada server, coba lagi nanti.',
                            icon: 'error',
                            confirmButtonText: 'OK',
                        });
                    }
                });
            });
        });

    </script>
@endpush
