<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIG Pendataan Pom Mini di Tanah Laut</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        #mapid { height: 600px; }
    </style>
</head>
<body class="bg-light">
    <header class="py-4 mb-2 shadow-lg">
        <div class="container">
            <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="container-fluid">
                  <h2><a class="navbar-brand" href="#">Pom Mini</a></h2>
                  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                  </button>
                  <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                      <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{url('admin/kelola-pom-mini')}}">Login</a>
                      </li>

                    </ul>


                  </div>
                </div>
              </nav>
        </div>
    </header>

    <main class="container mt-8 py-6 bg-white rounded shadow-lg">
        <div id="mapid" class="rounded mb-4"></div>
        <button id="checkNearest" class="btn btn-primary">Cek Lokasi Terdekat</button>
        <div id="distanceInfo" class="text-center h5 font-weight-bold text-primary mt-4"></div>
        <div id="nearestInfo" class="text-center h5 font-weight-bold text-success mt-4"></div>
    </main>

    <footer class="bg-primary py-4 mt-8">
        <div class="container text-center text-white">
            &copy; 2024 SIG Pendataan Pom Mini di Tanah Laut. All rights reserved.
        </div>
    </footer>

    <!-- Bootstrap and necessary scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Data Pom Mini dari Laravel
        var pomMiniData = @json($pomMini);

        // Fungsi untuk menghitung jarak
        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371; // Radius bumi dalam kilometer
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                      Math.sin(dLon/2) * Math.sin(dLon/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            const distance = R * c; // Jarak dalam kilometer
            return distance;
        }

        var userLat, userLon;
        var map = L.map('mapid');

        // Mendapatkan lokasi pengguna saat ini
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                userLat = position.coords.latitude;
                userLon = position.coords.longitude;

                map.setView([userLat, userLon], 13);

                // Tambahkan marker untuk lokasi pengguna
                var userMarker = L.marker([userLat, userLon]).addTo(map)
                    .bindPopup("<b>Lokasi Anda</b>").openPopup();

                // Menampilkan marker dan jarak untuk setiap Pom Mini
                pomMiniData.forEach(function(pom) {
                    var distance = calculateDistance(userLat, userLon, pom.latitude, pom.longitude).toFixed(2);
                    L.marker([pom.latitude, pom.longitude]).addTo(map)
                        .bindPopup(`<b>${pom.nama}</b><br>${pom.alamat}<br>Jarak: ${distance} km`);
                });

                var distanceInfo = document.getElementById('distanceInfo');
                distanceInfo.innerHTML = 'Jarak ke setiap Pom Mini telah dihitung dan ditampilkan pada popup marker.';
            }, function(error) {
                alert("Geolocation gagal: " + error.message);
                // Set default view jika geolocation gagal
                map.setView([-3.3176, 114.5901], 13);
            });
        } else {
            alert("Geolocation tidak didukung oleh browser ini.");
            // Set default view jika geolocation tidak didukung
            map.setView([-3.3176, 114.5901], 13);
        }

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        // Fungsi untuk menambahkan marker untuk setiap Pom Mini
        function addMarkers() {
            pomMiniData.forEach(function(pom) {
                L.marker([pom.latitude, pom.longitude]).addTo(map)
                    .bindPopup(`<b>${pom.nama}</b><br>${pom.alamat}`);
            });
        }

        // Panggil fungsi addMarkers untuk menambahkan marker pada saat halaman dimuat
        addMarkers();

        document.getElementById('checkNearest').addEventListener('click', function() {
            if (userLat && userLon) {
                var nearestPomMini = null;
                var nearestDistance = Infinity;

                // Mencari Pom Mini terdekat
                pomMiniData.forEach(function(pom) {
                    var distance = calculateDistance(userLat, userLon, pom.latitude, pom.longitude);
                    if (distance < nearestDistance) {
                        nearestDistance = distance;
                        nearestPomMini = pom;
                    }
                });

                if (nearestPomMini) {
                    var nearestInfo = document.getElementById('nearestInfo');
                    nearestInfo.innerHTML = `Pom Mini terdekat adalah ${nearestPomMini.nama} di ${nearestPomMini.alamat}, dengan jarak ${nearestDistance.toFixed(2)} km.`;
                }
            } else {
                alert("Lokasi Anda belum ditemukan.");
            }
        });
    </script>
</body>
</html>
