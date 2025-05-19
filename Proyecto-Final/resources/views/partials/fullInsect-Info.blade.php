<!--Estructura de la información detallada de un insecto.-->
@vite(['resources/css/user_styles/user-index_styles.css', 'resources/js/alpine.js', 'resources/js/app.js'])
<main class="main__full-insect-index">

    <div class="insect-box">

        <h2 class="insect-name">{{ $insect->name }}</h2>
        <h3 class="insect-scientificName">{{ $insect->scientificName }}</h2>
        <h3 class="insect-user">{{ $insect_user }}</h3>
        <div class="insect-separator-box">
        <div class="insect-picture-display">
            @foreach ($insect->photos as $photo)
                <img src="{{ asset('storage/' . $photo->path) }}" alt="Foto de {{ $insect->name }}" class="insect-picture">
            @endforeach
        </div>
            <p class="insect-info-start">Familia: <span class="insect-info">{{ $insect->family }}</span></p>
            <p class="insect-info-start">Dieta: <span class="insect-info">{{ $insect->diet }}</span></p>
            <p class="insect-info-start">Nº Documentados: <span class="insect-info">{{ $insect->n_spotted }}</span></p>
            <p class="insect-info-start">Tamaño record: <span class="insect-info">{{ $insect->maxSize }}</span></p>
            <p class="insect-info-start">En peligro de extinción: <span class="insect-info">{{ $insect->protectedSpecies ? 'SI' : 'NO' }}</span></p>
            <div class="insect-text">
                <script id="post-description-json" type="application/json">
                    {!! $insect->description !!}
                </script>
            </div>
        </div>

        @if($current_user && $current_user->isAdmin)
        <form action="{{ route('insect.showUpdateInsect', ['id' => $insect->id]) }}" method="POST">
            @csrf
            @method('GET')
            <button type="submit" class="btn btn-like"><i class="bi bi-arrow-clockwise icon-white"></i> Actualizar Insecto</button>
        </form>

        <div x-data="{}">
            <button @click="$refs.dialogDelUser.showModal()" class="btn btn-danger"><i class="bi bi-trash icon-white"></i> Eliminar Insecto</button>
            <dialog x-ref="dialogDelUser" class="bg-white rounded-lg shadow-lg p-4">
            
                <h2>¿Estas seguro de que quieres eliminar el insecto del registro?</h2>

                <form action="{{ route('insect.deleteInsect', ['id' => $insect->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-success">Sí, Eliminar Insecto</button>
                </form>

                <form method="dialog">

                    <button class="btn btn-danger">Cancelar</button>

                </form>

            </dialog>
        </div>
        @endif

    </div>

    <!-- Cargar librería -->
    <script src="https://cdn.jsdelivr.net/npm/editorjs-html-revised@3.3.0/build/edjsHTML.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            if (typeof window.edjsHTML !== 'function') {
                console.error("❌ edjsHTML no está disponible.");
                return;
            }

            const parser = edjsHTML({
                list: (block) => {
                    const tag = block.data.style === 'ordered' ? 'ol' : 'ul';
                    const items = block.data.items.map(item => `<li>${item}</li>`).join('');
                    return `<${tag}>${items}</${tag}>`;
                },
                checklist: (block) => {
                    const items = block.data.items.map(item => {
                        const checked = item.checked ? 'checked' : '';
                        return `<li><input type="checkbox" ${checked} disabled> ${item.text}</li>`;
                    }).join('');
                    return `<ul class="checklist">${items}</ul>`;
                },
                quote: (block) => {
                    return `<blockquote>${block.data.text} <footer>— ${block.data.caption}</footer></blockquote>`;
                },
                header: (block) => {
                    const level = block.data.level || 2;
                    return `<h${level}>${block.data.text}</h${level}>`;
                },
                paragraph: (block) => {
                    return `<p>${block.data.text}</p>`;
                }
            });

            const scriptTag = document.getElementById('post-description-json');

            try {
                const content = JSON.parse(scriptTag.textContent);
                const html = parser.parse(content);

                const container = document.createElement('div');
                container.classList.add('editorjs-content');
                container.innerHTML = html.join('');
                scriptTag.insertAdjacentElement('afterend', container);
            } catch (e) {
                console.error("⚠️ Error al procesar el contenido de Editor.js:", e);
            }
        });
    </script>

</main>