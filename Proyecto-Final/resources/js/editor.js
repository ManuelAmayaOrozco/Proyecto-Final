import EditorJS from "@editorjs/editorjs";
import Header from "@editorjs/header";
import List from "@editorjs/list";
import Quote from "@editorjs/quote";
import Embed from "@editorjs/embed";

// Función global reutilizable
function assignProtectedSpeciesCheckbox() {
    const form = document.getElementById('post-form');
    if (!form) return;

    const checkbox = form.querySelector('#checkboxTerms');
    const hidden = form.querySelector('#hiddenTerms');

    if (checkbox && hidden) {
        hidden.value = checkbox.checked ? '1' : '0';
    }
}

document.addEventListener('alpine:init', () => {
    Alpine.data('editor', (data = {}, readOnly = false) => ({
        open: false,
        editor: null,
        latitude: '',
        longitude: '',
        showMap: false,

        init() {
            const holder = document.getElementById('editor');
            if (!holder) return; // 🚫 Si no hay editor, no continuar

            this.editor = new EditorJS({
                holder: 'editor',
                minHeight: 20,
                inlineToolbar: ['link', 'bold', 'italic'],
                placeholder: 'Introduce la Descripción',
                data,
                readOnly,
                tools: {
                    header: { class: Header, inlineToolbar: true },
                    list: { class: List, inlineToolbar: true },
                    quote: {
                        class: Quote,
                        inlineToolbar: true,
                        shortcut: 'CMD+SHIFT+O',
                        config: {
                            quotePlaceholder: 'Introduce una cita',
                            captionPlaceholder: 'Autor de la cita',
                        },
                    },
                    embed: {
                        class: Embed,
                        config: {
                            services: {
                                youtube: true,
                                twitter: true,
                                instagram: true,
                                facebook: true,
                            }
                        }
                    },
                },
            });
        },

        toggleMap(event) {
            this.showMap = event.target.checked;

            const mapContainer = document.getElementById('map-container');
            if (!this.showMap) {
                mapContainer.style.display = 'none';
                document.getElementById('latitude').value = '';
                document.getElementById('longitude').value = '';
            } else {
                mapContainer.style.display = 'block';
                setTimeout(() => {
                    if (window.map) window.map.invalidateSize();
                }, 200);
            }
        },

        beforeSend() {
            if (!this.editor) {
                assignProtectedSpeciesCheckbox();
                document.getElementById('post-form').submit();
                return;
            }

            this.editor.save().then((data) => {
                // Esperar al siguiente "frame" del navegador
                requestAnimationFrame(() => {
                    const descInput = document.getElementById('description');
                    if (!descInput) {
                        console.error('⚠️ El campo #description no existe.');
                        alert('No se pudo encontrar el campo de descripción en el formulario.');
                        return;
                    }

                    descInput.value = JSON.stringify(data);

                    // Verificamos si los elementos de latitude y longitude existen antes de asignarles valores
                    const latitudeInput = document.getElementById('latitude');
                    const longitudeInput = document.getElementById('longitude');

                    if (latitudeInput && longitudeInput) {
                        if (!this.showMap) {
                            latitudeInput.value = '';
                            longitudeInput.value = '';
                        }
                    }

                    assignProtectedSpeciesCheckbox();

                    document.getElementById('post-form').submit();
                });
            }).catch((error) => {
                console.error('❌ Error al guardar el contenido del editor:', error);
                alert('Hubo un error al guardar el contenido.');
            });
        }
    }))
})