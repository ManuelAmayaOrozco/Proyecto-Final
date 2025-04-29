# Proyecto Final: BugBuds
Creada por Manuel Amaya Orozco

---

## **Idea del Proyecto**

Se trata de una red social hecha expresamente para entomólogos, en la que ellos puedan subir posts/blogs a cerca de sus descubrimientos y discutir a cerca de ellos en los comentarios, ademas también se podrá almacenar información a cerca de los diversos insectos que se vayan descubriendo para que los varios investigadores puedan escribir a cerca de ellos, buscando los posts con filtros, añadiéndolos como favoritos, etc.

## **Justificación del Proyecto**

Considero que la idea tiene bastante promesa ya que el campo de la entomología está muy poco explotado al ser muy de nicho, además la base de la aplicación es muy maleable y se puede usar para crear otras varias redes sociales de otros temas variados, sin mencionar las posibles mejoras y añadiciones que se le puedan añadir al modelo.

## **Tablas**

1. **Tabla Users (Usuarios)**
    - Representa un usuario que podrá publicar posts, comentarios e insectos (si es administrador) e interaccionar con los diversos elementos de la web abiertamente.
    - Propiedades:
        - `id` **(Tipo: Long)**: El ID del usuario correspondiente, autogenerado por la base de datos.
        - `name` **(Tipo: String)**: El nombre del usuario.
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
            **RESTRICCIONES:**
                - No puede estar vacía.
                - Ha de tener por lo menos 5 carácteres.
                - No puede tener más de 20 carácteres.
                - Ha de incluir una letra minúscula, una letra mayúscula y un dígito.
        - `banned` **(Tipo: Boolean)**: Define si un usuario está baneado o no, los usuarios baneados no pueden utilizar diversas funciones aunque inicien sesión. Es 'false' por defecto, por lo que no está baneado a menos que se cambie.
        - `photo` **(Tipo: String)**: La dirección en donde se guarda la foto de perfil del usuario dentro de los archivos del programa, puede ser null ya que la foto de perfil es opcional.


2. **Tabla Insects (Insectos)**
    - Representa un insecto, cuya información es almacenada en la base de datos. Los usuarios han de apuntar a alguno de estos insectos en sus posts.
    - Propiedades:
        - `id` **(Tipo: Long)**: El ID del insecto correspondiente, autogenerado por la base de datos.
        - `registered_by` **(Tipo: Long)**: El ID del usuario que registró este insecto originalmente.
        - `name` **(Tipo: String)**: El nombre del insecto.
            **RESTRICCIONES:**
                - No puede estar vacío.
                - Ha de ser único.
        - `scientificName` **(Tipo: String)**: El nombre científico del insecto.
            **RESTRICCIONES:**
                - No puede estar vacío.
                - Ha de ser único.
        - `family` **(Tipo: String)**: La familia taxonómica a la que pertenece el insecto.
            **RESTRICCIONES:**
                - No puede estar vacía.
        - `diet` **(Tipo: String)**: El tipo de dieta del insecto.
            **RESTRICCIONES:**
                - No puede estar vacía.
        - `description` **(Tipo: String)**: Una breve descripción del insecto.
            **RESTRICCIONES:**
                - No puede estar vacío.
        - `n_spotted` **(Tipo: Int)**: El número de instancias documentadas del insecto.
            **RESTRICCIONES:**
                - No puede estar vacío.
                - No puede ser menor que 1.
        - `maxSize` **(Tipo: Double)**: El tamaño máximo documentado del insecto.
            **RESTRICCIONES:**
                - No puede estar vacío.
                - No puede ser menor que 0.01.
        - `protectedSpecies` **(Tipo: Boolean)**: Define si el insecto está en peligro de extinción (true) o no (false).
        - `photo` **(Tipo: String)**: La dirección en donde se guarda una imagen del insecto dentro de los archivos del programa.
            **RESTRICCIONES:**
                - No puede estar vacío.

3. **Tabla Posts**
    - Representa un post/blog publicado por un usuario específico.
    - Propiedades:
        - `id` **(Tipo: Long)**: El ID del post correspondiente, autogenerado por la base de datos.
        - `title` **(Tipo: String)**: El título del post.
            **RESTRICCIONES:**
                - No puede estar vacío.
                - Ha de tener por lo menos 1 carácter.
                - No puede tener más de 50 carácteres.
        - `description` **(Tipo: String)**: Una la descripción o texto principal del post.
            **RESTRICCIONES:**
                - No puede estar vacío.
        - `publish_date` **(Tipo: Timestamp)**: La fecha en la que el post fue publicada originalmente.
        - `n_likes` **(Tipo: Int)**: El número de likes que tiene el post.
        - `belongs_to` **(Tipo: Long)**: El ID del usuario al que le pertence este post.
        - `related_insect` **(Tipo: Long)**: El ID del insecto al que este post hace referencia.
            **RESTRICCIONES:**
                - No puede estar vacío.
        - `photo` **(Tipo: String)**: La dirección en donde se guarda la imagen del post dentro de los archivos del programa.
            **RESTRICCIONES:**
                - No puede estar vacío.
        - `dailyPost` **(Tipo: Boolean)**: Decide si este post es elegido como post del día (true) o no (false). Por defecto está puesto como 'false'.

4. **Tabla Comments (Comentarios)**
    - Representa el comentario dentro de un post, normalmente utilizado para añadir sugerencias o discutir algo del post.
    - Propiedades:
        - `id` **(Tipo: Long)**: El ID del comentario correspondiente, autogenerado por la base de datos.
         - `comment` **(Tipo: String)**: El texto principal del post.
            **RESTRICCIONES:**
                - No puede estar vacío.
         - `publish_date` **(Tipo: Timestamp)**: La fecha en la que el comentario fue publicado originalmente.
         - `user_id` **(Tipo: Long)**: El ID del usuario al que le pertence este comentario.
         - `post_id` **(Tipo: Long)**: El ID del post en el que aparece este comentario.

5. **Tabla Tags (Etiquetas)**
    - Representa una etiqueta utilizada por uno o varios posts, utilizadas para una búsqueda más fácil de posts específicos.
    - Propiedades:
        - `id` **(Tipo: Long)**: El ID de la etiqueta correspondiente, autogenerado por la base de datos.
        - `title` **(Tipo: String)**: El nombre de la etiqueta.

6. **Tabla Post_Tag**
    - Se trata de una tabla intermediaria usada en la relación de muchos a muchos entre las tablas de los posts y la tablas de las tags (etiquetas).
    - Propiedades:
        - `post_id` **(Tipo: Long)**: El ID del post en el que aparece esta etiqueta.
        - `tag_id` **(Tipo: Long)**: El ID de la etiqueta que aparece en este post.

## **Endpoints**

1. **Endpoints para Usuarios**
    - **GET** `{users/login}`: Endpoint utilizado para llamar a la vista del login y mostrarla por pantalla.
      - *RUTA PÚBLICA*: Cualquier usuario puede acceder a este endpoint.
    - **GET** `{users/register}`: Endpoint utilizado para llamar a la vista del registro y mostrarla por pantalla.
      - *RUTA PÚBLICA*: Cualquier usuario puede acceder a este endpoint.
    - **POST** `{users/login}`: Endpoint utilizado para realizar el login e iniciar sesión con un usuario en particular, enviando al usuario de vuelta a la página principal una vez realizado.
      - *RUTA PÚBLICA*: Cualquier usuario puede acceder a este endpoint.
    - **POST** `{users/login}`: Endpoint utilizado para registrar un nuevo usuario en la base de datos, enviando al usuario a la página del login una vez realizado.
    - **GET** `{users/contact}`: Endpoint utilizado para llamar a la vista de contacto de la compañia y mostrarla por pantalla.
      - *RUTA PÚBLICA*: Cualquier usuario puede acceder a este endpoint.
    - **GET** `{users/profile}`: Endpoint utilizado para llamar a la vista del perfil del usuario actual y mostrarla por pantalla.
      - *RUTA PROTEGIDA* **AUTHENTICATED** Sólo usuarios correctamente autenticados pueden acceder a este recurso.
    - **GET** `{users/update/{id}}`: Endpoint utilizado para llamar a la vista para actualizar un usuario específico.
      - *RUTA PROTEGIDA* **AUTHENTICATED** Sólo usuarios correctamente autenticados pueden acceder a este recurso.
    - **PUT** `{users/update/{id}}`: Endpoint utilizado para actualizar un usuario específico, enviando al usuario de vuelta a su perfil una vez el usuario ha sido actualizado.
      - *RUTA PROTEGIDA* **AUTHENTICATED** Sólo usuarios correctamente autenticados pueden acceder a este recurso.
    - **DELETE** `{users/logout/{id}}`: Endpoint utilizado para salir de la sesión del usuario actual, devolviéndolo a la página principal justo después.
      - *RUTA PROTEGIDA* **AUTHENTICATED** Sólo usuarios correctamente autenticados pueden acceder a este recurso.
    - **DELETE** `{users/delete/{id}}`: Endpoint utilizado para eliminar el usuario actual de la base de datos, devolviéndolo a la página principal justo después.
      - *RUTA PROTEGIDA* **AUTHENTICATED** Sólo usuarios correctamente autenticados pueden acceder a este recurso.
    - **POST** `{users/contact}`: Endpoint utilizado para mandar el correo de contacto a el email correspondiente.
      - *RUTA PROTEGIDA* **AUTHENTICATED** Sólo usuarios correctamente autenticados pueden acceder a este recurso.
