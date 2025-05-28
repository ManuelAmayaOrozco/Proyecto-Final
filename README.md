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
                - Ha de ser único.
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
            **RESTRICCIONES:**
                - Ha de ser una imagen.
                - Ha de ser formato jpeg, png o jpg.
                - No puede ser mayor de 2048px.
        - `isAdmin` **(Tipo: Boolean)**: Define si un usuario tiene permisos de administrador o no, permitiéndole acceder a ciertos menús y otros permisos que los usuarios normales no tienen.


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
        - `photo` **(Tipo: Array)**: Array con todas las direcciones de las fotos que le pertenecen al insecto.
            **RESTRICCIONES:**
                - No puede estar vacío.
                - Ha de venir en formato array.
                - Han de ser imágenes.
                - Han de ser formato jpeg, png o jpg.
                - No pueden ser mayor de 2048px.
        - `protectedSpecies` **(Tipo: Boolean)**: Define si el insecto está en peligro de extinción (true) o no (false).

3. **Tabla Insect_Photos**

   - Representa todas las imágenes que le pertenecen a un insecto.
   - Propiedades:
     - `id` **(Tipo: Long)**: El ID de la imagen correspondiente, autogenerado por la base de datos.
     - `insect_id` **(Tipo: Long)**: El ID del insecto al que le pertenece la imagen.
     - `path` **(Tipo: String)**: La ruta donde se encuentra almacenada la imagen.

4. **Tabla Posts**
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
        - `latitude` **(Tipo: Decimal)**: La latitud de las coordenadas donde se encontró el insecto del post.
            **RESTRICCIONES:**
                - Ha de ser numérico.
        - `longitude` **(Tipo: Decimal)**: La longitud de las coordenadas donde se encontró el insecto del post.
            **RESTRICCIONES:**
                - Ha de ser numérico.
        - `photo` **(Tipo: String)**: La dirección en donde se guarda la imagen del post dentro de los archivos del programa.
            **RESTRICCIONES:**
                - No puede estar vacío.
                - Ha de ser una imagen.
                - Ha de ser formato jpeg, png o jpg.
                - No puede ser mayor de 2048px.
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
        - `name` **(Tipo: String)**: El nombre de la etiqueta.

6. **Tabla Post_Tag**
    - Se trata de una tabla intermediaria usada en la relación de muchos a muchos entre las tablas de los posts y la tablas de las tags (etiquetas).
    - Propiedades:
        - `post_id` **(Tipo: Long)**: El ID del post en el que aparece esta etiqueta.
        - `tag_id` **(Tipo: Long)**: El ID de la etiqueta que aparece en este post.

7. **Tabla Favorites (Favoritos)**
    - Representa una instancia en la que un usuario ha añadido como favorito un post.
    - Propiedades:
        - `id` **(Tipo: Long)**: El ID de la instancia en la que el usuario añade el post como favorito.
        - `id_post` **(Tipo: String)**: El ID del post que ha sido marcado como favorito.
        - `id_user` **(Tipo: String)**: El ID del usuario que ha marcado al post como favorito.

8. **Tabla Likes**
    - Tabla que señala los posts que han recibido un like de un usuario.
    - Propiedades:
        - `id` **(Tipo: Long)**: El ID de la instancia en la que el usuario le da like a un post específico.
        - `user_id` **(Tipo: String)**: El ID del usuario que ha dado like al post.
        - `post_id` **(Tipo: String)**: El ID del post que ha recibido el like del usuario.

## **Endpoints**

1. **Endpoints para Usuarios**
    - **GET** `{users/login}`: Endpoint utilizado para llamar a la vista del login y mostrarla por pantalla.
      - *RUTA PÚBLICA*: Cualquier usuario puede acceder a este endpoint.
    - **GET** `{users/register}`: Endpoint utilizado para llamar a la vista del registro de usuarios y mostrarla por pantalla.
      - *RUTA PÚBLICA*: Cualquier usuario puede acceder a este endpoint.
    - **POST** `{users/login}`: Endpoint utilizado para realizar el login e iniciar sesión con un usuario en particular, enviando al usuario de vuelta a la página principal una vez realizado.
      - *RUTA PÚBLICA*: Cualquier usuario puede acceder a este endpoint.
    - **POST** `{users/register}`: Endpoint utilizado para registrar un nuevo usuario en la base de datos, enviando al usuario a la página del login una vez realizado.
      - *RUTA PÚBLICA*: Cualquier usuario puede acceder a este endpoint.
    - **GET** `{users/contact}`: Endpoint utilizado para llamar a la vista de contacto de la compañia y mostrarla por pantalla.
      - *RUTA PÚBLICA*: Cualquier usuario puede acceder a este endpoint.
    - **GET** `{users/profile}`: Endpoint utilizado para llamar a la vista del perfil del usuario actual y mostrarla por pantalla.
      - *RUTA PROTEGIDA* **AUTHENTICATED** Sólo usuarios correctamente autenticados pueden acceder a este recurso.
    - **GET** `{users/adminMenu}`: Endpoint utilizado para llamar a la vista del menú de administración de usuarios y mostrarlo por pantalla.
      - *RUTA PROTEGIDA* **AUTHENTICATED** Sólo usuarios correctamente autenticados pueden acceder a este recurso.
    - **PUT** `{users/makeAdmin/{id}`: Endpoint utilizado para dar permisos de administrador a un usuario, recarga la página justo después.
      - *RUTA PROTEGIDA* **AUTHENTICATED** Sólo usuarios correctamente autenticados pueden acceder a este recurso.
    - **PUT** `{users/ban/{id}`: Endpoint utilizado para banear un usuario, bloqueándolo de acceder a varias partes de la aplicación, recarga la página justo después.
      - *RUTA PROTEGIDA* **AUTHENTICATED** Sólo usuarios correctamente autenticados pueden acceder a este recurso.
    - **PUT** `{users/unban/{id}`: Endpoint utilizado para desbanear un usuario, permitiéndolo tener los permisos de un usuario normal nuevamente, recarga la página justo después.
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
    - **GET** `{users/email/verify}`: Endpoint utilizado para llamar a la vista de verificación de email de un usuario.
      - *RUTA PROTEGIDA* **AUTHENTICATED** Sólo usuarios correctamente autenticados pueden acceder a este recurso.
    - **GET** `{users/email/{id}/{hash}}`: Endpoint utilizado para completar la verificación de email de un usuario, activado en el botón que se envia por correo.
      - *RUTA PROTEGIDA* **AUTHENTICATED** Sólo usuarios correctamente autenticados pueden acceder a este recurso.
    - **POST** `{users/email/verification-notification}`: Endpoint utilizado para enviar/reenviar el correo de verificación de email de un usuario.
      - *RUTA PROTEGIDA* **AUTHENTICATED** Sólo usuarios correctamente autenticados pueden acceder a este recurso.

2. **Endpoints para Insectos**
    - **GET** `{insects/insectlist}`: Endpoint utilizado para llamar a la vista de la lista de los insectos y mostrarla por pantalla.
      - *RUTA PÚBLICA*: Cualquier usuario puede acceder a este endpoint.
    - **GET** `{insects/fullInsect/{id}}`: Endpoint utilizado para llamar a la vista de los datos completos de un insecto en específico y mostrarla por pantalla.
      - *RUTA PÚBLICA*: Cualquier usuario puede acceder a este endpoint.
    - **GET** `{insects/register}`: Endpoint utilizado para llamar a la vista del registro de insectos y mostrarla por pantalla.
      - *RUTA PROTEGIDA* **AUTHENTICATED** Sólo usuarios correctamente autenticados pueden acceder a este recurso.
    - **POST** `{insects/register}`: Endpoint utilizado para registrar un insecto en la base de datos, devolviendo el usuario a la lista actualizada de insectos justo después.
      - *RUTA PROTEGIDA* **AUTHENTICATED** Sólo usuarios correctamente autenticados pueden acceder a este recurso.
    - **GET** `{insects/update/{id}}`: Endpoint utilizado para llamar a la vista para actualizar un insecto específico y mostrarla por pantalla.
      - *RUTA PROTEGIDA* **AUTHENTICATED** Sólo usuarios correctamente autenticados pueden acceder a este recurso.
    - **PUT** `{insects/update/{id}}`: Endpoint utilizado para actualizar un insecto en la base de datos, devolviendo el usuario a la lista actualizada de insectos justo después.
      - *RUTA PROTEGIDA* **AUTHENTICATED** Sólo usuarios correctamente autenticados pueden acceder a este recurso.
    - **DELETE** `{insects/delete/{id}}`: Endpoint utilizado para eliminar un insecto en la base de datos, devolviendo el usuario a la lista actualizada de insectos justo después.
      - *RUTA PROTEGIDA* **AUTHENTICATED** Sólo usuarios correctamente autenticados pueden acceder a este recurso.

3. **Endpoints para Posts**
    - **GET** `{posts/postlist/{tagId?}}`: Endpoint utilizado para llamar a la vista de la lista de los posts y mostrarla por pantalla, incluye un parámetro opcional del ID de una etiqueta en caso de que se busque a través de una.
      - *RUTA PÚBLICA*: Cualquier usuario puede acceder a este endpoint.
    - **GET** `{posts/fullPost/{id}}`: Endpoint utilizado para llamar a la vista de los datos completos de un post en específico y mostrarla por pantalla.
      - *RUTA PÚBLICA*: Cualquier usuario puede acceder a este endpoint.
    - **GET** `{insects/update-daily-post}`: Endpoint utilizado para actualizar el post del día, llamado automáticamente diariamente.
      - *RUTA PÚBLICA*: Cualquier usuario puede acceder a este endpoint.
    - **GET** `{posts/register}`: Endpoint utilizado para llamar a la vista del registro de posts y mostrarla por pantalla.
      - *RUTA PROTEGIDA* **AUTHENTICATED** Sólo usuarios correctamente autenticados pueden acceder a este recurso.
    - **POST** `{posts/register}`: Endpoint utilizado para registrar un post en la base de datos, devolviendo el usuario a la lista actualizada de posts justo después.
      - *RUTA PROTEGIDA* **AUTHENTICATED** Sólo usuarios correctamente autenticados pueden acceder a este recurso.
    - **PUT** `{posts/like/{id}}`: Endpoint utilizado para que un usuario le de un like a un post, recarga la página justo después.
      - *RUTA PROTEGIDA* **AUTHENTICATED** Sólo usuarios correctamente autenticados pueden acceder a este recurso.
    - **PUT** `{posts/dislike/{id}}`: Endpoint utilizado para quitar el like de un usuario a un post, recarga la página justo después.
      - *RUTA PROTEGIDA* **AUTHENTICATED** Sólo usuarios correctamente autenticados pueden acceder a este recurso.
    - **PUT** `{posts/newFavorite/{id}}`: Endpoint utilizado para actualizar la tabla de favoritos y crear una nueva instancia de un favorito.
      - *RUTA PROTEGIDA* **AUTHENTICATED** Sólo usuarios correctamente autenticados pueden acceder a este recurso.
    - **PUT** `{posts/removeFavorite/{id}}`: Endpoint utilizado para actualizar la tabla de favoritos y eliminar una instancia de un favorito.
      - *RUTA PROTEGIDA* **AUTHENTICATED** Sólo usuarios correctamente autenticados pueden acceder a este recurso.
    - **GET** `{posts/update/{id}}`: Endpoint utilizado para llamar a la vista para actualizar un post específico y mostrarla por pantalla.
      - *RUTA PROTEGIDA* **AUTHENTICATED** Sólo usuarios correctamente autenticados pueden acceder a este recurso.
    - **PUT** `{posts/update/{id}}`: Endpoint utilizado para actualizar un post en la base de datos, devolviendo el usuario a la lista actualizada de posts justo después.
      - *RUTA PROTEGIDA* **AUTHENTICATED** Sólo usuarios correctamente autenticados pueden acceder a este recurso.
    - **DELETE** `{posts/delete/{id}}`: Endpoint utilizado para eliminar un post en la base de datos, devolviendo el usuario a la lista actualizada de posts justo después.
      - *RUTA PROTEGIDA* **AUTHENTICATED** Sólo usuarios correctamente autenticados pueden acceder a este recurso.
     
4. **Endpoints para Comentarios**
    - **GET** `{comments/register/{id}}`: Endpoint utilizado para llamar a la vista del registro de comentarios y mostrarla por pantalla, junto con el ID de su post correspondiente.
      - *RUTA PROTEGIDA* **AUTHENTICATED** Sólo usuarios correctamente autenticados pueden acceder a este recurso.
    - **POST** `{comments/register/{id}}`: Endpoint utilizado para registrar un comentario en la base de datos junto con el ID de su post correspondiente, devolviendo el usuario al post actualizado justo después.
      - *RUTA PROTEGIDA* **AUTHENTICATED** Sólo usuarios correctamente autenticados pueden acceder a este recurso.

5. **Otros endpoints**
    - **GET** `{error-prueba-404}`: Endpoint utilizado para simular un error 404.
      - *RUTA PÚBLICA*: Cualquier usuario puede acceder a este endpoint.
    - **GET** `{captcha-reload}`: Endpoint utilizado para recargar el captcha.
      - *RUTA PÚBLICA*: Cualquier usuario puede acceder a este endpoint.

## **Lógica de negocio**

1. **Tabla Users**

| Campo                   | Regla de Validación                                                       | Código HTTP  | Mensaje de Error                                                       |
|-------------------------|---------------------------------------------------------------------------|--------------|------------------------------------------------------------------------|
| `name`                  | No puede estar vacío.                                                     | 400          | "El nombre es obligatorio."                                            |
| `name`                  | Ha de ser String.                                                         | 400          | "El nombre ha de ser un String"                                        |
| `name`                  | No puede ser de más de 20 carácteres.                                     | 400          | "El nombre debe contener 20 carácteres máximo."                        |
| `name`                  | Ha de ser único.                                                          | 400          | "Ese nombre ya está en uso."                                           |
| `email`                 | No puede estar vacío.                                                     | 400          | "El email es obligatorio."                                             |
| `email`                 | Ha de tener el formato correcto.                                          | 400          | "El email ha de tener el formato correcto."                            |
| `email`                 | Ha de ser único.                                                          | 400          | "Ese email ya está en uso."                                            |
| `photo`                 | Ha de ser una imagen.                                                     | 400          | "La foto ha de ser una imagen."                                        |
| `photo`                 | Ha de ser formato jpeg, png o jpg.                                        | 400          | "La foto ha de ser jpg/png/jpg."                                       |
| `photo`                 | Ha de ser de 2040px como máximo.                                          | 400          | "La foto no puede ser mayor de 2048px."                                |
| `password`              | No puede estar vacía.                                                     | 400          | "La contraseña es obligatoria."                                        |
| `password`              | Ha de tener por lo menos 5 carácteres.                                    | 400          | "La contraseña debe contener 5 carácteres mínimo."                     |
| `password`              | No puede ser de más de 20 carácteres.                                     | 400          | "La contraseña debe contener 20 carácteres máximo."                    |
| `password`              | Ha de contener los carácteres correctos.                                  | 400          | "La contraseña debe contener una minúscula, una mayúscula y un dígito" |
| `password_repeat`       | No puede estar vacía.                                                     | 400          | "La contraseña repetida es obligatoria."                               |
| `password_repeat`       | Ha de ser igual a `password`.                                             | 400          | "La contraseña repetida ha de ser igual a la contraseña original."     |

2. **Tabla Insects**

| Campo                   | Regla de Validación                                                       | Código HTTP  | Mensaje de Error                                            |
|-------------------------|---------------------------------------------------------------------------|--------------|-------------------------------------------------------------|
| `name`                  | No puede estar vacío.                                                     | 400          | "El nombre es obligatorio."                                 |
| `name`                  | Ha de ser único.                                                          | 400          | "Ese nombre ya está en uso."                                |
| `scientificName`        | No puede estar vacío.                                                     | 400          | "El nombre científico es obligatorio."                      |
| `scientificName`        | Ha de ser único.                                                          | 400          | "Ese nombre científico ya está en uso."                     |
| `family`                | No puede estar vacía.                                                     | 400          | "El nombre de la familia es obligatorio."                   |
| `diet`                  | No puede estar vacía.                                                     | 400          | "El tipo de dieta es obligatorio."                          |
| `description`           | No puede estar vacía.                                                     | 400          | "La descripción es obligatoria."                            |
| `n_spotted`             | No puede estar vacío.                                                     | 400          | "El número de ejemplares vistos es obligatorio."            |
| `n_spotted`             | Ha de ser por lo menos 1.                                                 | 400          | "El número de ejemplares vistos no puede ser menor que 1."  |
| `maxSize`               | No puede estar vacío.                                                     | 400          | "El tamaño máximo documentado es obligatorio."              |
| `maxSize`               | Ha de ser por lo menos 0.01.                                              | 400          | "El tamaño máximo documentado no puede ser menor a 0.01cm." |
| `photo`                 | No puede estar vacía.                                                     | 400          | "La foto es obligatoria."                                   |
| `photo`                 | Han de venir en formato array.                                            | 400          | "Las fotos han de venir en formato array."                  |
| `photo`                 | Ha de ser una imagen.                                                     | 400          | "La foto ha de ser una imagen."                             |
| `photo`                 | Ha de ser formato jpeg, png o jpg.                                        | 400          | "La foto ha de ser jpg/png/jpg."                            |
| `photo`                 | Ha de ser de 2040px como máximo.                                          | 400          | "La foto no puede ser mayor de 2048px."                     |

3. **Tabla Posts**

| Campo                   | Regla de Validación                                                       | Código HTTP  | Mensaje de Error                                    |
|-------------------------|---------------------------------------------------------------------------|--------------|-----------------------------------------------------|
| `title`                 | No puede estar vacío.                                                     | 400          | "El título es obligatorio."                         |
| `title`                 | Ha de tener por lo menos 1 carácter.                                      | 400          | "El título ha de tener por lo menos un carácter."   |
| `title`                 | No puede ser de más de 50 carácteres.                                     | 400          | "El título no puede tener más de 50 carácteres."    |
| `description`           | No puede estar vacía.                                                     | 400          | "La descripción es obligatoria."                    |
| `photo`                 | No puede estar vacía.                                                     | 400          | "La imagen es obligatoria."                         |
| `photo`                 | Ha de ser una imagen.                                                     | 400          | "La foto ha de ser una imagen."                     |
| `photo`                 | Ha de ser formato jpeg, png o jpg.                                        | 400          | "La foto ha de ser jpg/png/jpg."                    |
| `photo`                 | Ha de ser de 2040px como máximo.                                          | 400          | "La foto no puede ser mayor de 2048px."             |
| `insect`                | No puede estar vacío.                                                     | 400          | "El insecto relacionado es obligatorio."            |
| `latitude`              | Ha de ser numérica.                                                       | 400          | "La latitud ha de ser numérica."                    |
| `longitude`             | Ha de ser numérica.                                                       | 400          | "La longitud ha de ser numérica."                   |

4. **Tabla Comments**

| Campo                   | Regla de Validación                                                       | Código HTTP  | Mensaje de Error                                    |
|-------------------------|---------------------------------------------------------------------------|--------------|-----------------------------------------------------|
| `comment`               | No puede estar vacío.                                                     | 400          | "El comentario es obligatorio."                     |

## **Códigos de Respuesta**

Operaciones exitosas:

- **201 Created**: Creación de recursos exitosos (POST excepto Login).
- **200 OK**: Consultas y actualizaciones exitosas (GET, PUT, Login POST)
- **204 No Content**: Eliminación de recursos exitosos (DELETE)

Operaciones fallidas:

- **400 Bad Request**: Errores de validación de la lógica de negocio.
- **401 Not Authorized Exception**: Errores de autorización si no tiene los roles adecuados.
- **404 Not Found**: Recursos inexistentes de la lógica de negocio.
- **500 Internal Server Error**: Cualquier otro error que ocurra dentro del servidor.

## **Restricciones de Seguridad**

1. **Usuarios:**
    - **GET** `{users/login}`: Cualquiera puede acceder login ya que es necesario para acceder a la aplicación.
    - **GET** `{users/register}`: Cualquiera puede acceder registro ya que es necesario para acceder a la aplicación.
    - **POST** `{users/login}`: Cualquiera puede hacer login ya que es necesario para acceder a la aplicación.
    - **POST** `{users/register}`: Cualquiera puede registrarse ya que es necesario para acceder a la aplicación si no tienes una cuenta creada.
    - **GET** `{users/contact}`: Cualquiera puede ver la página de contactos solo para informarse, aunque solo se puede usar el formulario si has hecho login previamente.
    - **GET** `{users/profile}`: Solo puedes acceder a tu perfil si tienes una sesión activa ya que se requiere para saber cuál es tu usuario.
    - **GET** `{users/adminMenu}`: Solo pueden acceder los administradores que tengan una sesión activa en ese momento.
    - **PUT** `{users/makeAdmin/{id}}`: Solo puede ser usada por un administrador con la sesión activa en ese momento.
    - **PUT** `{users/ban/{id}}`: Solo puede ser usada por un administrador con la sesión activa en ese momento.
    - **PUT** `{users/unban/{id}}`: Solo puede ser usada por un administrador con la sesión activa en ese momento.
    - **GET** `{users/update/{id}}`: Solo se puede acceder desde el perfil de usuario, el cuál requiere una sesión activa para saber cuál es el usuario.
    - **PUT** `{users/update/{id}}`: Solo se puede usar desde el perfil de usuario, el cuál requiere una sesión activa para saber cuál es el usuario.
    - **DELETE** `{users/logout/{id}}`: Solo puedes cerrar sesión si tienes una sesión abierta anteriormente por obvios motivos.
    - **DELETE** `{users/delete/{id}}`: Solo puedes eliminar tu propio usuario si tienes una sesión abierta para saber cuál es tu usuario.
    - **POST** `{users/contact}`: Solo puedes mandar un correo de contacto si tienes la sesión iniciada para saber cuál es el usuario que lo está haciendo.
    - **GET** `{users/email/verify}`: Solo los usuarios autenticados pueden acceder a la verificación del correo electrónico para así saber cuál usuario es el que ha de ser verificado.
    - **GET** `{users/email/verify/{id}/{hash}}`: Solo los usuarios autenticados pueden acceder a la verificación del correo electrónico para así saber cuál usuario es el que ha de ser verificado.
    - **GET** `{users/email/verification-notification}`: Solo los usuarios autenticados pueden acceder a la verificación del correo electrónico para así saber cuál usuario es el que ha de ser verificado.

2. **Insectos:**
    - **GET** `{insects/insectlist}`: Cualquiera puede mirar la lista de insectos para informarse sobre ellos.
    - **GET** `{insects/fullInsect/{id}}`: Cualquiera puede mirar la información detallada de un insecto específico para informarse sobre este.
    - **GET** `{insects/register}`: Solo los administradores que hayan iniciado sesión pueden acceder para registrar nuevos insectos ya que ellos son los que se encargan de modificar la lista de insectos.
    - **POST** `{insects/register}`: Solo los administradores que hayan iniciado sesión pueden registrar nuevos insectos ya que ellos son los que se encargan de modificar la lista de insectos.
    - **GET** `{insects/update/{id}}`: Solo los administradores que hayan iniciado sesión pueden acceder para actualizar insectos ya que ellos son los que se encargan de modificar la lista de insectos.
    - **PUT** `{insects/update/{id}}`: Solo los administradores que hayan iniciado sesión pueden actualizar insectos ya que ellos son los que se encargan de modificar la lista de insectos.
    - **DELETE** `{insects/delete/{id}}`: Solo los administradores que hayan iniciado sesión pueden eliminar insectos ya que ellos son los que se encargan de modificar la lista de insectos.
  
3. **Posts:**
    - **GET** `{posts/postlist/{tagId?}}`: Cualquiera puede mirar la lista de posts y buscar con los diferentes filtros.
    - **GET** `{posts/fullPost/{id}}`: Cualquiera puede mirar un post completo.
    - **GET** `{insects/update-daily-post}`: El post del día se actualiza por si solo de manera pública.
    - **GET** `{posts/register}`: Solo los usuarios que han iniciado sesión pueden acceder para registrar posts para así saber a cuál usuario le pertence el post.
    - **POST** `{posts/register}`: Solo los usuarios que han iniciado sesión pueden registrar posts para así saber a cuál usuario le pertence el post.
    - **PUT** `{posts/like/{id}}`: Solo los usuarios que han iniciado sesión pueden dar likes a los posts para así saber a cuál usuario le ha dado like a cada post.
    - **PUT** `{posts/dislike/{id}}`: Solo los usuarios que han iniciado sesión pueden quitar sus likes a los posts para así saber a cuál usuario ha quitado su like en cada post.
    - **PUT** `{posts/newFavorite/{id}}`: Solo los usuarios que han iniciado sesión pueden marcar posts como favoritos para saber cuál usuario lo ha marcado.
    - **PUT** `{posts/removeFavorite/{id}}`: Solo los usuarios que han iniciado sesión pueden desmarcar posts como favoritos para saber cuál usuario lo ha desmarcado.
    - **GET** `{posts/update/{id}}`: Solo los administradores y el usuario al que le pertenezca el post pueden acceder para actualizar un post específico.
    - **PUT** `{posts/update/{id}}`: Solo los administradores y el usuario al que le pertenezca el post pueden actualizar un post específico.
    - **DELETE** `{posts/delete/{id}}`: Solo el usuario autenticado que ha creado un post puede eliminar su propio post.

4. **Comentarios:**
    - **GET** `{comments/register/{id}}`: Solo los usuarios que han iniciado sesión pueden acceder para registrar comentarios para así saber a cuál usuario le pertence el comentario.
    - **GET** `{comments/register/{id}}`: Solo los usuarios que han iniciado sesión pueden registrar comentarios para así saber a cuál usuario le pertence el comentario.
  
5. **Otros:**
    - **GET** `{error-prueba-404}`: Cualquier usuario puede acceder a esta simulación de error 404 a través de los enlaces de las redes sociales, ya que dichas redes aún no existen.
    - **GET** `{captcha-reload}`: Cualquier usuario puede recargar el captcha en el formulario de login.
