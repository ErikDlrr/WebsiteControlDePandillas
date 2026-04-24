
# Criminal Nexus

**Criminal Nexus** es una plataforma web académica orientada al registro, consulta y gestión de información relacionada con pandillas, zonas de incidencia y reportes ciudadanos.  
El proyecto integra módulos para distintos tipos de usuarios, generación de reportes y conexión con una base de datos MySQL.

> Proyecto desarrollado con fines académicos para la materia de Ingeniería de Software / desarrollo de sistemas web.

---

## Descripción general

El sistema busca centralizar información relacionada con grupos delictivos o pandillas mediante una aplicación web modular.  
Permite administrar datos, consultar información registrada, generar reportes y mantener una separación básica de roles dentro del sistema.

La arquitectura está organizada en tres capas principales:

- **Capa de presentación:** interfaz web para usuarios, administradores y consultores.
- **Capa de lógica:** scripts PHP encargados de validaciones, conexión, consultas y operaciones del sistema.
- **Capa de datos:** base de datos MySQL/MariaDB para almacenar usuarios, registros, reportes e información relacionada.

---

## Funcionalidades principales

- Inicio de sesión por tipo de usuario.
- Gestión de usuarios.
- Registro y consulta de información sobre pandillas.
- Módulo ciudadano para reportes o interacción básica.
- Módulo consultor para visualización de información.
- Módulo administrador para gestión del sistema.
- Generación de reportes en PDF.
- Conexión con base de datos MySQL/MariaDB.
- Estructura modular por carpetas.

---

## Roles del sistema

### Administrador
Tiene acceso a funciones de gestión, administración de información y control general del sistema.

### Consultor
Puede consultar información registrada y visualizar datos relevantes dentro del sistema.

### Ciudadano
Puede acceder a funciones limitadas, como el envío o consulta de reportes según el flujo definido en el proyecto.

---

## Tecnologías utilizadas

- **PHP**
- **HTML5**
- **CSS3**
- **JavaScript**
- **MySQL / MariaDB**
- **Apache**
- **Docker**
- **FPDF** para generación de reportes PDF

---

## Estructura general del proyecto

```text
criminal-nexus/
│
├── administrador/
│   └── Archivos del módulo administrador
│
├── ciudadano/
│   └── Archivos del módulo ciudadano
│
├── consultor/
│   └── Archivos del módulo consultor
│
├── ingreso/
│   └── Archivos relacionados con acceso o pruebas internas
│
├── database/
│   └── Scripts SQL de la base de datos
│
├── reportes/
│   └── Generación de reportes PDF
│
├── css/
│   └── Hojas de estilo
│
├── js/
│   └── Scripts del frontend
│
├── docker-compose.yml
├── Dockerfile
└── README.md
