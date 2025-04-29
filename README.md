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
            **RESTRICCIÓN:**
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
        - `photo` **(Tipo: String)**: La dirección en donde se guarda una foto del insecto dentro de los archivos del programa.
            **RESTRICCIONES:**
                - No puede estar vacío.
