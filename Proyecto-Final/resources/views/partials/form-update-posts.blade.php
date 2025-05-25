<!--Estructura del formulario de actualización de posts.-->
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
            let map = null;
            const mapContainer = document.getElementById('map-container');
            const enableLocationCheckbox = document.getElementById('enable-location');

            function initMap(lat, lng) {
                if (map) {
                    map.remove();
                    map = null;
                }
                map = L.map('map').setView([lat, lng], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                L.Marker.prototype.options.icon = L.icon({
                    iconUrl: '{{ asset('storage/imagenesBugs/marker-icon.png') }}',
                    shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                });

                const marker = L.marker([lat, lng], { draggable: true }).addTo(map);

                function updateCoords(lat, lng) {
                    document.getElementById("latitude").value = lat;
                    document.getElementById("longitude").value = lng;
                }
                updateCoords(lat, lng);

                marker.on('dragend', function () {
                    const latLng = marker.getLatLng();
                    updateCoords(latLng.lat, latLng.lng);
                });

                map.on('click', function (e) {
                    marker.setLatLng(e.latlng);
                    updateCoords(e.latlng.lat, e.latlng.lng);
                });

                // Forzar recalculo tamaño
                setTimeout(() => {
                    map.invalidateSize(true);
                }, 200);
            }

            function toggleMap(e) {
                if (e.target.checked) {
                    mapContainer.style.display = 'block';
                    const lat = parseFloat(document.getElementById('latitude').value) || 40.4168;
                    const lng = parseFloat(document.getElementById('longitude').value) || -3.7038;
                    initMap(lat, lng);
                } else {
                    mapContainer.style.display = 'none';
                    if (map) {
                        map.remove();
                        map = null;
                    }
                    document.getElementById('latitude').value = '';
                    document.getElementById('longitude').value = '';
                }
            }

            enableLocationCheckbox.addEventListener('change', toggleMap);

            // Inicializar si checkbox está activo al cargar
            if (enableLocationCheckbox.checked) {
                toggleMap({ target: enableLocationCheckbox });
            }
        };
    </script>
@endpush
<main class="main__register">
    <img src="{{ asset('storage/imagenesBugs/Bug5.png') }}" class="bg-image bg-image--1" alt="">
    <img src="{{ asset('storage/imagenesBugs/Bug5.png') }}" class="bg-image bg-image--2" alt="">
    <img src="{{ asset('storage/imagenesBugs/Bug5.png') }}" class="bg-image bg-image--3" alt="">

    <form class="register__register_form {{ $errors->any() ? 'register__register_form-error' : '' }}" action="{{ route('post.updatePost', ['id' => $post->id]) }}" method="post" enctype="multipart/form-data" x-data="editor({{ $post->description ? $post->description : '{}' }})" @submit.prevent="beforeSend" id="post-form">
        @csrf
        <div class="form-group">
            <label for="title">Título:</label>
            <input class="form-control" type="text" name="title" placeholder="Introduce el título" value="{{ $post->title }}">
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
                @foreach ($insects as $insect)
                    <option value="{{ $insect->id }}" {{ $post->related_insect == $insect->id ? 'selected' : '' }}>
                        {{ $insect->name }}
                    </option>
                @endforeach
            </select>
            @error('insect') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label for="tags">Etiquetas:</label>
            <input class="form-control" type="text" name="tags" placeholder="Introduce las etiquetas (Separadas por ',')" value="{{ $post->tags->pluck('name')->implode(', ') }}">
        </div>

        <div class="form-group">
            <label for="enable-location">Añadir Localización:</label>
            <input class="form-checkbox" type="checkbox" id="enable-location" @change="toggleMap" {{ $post->latitude && $post->longitude ? 'checked' : '' }}>
        </div>

        <input type="hidden" name="latitude" id="latitude" value="{{ $post->latitude }}">
        <input type="hidden" name="longitude" id="longitude" value="{{ $post->longitude }}">

        <div class="form-group" id="map-container" style="{{ $post->latitude && $post->longitude ? '' : 'display: none;' }}">
            <div id="map"></div>
        </div>

        <div class="form-group">
            <label for="photo">Imagen:</label>
            <input type="file" class="form-control" id="input_photo" name="photo" accept="image/*">
            @error('photo') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group d-flex justify-content-center gap-3">
            @method('PUT')
            <button type="submit" class="btn btn-primary">Actualizar Post</button>
            <button type="reset" class="btn btn-danger">Resetear</button>
        </div>
    </form>

</main>