# Proyecto Final: BugBuds
Creada por Manuel Amaya Orozco

---

## **Idea del Proyecto**

Se trata de una red social hecha expresamente para entomólogos, en la que ellos puedan subir posts/blogs a cerca de sus descubrimientos y discutir a cerca de ellos en los comentarios, ademas también se podrá almacenar información a cerca de los diversos insectos que se vayan descubriendo para que los varios investigadores puedan escribir a cerca de ellos, buscando los posts con filtros, añadiéndolos como favoritos, etc.

## **Justificación del Proyecto**

Considero que la idea tiene bastante promesa ya que el campo de la entomología está muy poco explotado al ser muy de nicho, además la base de la aplicación es muy maleable y se puede usar para crear otras varias redes sociales de otros temas variados, sin mencionar las posibles mejoras y añadiciones que se le puedan añadir al modelo.

## **Tablas**

1. **Tabla Usuario**
    - Representa un usuario que podrá publicar posts, comentarios e insectos (si es administrador) e interaccionar con los diversos elementos de la web abiertamente.
    - Propiedades:
        - `id` **(Tipo: Long)**: El ID del usuario correspondiente, autogenerado por la base de datos.
        - `nombre` **(Tipo: String)**: El nombre del usuario.
            **RESTRICCIONES:**
                - No puede estar vacío.
                - Debe ser de tipo String.
                - No puede tener más de 20 carácteres.
        - `email` **(Tipo: String)**: El email del usuario.
            **RESTRICCIONES:**
                - No puede estar vacío.
                - Ha de tener el formato de email correcto.
                - Ha de ser único.
        - `email_verified_at` **(Tipo: Timestamp)**: Instancia en la que el email fue verificado por última vez.
        - `password` **(Tipo: String)**: La contraseña del usuario, se guarda hasheada en la base de datos.
            **RESTRICCIÓN:**
                - No puede estar vacía.
                - Ha de tener por lo menos 5 carácteres.
                - No puede tener más de 20 carácteres.
                - Ha de incluir una letra minúscula, una letra mayúscula y un dígito.
        - `banned` **(Tipo: Boolean)**: Define si un usuario está baneado o no, los usuarios baneados no pueden utilizar diversas funciones aunque inicien sesión. Es 'false' por defecto, por lo que no está baneado a menos que se cambie.
        - `photo` **(Tipo: String)**: La dirección en donde se guarda la foto de perfil del usuario dentro de los archivos del programa, puede ser null ya que la foto de perfil es opcional.


