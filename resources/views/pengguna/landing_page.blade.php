<!-- resources/views/landing_page.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIG Pendataan Pom Mini di Tanah Laut</title>
    <link href="https://unpkg.com/tailwindcss@^2.2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        #mapid { height: 600px; }
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-blue-600 p-4 shadow-lg">
        <div class="container mx-auto">
            <h1 class="text-white text-3xl font-bold">SIG Pendataan Pom Mini di Tanah Laut</h1>
        </div>
    </header>

    <main class="container mx-auto mt-8 p-4 bg-white rounded shadow-lg">
        <div id="mapid" class="rounded mb-4"></div>
        <button id="checkNearest" class="bg-blue-600 text-white px-4 py-2 rounded">Cek Lokasi Terdekat</button>
        <div id="distanceInfo" class="text-center text-lg font-semibold text-blue-600 mt-4"></div>
        <div id="nearestInfo" class="text-center text-lg font-semibold text-green-600 mt-4"></div>
    </main>

    <footer class="bg-blue-600 p-4 mt-8">
        <div class="container mx-auto text-center text-white">
            &copy; 2024 SIG Pendataan Pom Mini di Tanah Laut. All rights reserved.
        </div>
    </footer>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        var map = L.map('mapid').setView([-3.3176, 114.5901], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        // Data Pom Mini dari Laravel
        var pomMiniData = @json($pomMini);

        // Fungsi untuk menambahkan marker untuk setiap Pom Mini
        function addMarkers() {
            pomMiniData.forEach(function(pom) {
                L.marker([pom.latitude, pom.longitude]).addTo(map)
                    .bindPopup(`<b>${pom.nama}</b><br>${pom.alamat}`);
            });
        }

        // Panggil fungsi addMarkers untuk menambahkan marker pada saat halaman dimuat
        addMarkers();

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

        // Mendapatkan lokasi pengguna saat ini
        var userLat, userLon;
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                userLat = position.coords.latitude;
                userLon = position.coords.longitude;

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
            });
        } else {
            alert("Geolocation tidak didukung oleh browser ini.");
        }

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
