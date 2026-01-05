# Proyecto Videoclub PHP 3.0 - FINAL
> Referencia: [Proyecto Videoclub 3.0 - Aitor Medrano](https://aitor-medrano.github.io/dwes2122/04web.html#proyecto-videoclub-30)

<img width="1920" height="1080" alt="banner_videoclub_3" src="https://github.com/user-attachments/assets/fc94d798-a6b3-4387-a041-300fbd08237f" />

> Imagen del proyecto 3.0 en producción - páginas login (izquierda) y panel de admin (derecha)

## Realizado por:
### Darío Muñoz Rodríguez (2 DAW)
### Yago García Alonso (2 DAW)
---
## Idea del Proyecto

La nueva versión del proyecto videoclub incluye un uso más avanzado de la clase Videoclub, agregando gestión de usuarios con un inicio de sesión propio y panel de administración con una vista general del Videoclub.

## Desarrollo
### Stack Tecnológico

**Backend:**
- **PHP 7.4+**: Programación orientada a objetos, gestión de excepciones, autoloading y namespaces para una estructura modular y escalable
- **Composer**: Gestor de dependencias para PHP (autoload PSR-4, gestión de librerías)
- **Monolog**: Sistema de logging profesional para registro de operaciones y auditoría
- **PHPUnit**: Framework de testing para pruebas unitarias y de integración

**Frontend:**
- **HTML5**: Estructura semántica y accesible de la aplicación
- **CSS3**: Estilos base y personalizados de la aplicación
- **JavaScript ES6+**: Interactividad, validaciones y mejoras visuales
- **Bootstrap 5.3.8**: Framework principal para diseño responsivo, componentes UI, alertas y tarjetas (cards)
- **Bootstrap Icons**: Librería de iconos para mejora visual de la interfaz

**Base de Datos:**
- Sesiones PHP: Almacenamiento de estado de usuario durante la sesión
- Arrays asociativos: Sincronización de datos en memoria durante la sesión

**Inteligencia Artificial utilizada:**
> A favor de la IA responsable, se ha utilizado la IA con fines de documentación y corrección de errores a modo de aprendizaje, favoreciendo desarrollar aplicaciones reales listas para una producción

> Todo el código generado por la IA ha sido revisado antes de ser implementado...
 
- **GitHub Copilot**: Asistente de IA para acelerar el desarrollo, refactorizar código, mejorar la legibilidad y aplicar buenas prácticas de programación


### Funcionalidades Implementadas

- **Autenticación y Control de Acceso**: Sistema de login con validación de credenciales y sesiones PHP. Diferenciación de permisos entre administrador y clientes normales. Control de acceso mediante redirecciones seguras.
- **Gestión de socios/clientes**: 
  - Alta de nuevos clientes (solo administrador)
  - Baja de clientes (solo administrador, no permite eliminar el administrador principal)
  - Edición de perfil: administrador puede editar cualquier cliente, clientes pueden editar su propio perfil
  - Listado completo con paginación y visualización de datos
  - Visualización de alquileres activos por cliente
- **Gestión de soportes/productos**: 
  - Listado de productos (CintaVideo, Dvd, Juego) con indicador visual de disponibilidad/alquiler
  - Visualización de detalles técnicos según tipo de producto
  - Gestión de cupo de alquileres por tipo de producto
- **Panel de Administrador**: 
  - Dashboard con vista general de estadísticas (total de clientes, productos, alquileres)
  - Listado de clientes con opciones de edición y eliminación
  - Listado de productos con estado de disponibilidad
  - Control de permisos y acciones exclusivas
- **Panel de Cliente**: 
  - Visualización personal de alquileres actuales
  - Acceso a edición de perfil personal
  - Vista de cupo utilizado vs máximo permitido
- **Alquiler y devolución**: 
  - Sistema de alquiler con control de cupo máximo por cliente
  - Control de disponibilidad de productos (no permitir alquilar productos ya alquilados)
  - Sistema de devolución con liberación automática de cupo
  - Manejo robusto de excepciones de negocio
- **Validación de formularios**: 
  - Validaciones en servidor con mensajes de error detallados
  - Rellenado automático de formularios en caso de error
  - Feedback visual mediante alertas Bootstrap
- **Sincronización de datos**: 
  - Mantiene consistencia entre objetos Videoclub y arrays asociativos de sesión
  - Persistencia de datos durante la sesión
  - Actualización en tiempo real de cambios
- **Frontend responsivo**: 
  - Interfaz completamente responsiva con Bootstrap 5
  - Alertas flash para operaciones exitosas y errores
  - Tarjetas (cards) para presentación elegante de datos
  - Iconos Bootstrap Icons para mejor UX
  - Tema visual cohesivo y profesional
- **Excepciones personalizadas**: 
  - Manejo granular de errores de negocio
  - Clases específicas: `ClienteNoEncontradoException`, `SoporteNoEncontradoException`, `CupoSuperadoException`, `SoporteYaAlquiladoException`
  - Stack de excepciones base para control centralizado
- **Logging y auditoría**: 
  - Sistema de logging con Monolog
  - Registro de operaciones importantes en ficheros de log
  - Seguimiento de alquileres y operaciones del sistema
- **Documentación exhaustiva**: 
  - El código está comentado en profundidad
  - Explicación clara de lógica compleja
  - Facilita comprensión y mantenimiento futuro

## Estructura del Proyecto
### Jerarquía de Archivos y Carpetas

```
� proyecto-videoclub-php/
├── 📂 app/
│   └── 📁 Dwes/
│       └── 📁 ProyectoVideoclub/
│           ├── 📄 Videoclub.php             (Clase principal del dominio)
│           ├── 📄 Cliente.php                (Clase de cliente/socio)
│           ├── 📄 Soporte.php                (Clase abstracta de productos)
│           ├── 📄 CintaVideo.php             (Tipo: cinta de video)
│           ├── 📄 Dvd.php                    (Tipo: DVD)
│           ├── 📄 Juego.php                  (Tipo: juego)
│           ├── 📄 Resumible.php              (Interfaz de resumen)
│           ├── 📁 Exception/
│           │   ├── 📄 VideoclubException.php
│           │   ├── 📄 ClienteNoEncontradoException.php
│           │   ├── 📄 ClienteNoExisteException.php
│           │   ├── 📄 SoporteNoEncontradoException.php
│           │   ├── 📄 SoporteYaAlquiladoException.php
│           │   └── 📄 CupoSuperadoException.php
│           └── 📁 Util/
│               ├── 📄 LogFactory.php
│               ├── 📄 LogInterface.php
│               └── 📄 (excepciones duplicadas)
├── 📂 test/
│   ├── 📄 inicio.php                        (Demo 1)
│   ├── 📄 inicio2.php                       (Demo 2)
│   ├── 📄 inicio3.php                       (Demo 3)
│   ├── 📄 bootstrap.php
│   ├── 📄 BlurayTest.php
│   ├── 📄 CintaVideoTest.php
│   ├── 📄 ClienteTest.php
│   ├── 📄 DvdTest.php
│   ├── 📄 ExampleTest.php
│   ├── 📄 JuegoTest.php
│   ├── 📄 SoporteTest.php
│   ├── 📄 VideoclubTest.php
│   └── 📄 run_videoclub_test.php
├── 📂 vendor/                               (Dependencias de Composer)
│   ├── 📁 scripts/
│   │   ├── 📄 bootstrap.min.js
│   │   └── 📄 theme.js
│   ├── 📁 styles/
│   │   ├── 📄 bootstrap.min.css
│   │   └── 📄 custom.css
│   ├── 📄 autoload.php
│   └── ... (otras dependencias)
├── 📂 logs/                                 (Archivos de log de Monolog)
│   └── 📄 videoclub.log
├── 📂 coverage/                             (Reporte de cobertura PHPUnit)
│   └── ... (archivos HTML)
├── 📄 index.html                            (Página de bienvenida inicial)
├── 📄 index.php                             (Punto de entrada principal)
├── 📄 login.php                             (Procesamiento de login)
├── 📄 logout.php                            (Cierre de sesión)
├── 📄 home.php                              (Página de bienvenida post-login)
├── 📄 main.php                              (Router de contenido)
├── 📄 mainAdmin.php                         (Panel de administrador)
├── 📄 mainCliente.php                       (Panel de cliente)
├── 📄 formCreateCliente.php                 (Formulario crear cliente)
├── 📄 createCliente.php                     (Procesar crear cliente)
├── 📄 formUpdateCliente.php                 (Formulario editar cliente)
├── 📄 updateCliente.php                     (Procesar editar cliente)
├── 📄 removeCliente.php                     (Procesar eliminar cliente)
├── 📄 autoload.php                          (Cargador automático PSR-4)
├── 📄 composer.json                         (Definición del proyecto)
├── 📄 composer.lock                         (Lock de dependencias)
├── 📄 phpunit.xml                           (Configuración PHPUnit)
├── 📄 README.md                             (Documentación principal)
├── 📄 .gitignore                            (Archivos ignorados por git)
├── 📄 .git/                                 (Repositorio git)
└── ... (otros archivos)
```

### Descripción de Componentes

**Estructura del Dominio (app/Dwes/ProyectoVideoclub/):**
- **app/Dwes/ProyectoVideoclub/**: Contiene la lógica principal y las clases del dominio de negocio.
  - `Videoclub.php`: Clase principal que gestiona socios, productos y alquileres. Incluye métodos para:
    - Gestión de productos (incluir, obtener, listar)
    - Gestión de socios/clientes (incluir, obtener, buscar)
    - Control de alquileres (alquilar, devolver)
    - Estadísticas y logging
  - `Cliente.php`: Representa un cliente/socio del videoclub con propiedades de:
    - Datos personales (nombre, número, usuario, contraseña)
    - Control de alquileres (cupo máximo, productos alquilados)
    - Métodos para alquilar, devolver y consultar estado
  - `Soporte.php`: Clase base abstracta para productos alquilables con propiedades comunes:
    - Identificador único
    - Precio y disponibilidad
    - Tipo de producto (de clase hija)
  - `CintaVideo.php`: Tipo específico de soporte con propiedades de duración en minutos
  - `Dvd.php`: Tipo específico de soporte con propiedades de idiomas disponibles y relación de aspecto
  - `Juego.php`: Tipo específico de soporte con propiedades de plataforma y cupos de alquiler
  - `Resumible.php`: Interfaz para productos que pueden ser alquilados y devueltos, define método `muestraResumen()`
- **app/Dwes/ProyectoVideoclub/Exception/**: Excepciones personalizadas para manejo de errores de negocio
  - `VideoclubException.php`: Clase base para todas las excepciones del sistema
  - `ClienteNoEncontradoException.php`: Lanzada cuando un cliente solicitado no existe
  - `ClienteNoExisteException.php`: Lanzada cuando se intenta crear un cliente duplicado
  - `SoporteNoEncontradoException.php`: Lanzada cuando un producto solicitado no existe
  - `SoporteYaAlquiladoException.php`: Lanzada cuando se intenta alquilar un producto ya alquilado
  - `CupoSuperadoException.php`: Lanzada cuando un cliente alcanza su límite de alquileres
- **app/Dwes/ProyectoVideoclub/Util/**: Utilidades y servicios del sistema
  - `LogFactory.php`: Factory para crear loggers Monolog configurados
  - `LogInterface.php`: Interfaz para servicios que requieren logging
  - Excepciones duplicadas en Util (compatibilidad y retrocompatibilidad)

**Archivos de Autenticación:**
- `index.html`: Página de bienvenida inicial con información sobre credenciales de prueba disponibles
- `index.php`: Punto de entrada principal. Redirige a login si no hay sesión activa
- `login.php`: Procesa credenciales, valida usuario contra la sesión Videoclub, establece sesión PHP y carga datos iniciales
- `logout.php`: Destruye la sesión completamente y redirige al formulario de login

**Archivos de Gestión de Paneles:**
- `home.php`: Página de bienvenida post-login. Contiene tabs para:
  - Acceso a tests (inicio.php, inicio2.php, inicio3.php)
  - Información sobre el proyecto
  - Enlaces a documentación
- `main.php`: Página principal de distribución de contenido según permisos del usuario
- `mainAdmin.php`: Panel exclusivo del administrador con:
  - Dashboard de estadísticas (clientes totales, productos, estado)
  - Listado de clientes en cards con opciones de editar/eliminar
  - Listado de productos con badges de disponibilidad
  - Botones de acción para gestión
- `mainCliente.php`: Panel personal del cliente con:
  - Saludo personalizado
  - Visualización de alquileres actuales
  - Indicador de cupo utilizado vs máximo
  - Botón de edición de perfil

**Archivos de Gestión de Clientes:**
- `formCreateCliente.php`: Formulario para crear nuevo cliente (solo accesible al admin)
  - Validación de campos obligatorios
  - Campos: nombre, usuario, contraseña, máximo de alquileres
  - Manejo de errores con rellenado automático de formulario
- `createCliente.php`: Procesa la creación de nuevo cliente:
  - Valida datos enviados desde el formulario
  - Verifica que el usuario no exista
  - Crea cliente en el objeto Videoclub
  - Sincroniza con array de sesión
  - Genera mensaje flash de éxito
- `formUpdateCliente.php`: Formulario para editar datos de cliente:
  - Admin puede editar cualquier cliente
  - Cliente solo puede editar su propio perfil
  - Campos editables: nombre, usuario, contraseña, cupo (solo admin)
  - Origen inteligente (redirige a panel desde donde vino)
- `updateCliente.php`: Procesa la actualización de datos:
  - Valida cambios realizados
  - Sincroniza cambios en objeto Videoclub y arrays de sesión
  - Actualiza sesión del usuario si es autoeditción
  - Genera mensajes de feedback
- `removeCliente.php`: Procesa la eliminación de cliente:
  - Solo admin puede eliminar
  - Protección: no permite eliminar al administrador principal (socio 1)
  - Eliminación del objeto Videoclub y arrays
  - Confirmación JavaScript antes de eliminar

**Archivos de Prueba y Testing:**
- `test/inicio.php`: Demo 1 - Muestra ejemplos de creación de soportes y su visualización con Bootstrap
- `test/inicio2.php`: Demo 2 - Demuestra alquiler y devolución de productos, control de excepciones
- `test/inicio3.php`: Demo 3 - Showcase completo con múltiples operaciones y rendimiento de datos

**Archivos de Configuración:**
- `autoload.php`: Carga automática de clases usando PSR-4 namespace
- `composer.json`: Definición de proyecto, dependencias y autoload de Composer
- `composer.lock`: Lock file para asegurar versiones consistentes de dependencias
- `phpunit.xml`: Configuración de PHPUnit para pruebas unitarias
- `README.md`: Documentación completa del proyecto
- `.gitignore`: Archivos ignorados por git (vendor, logs, coverage, etc.)

**Recursos Frontend:**
- `vendor/styles/`: 
  - `bootstrap.min.css`: Framework Bootstrap minificado
  - `custom.css`: Estilos personalizados de la aplicación
- `vendor/scripts/`:
  - `bootstrap.min.js`: JavaScript de Bootstrap minificado
  - `theme.js`: Scripts personalizados del tema

**Archivos de Logging:**
- `logs/`: Directorio donde se guardan los archivos de log de Monolog
- `coverage/`: Reporte de cobertura de tests generado por PHPUnit

## Estructura de Ramas y Tags

- **Ramas principales**:
	- `main`: Rama principal y estable. Contiene el código de producción final (versión 3.0).
    - `develop`: Rama de desarrollo y staging (pre-producción)

- **Ramas de desarrollo**:
	- `B-Dario`: Rama de desarrollo para Darío, donde se han realizado la interfaz web y el sistema de autenticación
    - `B-Yago`: Rama de desarrollo para Yago, contiene la lógica inicial del dominio

- **Tags**:
	- Se han utilizado tags para marcar versiones importantes y entregas parciales del proyecto
	- Facilita el seguimiento de la evolución y los hitos alcanzados
	- Última versión estable: 3.0 (FINAL)

## Mejoras e Implementaciones de la Versión 3.0

### Mejoras en el Sistema de Autenticación
- Sistema de login seguro con validación de credenciales
- Control de acceso basado en roles (admin/cliente)
- Manejo de sesiones PHP con cookies seguras
- Redirecciones automáticas según permisos

### Mejoras en la Interfaz de Usuario
- Diseño responsive con Bootstrap 5.3.8
- Tema visual Brite de Bootswatch
- Alertas flash para feedback de operaciones
- Cards y componentes visuales mejorados
- Iconos Bootstrap Icons para mejor UX
- Formularios con validación visual

### Mejoras en la Gestión de Datos
- Sincronización bidireccional entre objetos y sesiones
- Persistencia de datos durante la sesión del usuario
- Manejo seguro de arrays asociativos
- Validación exhaustiva de datos entrada

### Mejoras en el Control de Errores
- Excepciones personalizadas específicas del negocio
- Manejo granular de errores con mensajes descriptivos
- Validación en servidor (no solo en cliente)
- Prevención de eliminaciones no autorizadas

### Mejoras en la Documentación
- Comentarios detallados en cada método y función
- Explicación de lógica compleja
- Documentación de parámetros y retornos
- README comprensivo con guías de uso

### Características de Seguridad
- Protección contra acceso no autorizado
- Validación de permisos en cada acción
- Prevención de eliminación del admin principal
- Sanitización de entrada HTML (htmlspecialchars)
- Confirmación JavaScript en acciones destructivas

### Características de Usabilidad
- Mensajes de error contextuales y claros
- Rellenado automático de formularios en caso de error
- Navegación intuitiva entre paneles
- Interfaz accesible y fácil de usar
- Origen inteligente para redirecciones post-acción

## Cómo ejecutar el proyecto

### Requisitos previos
- **XAMPP** o servidor PHP local (PHP 7.4+)
- **Composer** instalado en el sistema
- **Navegador web** moderno (Chrome, Firefox, Safari, Edge)

### Instalación y ejecución

1. **Clona el repositorio** en tu entorno local:
   ```bash
   git clone <repositorio-url>
   cd proyecto-videoclub-php
   ```

2. **Instala las dependencias** con Composer:
   ```bash
   composer install
   composer dump-autoload
   ```

3. **Configura el servidor**:
   - Si usas XAMPP, coloca el proyecto en `htdocs/proyecto-videoclub-php`
   - Inicia Apache y MySQL (si es necesario)
   - Asegúrate de que PHP tiene permisos de escritura en el directorio `logs/`

4. **Accede a la aplicación**:
   - Abre tu navegador web
   - Ve a `http://localhost/proyecto-videoclub-php/` o `http://localhost/proyecto-videoclub-php/index.html`
   - O directamente a `http://localhost/proyecto-videoclub-php/index.php`

### Credenciales de prueba

El sistema incluye usuarios predefinidos para testing:

**Administrador:**
- Usuario: `admin`
- Contraseña: `admin`
- Acceso: Panel de administración con control total

**Clientes normales:**
- Usuario: `bruce` / Contraseña: `gotham` (Bruce Wayne)
- Usuario: `clark` / Contraseña: `dailyplanet` (Clark Kent)
- Usuario: `diana` / Contraseña: `amazon` (Diana Prince)
- Usuario: `usuario` / Contraseña: `usuario` (Usuario de prueba)

### Pruebas de funcionalidad

Desde la página `home.php` (después de login) puedes acceder a:
- **test/inicio.php**: Demostración básica con creación de soportes
- **test/inicio2.php**: Demostración de alquileres y devoluciones
- **test/inicio3.php**: Demostración completa del sistema

### Ejecutar tests unitarios

Si tienes PHPUnit instalado vía Composer:

```bash
vendor/bin/phpunit --bootstrap vendor/autoload.php test
```

Para generar un reporte de cobertura:

```bash
vendor/bin/phpunit --bootstrap vendor/autoload.php --coverage-html coverage test
```

### Estructura de directorios a crear

Al ejecutar por primera vez, asegúrate de que existen:
```
logs/              # Para archivos de log de Monolog
```

Si no existen, créalos manualmente o con permisos de escritura.

## Arquitectura y Patrones de Diseño

### Patrones Utilizados

- **Patrón MVC (Model-View-Controller)**: 
  - Models: Clases de dominio (Videoclub, Cliente, Soporte, etc.)
  - Views: Archivos PHP con HTML y Bootstrap
  - Controllers: Archivos de procesamiento (login, createCliente, etc.)

- **Patrón Factory**: 
  - `LogFactory` para crear instancias de Logger configuradas

- **Patrón Singleton** (implícito): 
  - Instancia única de Videoclub en sesión

- **Patrón Builder/Fluent Interface**: 
  - Métodos que retornan `$this` para encadenamiento:
    ```php
    $vc->incluirSocio("Nombre", 3, "user", "pass")
       ->incluirCintaVideo("Película", 3.5, 107)
       ->incluirDvd("Película", 15, "es,en", "16:9");
    ```

- **Patrón Strategy**: 
  - Diferentes tipos de soportes (CintaVideo, Dvd, Juego) implementan Resumible

### Estructura de Namespaces

```
Dwes\
  ProyectoVideoclub\
    (Clases principales)
    Exception\
      (Excepciones de negocio)
    Util\
      (Utilidades y servicios)
```

### Flujo de Aplicación

```
index.php/index.html
    ↓
login.php (validación)
    ↓
home.php (bienvenida)
    ↓
main.php (router según rol)
    ├→ mainAdmin.php (si es admin)
    └→ mainCliente.php (si es cliente)
         ↓
    Gestión de clientes/productos
```

### Gestión de Sesiones

- `$_SESSION['user']`: Nombre del usuario logeado
- `$_SESSION['videoclub']`: Objeto Videoclub serializado
- `$_SESSION['soportes']`: Array de soportes para visualización rápida
- `$_SESSION['socios']`: Array de socios/clientes
- `$_SESSION['flash_*']`: Mensajes de feedback

### Seguridad Implementada

- Validación de sesión en cada página
- Redirecciones a login si no hay sesión
- Validación de permisos antes de acciones
- Sanitización HTML con `htmlspecialchars()`
- Protección contra auto-eliminación del admin
- Confirmación de acciones destructivas

## Créditos

**Desarrollado por:**
- **Darío Muñoz Rodríguez** - Versión 2.0 y 3.0 (interfaz web, autenticación, panel de administración)
- **Yago García Alonso** - Versión 1.0 (lógica de dominio, clases de negocio)

Ambos estudiantes de **2º DAW** (Desarrollo de Aplicaciones Web)

**Referencia base:**
Proyecto desarrollado siguiendo la guía y el estilo de documentación de [Aitor Medrano](https://aitor-medrano.github.io/dwes2122/03phpoo.html#proyecto-videoclub) para el curso de DWES (Desarrollo Web en Entorno Servidor).

**Librerías y frameworks utilizados:**
- [Bootstrap](https://getbootstrap.com/) - Framework CSS responsivo
- [Bootstrap Icons](https://icons.getbootstrap.com/) - Librería de iconos
- [Bootswatch Brite](https://bootswatch.com/brite/) - Tema visual profesional
- [Monolog](https://github.com/Seldaek/monolog) - Logging profesional
- [PHPUnit](https://phpunit.de/) - Framework de testing
- [Composer](https://getcomposer.org/) - Gestor de dependencias

**Herramientas de desarrollo:**
- Visual Studio Code - Editor de código
- GitHub Copilot - Asistencia en desarrollo
- XAMPP - Servidor local de desarrollo
- Git - Control de versiones

## Notas Importantes

### Versión Actual
Esta es la versión **3.0 FINAL** del Proyecto Videoclub PHP. Incluye todas las características de autenticación, gestión de usuarios y panel de administración completamente funcionales.

### Datos de Prueba
Todos los datos (clientes, productos, alquileres) se almacenan en sesión PHP durante la sesión activa. Los datos se reinician cuando se cierra la sesión o se cierra el navegador. No hay persistencia en base de datos.

### Mejoras Futuras Posibles
- Integración con base de datos (MySQL/PostgreSQL)
- Sistema de notificaciones por email
- Historial de alquileres persistente
- Reportes y estadísticas avanzadas
- API REST para integración mobile
- Paginación de listados largos
- Búsqueda y filtrado de productos
- Sistema de reservas de productos
- Cálculo de multas por demora

### Compatibilidad
- **PHP**: 7.4 o superior
- **Navegadores**: Chrome, Firefox, Safari, Edge (versiones modernas)
- **Servidores**: Apache, Nginx
- **Sistemas operativos**: Windows, Linux, macOS

### Licencia
Este proyecto está disponible bajo licencia libre para fines educativos.

---

**Última actualización**: Enero 2026  
**Versión**: 3.0 FINAL  
**Estado**: Producción
