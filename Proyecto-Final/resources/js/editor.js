import EditorJS from "@editorjs/editorjs";
import Header from "@editorjs/header";
import List from "@editorjs/list";
import Image from "@editorjs/image";
import Quote from "@editorjs/quote";
import Embed from "@editorjs/embed";

// Función global reutilizable
function assignProtectedSpeciesCheckbox() {
    const checkbox = document.getElementById('checkboxTerms');
    const hidden = document.getElementById('hiddenTerms');
    if (checkbox && hidden) {
        hidden.value = checkbox.checked ? '1' : '0';
    }
}

document.addEventListener('alpine:init', () => {
    Alpine.data('editor', (data = {}, readOnly = false) => ({
        open: false,
        editor: null,
        init() {
            console.log('here')
            this.editor = new EditorJS({
                holder: 'editor',
                minHeight: 20,
                inlineToolbar: ['link', 'bold', 'italic',],
                placeholder: 'Escribe tu post',
                data,
                readOnly,
                tools: {
                    header: {
                        class: Header,
                        inlineToolbar: true
                    },
                    list: {
                        class: List,
                        inlineToolbar: true
                    },
                    quote: {
                        class: Quote,
                        inlineToolbar: true,
                        shortcut: 'CMD+SHIFT+O',
                        config: {
                            quotePlaceholder: 'Enter a quote',
                            captionPlaceholder: 'Quote\'s author',
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
            })
        },
        beforeSend() {
            this.editor.save().then((data) => {
                document.getElementById('description').value = JSON.stringify(data);

                // Asignar checkbox solo si existe
                assignProtectedSpeciesCheckbox();

                document.getElementById('post-form').submit();
            }).catch((error) => {
                console.error('Error al guardar el contenido del editor:', error);
                alert('Hubo un error al guardar el post. Revisa la consola para más información.');
            });
        }
    }))
})