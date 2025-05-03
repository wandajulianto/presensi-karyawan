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
        /* Webcam styling */
        .webcam-capture,
        .webcam-capture video {
            display: inline-block;
            width: 100% !important;
            margin: auto;
            height: auto !important;
            border-radius: 15px;
        }

        /* Map container styling */
        #map {
            height: 250px;
        }
    </style>

    <!-- Leaflet CSS and JS for maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endsection

@section('content')
    <!-- Webcam Capture Section -->
    <div class="row" style="margin-top: 70px">
        <div class="col">
            <input type="hidden" id="location">
            <div class="webcam-capture"></div>
        </div>
    </div>

    <!-- Absent Button Section -->
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

    <!-- Map Section -->
    <div class="row mt-2">
        <div class="col">
            <div id="map"></div>
        </div>
    </div>
@endsection

@push('myScript')
    <script>
        // Initialize webcam settings
        Webcam.set({
            height: 480,
            width: 640,
            imageFormat: 'jpeg',
            imageQuality: 80,
        });

        // Attach webcam to the capture element
        Webcam.attach('.webcam-capture');

        // Get location input element
        const locationInput = document.getElementById('location');

        // Check if geolocation is available
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
        }

        /**
         * Success callback for geolocation
         * @param {GeolocationPosition} position - The position object
         */
        function successCallback(position) {
            // Set location value
            const lat = position.coords.latitude;
            const long = position.coords.longitude;
            locationInput.value = `${lat},${long}`;

            // Initialize map
            const map = L.map('map').setView([lat, long], 18);

            // Add tile layer
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            // Add marker for current position
            const marker = L.marker([lat, long]).addTo(map);

            // Add circle for office radius (hardcoded office location)
            const officeCircle = L.circle([-7.33351589751558, 108.22279680492574], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: 20
            }).addTo(map);
        }

        /**
         * Error callback for geolocation
         */
        function errorCallback() {
            Swal.fire({
                title: 'Peringatan!',
                text: 'Akses lokasi diperlukan untuk absen. Mohon aktifkan GPS/lokasi.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
        }

        // Handle absent button click
        $("#takeAbsent").click(function(e) {
            // Capture image from webcam
            Webcam.snap(function(uri) {
                const image = uri;
                const locationId = $("#location").val();

                // Send data to server
                $.ajax({
                    type: 'POST',
                    url: '{{ route('presensi.store') }}',
                    data: {
                        _token: "{{ csrf_token() }}",
                        image: image,
                        location: locationId,
                    },
                    cache: false,
                    success: function(respond) {
                        const status = respond.split("|");
                        
                        if (status[0] === "success") {
                            showSuccessAlert(status[1]);
                        } else {
                            showErrorAlert(status[1]);
                        }
                    },
                    error: function() {
                        showErrorAlert('Terjadi masalah pada server, coba lagi nanti.');
                    }
                });
            });
        });

        /**
         * Show success alert
         * @param {string} message - The success message
         */
        function showSuccessAlert(message) {
            Swal.fire({
                title: 'Berhasil!',
                text: message,
                icon: 'success',
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true,
                willClose: () => {
                    window.location.href = '/dashboard';
                }
            });
        }

        /**
         * Show error alert
         * @param {string} message - The error message
         */
        function showErrorAlert(message) {
            Swal.fire({
                title: 'Error!',
                text: message,
                icon: 'error',
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true,
            });
        }
    </script>
@endpush