# Proyecto Videoclub PHP 3.0
> Referencia: [Proyecto Videoclub 3.0 - Aitor Medrano](https://aitor-medrano.github.io/dwes2122/04web.html#proyecto-videoclub-30)

<img width="1920" height="1080" alt="banner_videoclub_3" src="https://github.com/user-attachments/assets/fc94d798-a6b3-4387-a041-300fbd08237f" />

> Imagen del proyecto 3.0 en producción - páginas index.php (izquierda) y panel de admin (derecha)

<img width="1920" height="1080" alt="proyecto_videoclub_php_banner" src="https://github.com/user-attachments/assets/c4e39a64-5d24-4e37-a5b2-7c61b7a12971" />

> Imagen del proyecto 2.0 (página index.html)

## Realizado por:
### Darío Muñoz Rodríguez (2 DAW)
### Yago García Alonso (2 DAW)
---
## Idea del Proyecto

La nueva versión del proyecto videoclub incluye un uso más avanzado de la clase Videoclub, agregando gestión de usuarios con un inicio de sesión propio y panel de administración con una vista general del Videoclub.

## Desarrollo
### Stack Tecnológico

**Backend:**
- PHP 7+: Programación orientada a objetos, gestión de excepciones, autoloading y namespaces para una estructura modular y escalable.

**Frontend:**
- HTML5 y CSS3: Estructura y estilos base de la aplicación.
- JavaScript: Interactividad y mejoras visuales.
- Bootstrap 5: Framework principal para el diseño responsivo, alertas y tarjetas (cards) para la presentación de datos y mensajes.

**Inteligencia Artificial utilizada:**
> A favor de la IA responsable, se ha utilizado la IA con fines de documentación y corrección de errores a modo de aprendizaje, favoreciendo desarrollar aplicaciones reales listas para una producción

> Todo el código generado por la IA ha sido revisado antes de ser implementado...
 
- GitHub Copilot: Asistente de IA para acelerar el desarrollo, refactorizar código, mejorar la legibilidad y aplicar buenas prácticas de programación.


### Funcionalidades Implementadas

- **Autenticación y Control de Acceso**: Sistema de login con validación de credenciales y sesiones PHP. Diferenciación de permisos entre administrador y clientes normales.
- **Gestión de socios**: Alta, baja, edición y listado de clientes. Solo administrador puede crear/eliminar, cualquier usuario puede editar su perfil.
- **Gestión de soportes**: Listado de productos (CintaVideo, Dvd, Juego) con indicador visual de disponibilidad/alquiler.
- **Panel de Administrador**: Vista general con tarjetas de clientes y productos. Opciones para editar y eliminar clientes.
- **Panel de Cliente**: Visualización personal de alquileres actuales. Acceso a edición de perfil.
- **Alquiler y devolución**: Permite alquilar y devolver productos, controlando el cupo máximo y el estado de los soportes.
- **Validación de formularios**: Validaciones en servidor con mensajes de error detallados y rellenado automático de formularios.
- **Sincronización de datos**: Mantiene consistencia entre objetos Videoclub y arrays asociativos para flexibilidad.
- **Frontend responsivo**: Interfaz con Bootstrap 5, alertas flash para operaciones, tarjetas (cards) para presentación de datos, iconos Bootstrap.
- **Excepciones personalizadas**: Manejo de errores de negocio con clases específicas.
- **Documentación exhaustiva**: El código está comentado en profundidad para facilitar su comprensión y mantenimiento.

## Estructura del Proyecto
### Jerarquía de Archivos y Carpetas

```
📂 app/
	📁 Dwes/
		📁 ProyectoVideoclub/
			CintaVideo.php
			Cliente.php
			Dvd.php
			Juego.php
			Resumible.php
			Soporte.php
			Videoclub.php
			📁 Util/
				ClienteNoEncontradoException.php
				CupoSuperadoException.php
				SoporteNoEncontradoException.php
				SoporteYaAlquiladoException.php
				VideoclubException.php
📂 test/
    inicio.php
    inicio2.php
    inicio3.php
📂 vendor/
    📁scripts/
        bootstrap.min.js
        theme.js
    📁styles/
        bootstrap.min.css
        custom.css

autoload.php
createCliente.php
formCreateCliente.php
formUpdateCliente.php
home.php
index.php
login.php
logout.php
main.php
mainAdmin.php
mainCliente.php
removeCliente.php
updateCliente.php
README.md
TODO.md
```

### Descripción de Componentes

**Estructura del Dominio (app/Dwes/ProyectoVideoclub/):**
- **app/Dwes/ProyectoVideoclub/**: Contiene la lógica principal y las clases del dominio de negocio.
  - `Videoclub.php`: Clase principal que gestiona socios, productos y alquileres.
  - `Cliente.php`: Representa un cliente/socio del videoclub con sus datos personales y alquileres.
  - `Soporte.php`: Clase base abstracta para productos alquilables.
  - `CintaVideo.php`, `Dvd.php`, `Juego.php`: Tipos específicos de soportes/productos.
  - `Resumible.php`: Interfaz para productos que pueden ser alquilados y devueltos.
- **app/Dwes/ProyectoVideoclub/Util/**: Excepciones personalizadas para manejo de errores de negocio.

**Archivos de Prueba:**
- **test/**: Pruebas y scripts de ejemplo para verificar la lógica del dominio.

**Recursos Frontend:**
- **vendor/**: Dependencias externas (Bootstrap, scripts personalizados, estilos).

**Archivos de Autenticación:**
- `index.php`: Punto de entrada. Muestra el formulario de login.
- `login.php`: Procesa credenciales, valida usuario y establece la sesión.
- `logout.php`: Destruye la sesión y redirige al login.

**Archivos de Gestión de Panel:**
- `home.php`: Página de bienvenida post-login (redirige al panel correspondiente).
- `main.php`: Página principal para distribución de contenido según permisos.
- `mainAdmin.php`: Panel de administrador. Muestra listado de clientes y productos con opciones de edición/eliminación.
- `mainCliente.php`: Panel de cliente. Muestra sus alquileres actuales y opción para editar perfil.

**Archivos de Gestión de Clientes:**
- `formCreateCliente.php`: Formulario para crear nuevo cliente (solo admin).
- `createCliente.php`: Procesa la creación de nuevo cliente. Valida datos y actualiza sesión.
- `formUpdateCliente.php`: Formulario para editar datos de cliente (admin puede editar cualquier cliente, clientes solo editan su perfil).
- `updateCliente.php`: Procesa la actualización de datos del cliente. Sincroniza cambios en objeto Videoclub y arrays.
- `removeCliente.php`: Procesa la eliminación de cliente. Solo admin. Valida que no sea el administrador (socio 1).

**Archivos de Configuración:**
- `autoload.php`: Carga automática de clases usando PSR-4 namespace.
- `README.md`: Documentación del proyecto.
- `TODO.md`: Lista de tareas y mejoras pendientes.

## Estructura de Ramas y Tags

- **Ramas principales**:
	- `main`: Rama principal y estable.
    - `develop`: Rama de desarrollo conjunta (Versión previa a producción)

	- `B-Dario`: Rama de desarrollo para Darío, donde se han realizado la última parte (videoclub 2.0).
    - `B-Yago`: Rama de desarrollo para Yago, contiene el trabajo realizado proyecto videoclub 1.0

- **Tags**:
	- Se han utilizado tags para marcar versiones importantes y entregas parciales del proyecto, facilitando el seguimiento de la evolución y los hitos alcanzados.

## Cómo ejecutar el proyecto

1. Clona el repositorio en tu entorno local.
2. Configura un servidor local (por ejemplo, XAMPP) y sitúa el proyecto en la carpeta `htdocs`.
3. Accede al archivo index.php para probar la gestión de usuarios / o home.php para probar los tests de uso
4. Asegúrate de tener configurado el autoloading para cargar las clases correctamente.

### Usar Composer (recomendado)

Si clonas este repositorio, instala las dependencias y genera el autoload de Composer:

```bash
cd proyecto-videoclub-php
composer install
composer dump-autoload
```

Después puedes ejecutar los tests con:

```bash
vendor/bin/phpunit --bootstrap vendor/autoload.php test
```

Si no tienes Composer instalado, visita https://getcomposer.org/download/ para instrucciones.

## Créditos

Desarrollado por Yago García Alonso y Darío Muñoz Rodríguez siguiendo la guía y el estilo de documentación de [Aitor Medrano](https://aitor-medrano.github.io/dwes2122/03phpoo.html#proyecto-videoclub).
