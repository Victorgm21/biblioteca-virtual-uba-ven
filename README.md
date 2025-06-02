# Biblioteca Virtual UBA

Este proyecto es un sistema de biblioteca virtual desarrollado para la UBA (Universidad de Buenos Aires). Permite a los usuarios administrar y consultar libros digitales.

## Características Principales

*   **Autenticación de Usuarios:** Sistema de inicio de sesión para acceder a las funcionalidades de administración.
*   **Gestión de Libros:**
    *   **Añadir:** Permite agregar nuevos libros al sistema, incluyendo detalles como título, autor, categoría, y subir el archivo del libro (PDF) y su portada.
    *   **Editar:** Modificar la información de los libros existentes.
    *   **Eliminar:** Remover libros de la biblioteca.
*   **Búsqueda de Libros:** Funcionalidad para buscar libros por título, autor o categoría.
*   **Visualización de Libros:** Página dedicada para mostrar los detalles de un libro específico.
*   **Panel de Administración:** Interfaz para que los administradores gestionen el catálogo de libros.

## Tecnologías Utilizadas

*   **Frontend:** HTML, CSS, JavaScript
*   **Backend:** PHP
*   **Base de Datos:** SQL (el archivo `biblioteca_digital.sql` contiene el esquema)
*   **Servidor Web:** (No especificado, pero requiere un entorno que soporte PHP, como Apache o Nginx)

## Estructura del Proyecto

*   `index.html`: Página principal de la biblioteca.
*   `login.html`: Página de inicio de sesión.
*   `libro.html`: Página para mostrar detalles de un libro.
*   `api.php`: Maneja las solicitudes de la API para operaciones CRUD de libros.
*   `biblioteca_digital.sql`: Script SQL para crear la estructura de la base de datos.
*   `css/`: Contiene los archivos de estilos CSS.
    *   `administracion.css`, `buscar.css`, `index.css`, etc.: Estilos específicos para diferentes secciones.
*   `funciones_php/`: Scripts PHP con lógica de negocio.
    *   `auth-middleware.php`: Middleware para la autenticación.
    *   `buscarEnLaDB.php`, `editarLibroEnLaDB.php`, etc.: Funciones para interactuar con la base de datos.
*   `pages/`: Contiene las páginas HTML principales de la aplicación.
    *   `administracion.html`: Panel de administración.
    *   `buscar.html`: Página de búsqueda.
    *   `insert.html`, `edit.html`, `delete.html`: Formularios para la gestión de libros.
*   `scripts/`: Contiene los archivos JavaScript para la interactividad del frontend.
    *   `administracion/`, `buscar/`, `index/`: Scripts específicos para diferentes secciones.
*   `uploads/`: Directorio donde se almacenan los archivos de los libros (PDFs) y sus portadas.
*   `webfonts/`: Fuentes web utilizadas en el proyecto (FontAwesome).

## Instalación y Configuración (Guía Básica)

1.  **Servidor Web:** Asegúrate de tener un servidor web instalado y configurado (ej. Apache, XAMPP, WAMP, MAMP) con soporte para PHP.
2.  **Base de Datos:**
    *   Importa el archivo `biblioteca_digital.sql` en tu sistema de gestión de bases de datos MySQL (o compatible). Esto creará la tabla necesaria (`libros`) y otras estructuras.
    *   Verifica y actualiza las credenciales de conexión a la base de datos si es necesario. Estas suelen estar en los archivos PHP que realizan conexiones (probablemente dentro de `funciones_php/`).
3.  **Archivos del Proyecto:** Clona o descarga este repositorio y coloca los archivos en el directorio raíz de tu servidor web (ej. `htdocs` en Apache).
4.  **Permisos:** Asegúrate de que el directorio `uploads/` tenga permisos de escritura para que la aplicación pueda guardar los archivos subidos.
5.  **Acceso:** Abre tu navegador web y navega a la URL donde has alojado el proyecto (ej. `http://localhost/biblioteca-virtual-uba/`).

## Uso

*   **Página Principal (`index.html`):** Muestra los libros disponibles.
*   **Login (`login.html`):** Accede con tus credenciales para administrar los libros.
*   **Administración (`pages/administracion.html`):** Una vez logueado, puedes añadir, editar o eliminar libros.
*   **Búsqueda (`pages/buscar.html`):** Encuentra libros utilizando los filtros de búsqueda.

```
