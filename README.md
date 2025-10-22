# Proyecto Videoclub PHP
> Referencia: [Proyecto Videoclub - Aitor Medrano](https://aitor-medrano.github.io/dwes2122/03phpoo.html#proyecto-videoclub)

## Realizado por:
### Darío Muñoz Rodríguez (2 DAW)
### Yago García Alonso (2 DAW)
---
## Idea del Proyecto

El objetivo principal de este proyecto es simular la gestión de un videoclub, permitiendo administrar socios y soportes (cintas de vídeo, DVDs y juegos), así como el alquiler y devolución de estos productos. El sistema está desarrollado en PHP orientado a objetos, siguiendo buenas prácticas de diseño y estructura modular.

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

- **Gestión de socios**: Alta, baja y listado de clientes.
- **Gestión de soportes**: Alta y listado de productos (CintaVideo, Dvd, Juego).
- **Alquiler y devolución**: Permite alquilar y devolver productos, controlando el cupo máximo y el estado de los soportes.
- **Frontend**: Interfaz con Bootstrap 5, alertas para operaciones y errores, y visualización de productos y alquileres en tarjetas.
- **Excepciones personalizadas**: Manejo de errores de negocio con clases específicas.
- **Documentación y comentarios**: El código está comentado para facilitar su comprensión y mantenimiento.

## Estructura del Proyecto
### Jerarquía app - vendor - test

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

README.md
autoload.php
```

- **app/Dwes/ProyectoVideoclub/**: Contiene la lógica principal y las clases del dominio.
- **app/Dwes/ProyectoVideoclub/Util/**: Excepciones personalizadas.
- **test/**: Pruebas y scripts de ejemplo.
- **vendor/**: Dependencias externas (si las hubiera).

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
3. Accede a los archivos de inicio (`inicio.php`, `inicio2.php`, `inicio3.php`) para probar las funcionalidades.
4. Asegúrate de tener configurado el autoloading para cargar las clases correctamente.

## Créditos

Desarrollado por Yago García Alonso y Darío Muñoz Rodríguez siguiendo la guía y el estilo de documentación de [Aitor Medrano](https://aitor-medrano.github.io/dwes2122/03phpoo.html#proyecto-videoclub).