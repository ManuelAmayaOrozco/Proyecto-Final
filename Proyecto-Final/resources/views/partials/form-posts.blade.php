<!--Estructura del formulario de registro de posts.-->
@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin="" />
@endpush
@push('scripts') 
    @vite(['resources/css/user_styles/register_styles.css', 'resources/js/editor.js'])
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>
    <script>
        window.onload = function () {
            const defaultLat = 40.4168;
            const defaultLng = -3.7038;

            const map = L.map('map').setView([defaultLat, defaultLng], 13);
            window.map = map; // 💡 guardar globalmente para Alpine.js

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            L.Marker.prototype.options.icon = L.icon({
                iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            const marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

            function updateCoords(lat, lng) {
                document.getElementById("latitude").value = lat;
                document.getElementById("longitude").value = lng;
            }

            updateCoords(defaultLat, defaultLng);

            marker.on('dragend', function () {
                const latLng = marker.getLatLng();
                updateCoords(latLng.lat, latLng.lng);
            });

            map.on('click', function (e) {
                marker.setLatLng(e.latlng);
                updateCoords(e.latlng.lat, e.latlng.lng);
            });

            setTimeout(() => {
                map.invalidateSize(true);
            }, 200);
        };
    </script>
@endpush
<main class="main__register">
    <form class="register__register_form {{ $errors->any() ? 'register__register_form-error' : '' }}" action="{{ route('post.doRegisterPost') }}" method="post" enctype="multipart/form-data" x-data="editor" @submit.prevent="beforeSend" id="post-form">
        @csrf
        <div class="form-group">
            <label for="title">Título:</label>
            <input class="form-control" type="text" name="title" placeholder="Escribe el título">
            @error('title') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>

        <input type="hidden" name="description" id="description">
        <div class="form-group">
            <label for="editor">Descripción:</label>
            <div id="editor" class="editor-input"></div>
            @error('description') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label for="insect">Insecto:</label>
            <select class="form-select" name="insect" id="insect">
                    <option value="" disabled selected>Elige un insecto</option>
                @foreach ($insects as $insect)
                    <option value="{{ $insect->id }}">{{ $insect->name }}</option>
                @endforeach
            </select>
            @error('insect') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label for="tags">Etiquetas:</label>
            <input class="form-control" type="text" name="tags" placeholder="Escribe las etiquetas (Separadas por ',')">
        </div>

        <div class="form-group">
            <label for="enable-location">Añadir Localización:</label>
            <input class="form-checkbox" type="checkbox" id="enable-location" @change="toggleMap">
        </div>

        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">
        <div class="form-group" id="map-container" style="display: none;">
            <div id="map"></div>
        </div>   

        <div class="form-group">
            <label for="photo">Imagen:</label>
            <input type="file" class="form-control" id="input_photo" name="photo" accept="image/*">
            @error('photo') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group d-flex justify-content-center gap-3">
            <button type="submit" class="btn btn-primary">Registrar Post</button>
            <button type="reset" class="btn btn-danger">Resetear</button>
        </div>
    </form>

    <!--<div id="editor">-->

    <!--<div class="form-group" id="editor">
            <label for="description">Description:</label>
            <textarea rows="4" class="form-control" name="description" placeholder="Enter description"></textarea>
            @error('description') <small class="register_form__error">{{ $message }}</small> @enderror
    </div>-->
</main>