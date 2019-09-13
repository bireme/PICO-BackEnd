# PICOSearcher

Versión 0.2

## Instalación

### Pre Requisitos

```
PHP ^7.2
Composer ^1.6
```

### Pasos de instalación

#### 1. Prerequisitos

Instalar PHP y composer en la Virtual Machine

#### 2. Adaptación

Ubicar en la carpeta deseada de la Virtual Machine

#### 3. Instalación en la VM e integración

**Se asume que:**
* Existen múltiples apps en la VM
* Todos los archivos de la VM están ocultos al público
* Todos los archivos de la VM están protegido en modo lectura
* Los archivos y folders visibles de cada app se asignan mediante un .htaccess
* Los archivos y folders modificables de cada app se asignan mediante un .htaccess
 
**Folders especiales de PICOSearch**
* El único folder que debe quedar visible es:
```
/public/* 
```
* El único folder que debe permitir escritura y modificación es:
```
/storage/* -> Contiene logs, sesiones y otros datos que se modifican
```


***NOTA IMPORTANTE***

UNA VEZ INSTALADA, ES INDISPENSABLE VERIFICAR QUE .ENV Y .ENV.EXAMPLE QUEDEN OCULTOS AL ACCESO DE LOS USUARIOS DESDE LA WEB

**Creación de un Virtual Host**

* Añadir en system32/drivers/etc/host/ unas líneas similares a estas
```
127.0.0.1      PICOSearch.localhost/
```

* Añadir en httpd-vhost.conf en la configuración de apache de la VM unas líneas similares a estas
```
<VirtualHost *:80>
ServerAdmin webmaster@localhost.com
DocumentRoot "C:\xampp\htdocs\home\apps\bvsalud.org\pesquisa\htdocs\pico\PICO-BackEnd"
ServerName PICOSearch.localhost
ErrorLog "logs/PICOSearch.localhost-error.log"
CustomLog "logs/PICOSearch.localhost-access.log" common
</VirtualHost>
```

**Instalación e integración**

* No debe existir en el root un server.php, sino un index.php. Si existe, cambiarle el nombre a index.php
* Se debe verificar que el htaccess esté en el root de la app y no en public
* Se deberá modificar el .htaccess segun lo necesario
* La carpeta /public/ quedará inaccesible y el su contenido será desplegado en picosearch.localhost/
* Para personalizar la adaptación, abrir .htacces en el folder root

#### 4. Primera instalación

**1. Crear el archivo base de configuración**

Copiar el archivo .env.example como .env, si no existe

**2. Clave de seguridad nueva**

Se debe generar una nueva clave que proteja a la aplicación de amenazas externas. Para eso se debe ejecutar el comando:
```
php artisan key:generate
```
**3. Modificar .env**

* Mostrar errores usando el logger de laravel
```
APP_DEBUG=false
```

* Ambiente de ejecución (alternativa, debug)
```
APP_ENV=production
```

* Usuarios de admins maestros
```
MASTER_ADMIN_DATA=MasterAdminEmail@server.com:MasterAdminPass
```

* Usuarios de admins
```
ADMIN_DATA=AdminEmail1@server.com:AdminPass,AdminEmail2@server.com:AdminPass2
```

<<<<<<< HEAD
#### 5. Borrar datos de logeo de los comónentes

* Realizar este paso cada vez que se instale la app
* Realizar este paso si ocurren errores inexplicables
* Borrar el contenido de la siguiente carpeta con toodos sus contenidos
```
/bootstrap/cache/*
```

#### 6. Archivos de Configuración

Se encuentran en la carpeta /config/ como archivos .php

* **analytics.php =>** Config de google analytics
* **languages.php =>** Lista de locales
* **logging.php =>** Drivers, ubicación, nivel y duración de los archivos de log  
* **Demás configs =>** Ver referencia en laravel

#### 7. Archivos de locale

Se encuentran en /resources/lang/ e incluye locales para cuatro idiomas, que se pueden modificar
* **en =>** Inglés
* **es =>** Español
* **fr =>** Francés
* **pt =>** Portugués


#### 8. Comandos de utilidad

Abriendo una instancia de la consola de la VM, se establece como directorio la carpeta base de la app 

**Borrar logs previos**
```
php artisan logs:clear
```

**Actualizar vínculos para IDE**
```
php artisan config:cache
composer dump-autoload
php artisan ide-helper:generate
```

**Desplegar app temporalmente usando server built-in (No usará VM)**
```
php artisan serve
```

## Proyecto

### Misión y visión

PICOSearcher busca proponer una solución digital que facilite el proceso de búsqueda de artículos científicos a través de la metodología PICO (Paciente, Intervención, Comparación, Outcomes) de una manera interactiva orientada a la experiencia de usuario y a la precisión de los datos entregados por la aplicación

PICOSearcher se alínea con la misión y visión de BIREME, y busca ser el buscador PICO de uso gratuito más usado y de mayor utilidad para los investigadores biomédicos

### Objetivos y metas

1. Ofrecer datos y resultados de utilidad para los investigadores biomédicos
*Los datos deben ser claros y concisos
*Los datos deben ser precisos y adecuados
*Los datos deben ser replicables

2. Ofrecer una excelente experiencia de usuario que permita al mismo identificar facilmente las funciones y obtener los mejores resultados
*Deben existir guías y ayudas
*Deben existir mensajes claros de error
*La interfaz debe ser atractiva

### Requerimientos funcionales

La app debe ser capaz de:
* Brindar ayudas de usuario y mensajes de error útiles y comprendibles
* Debe tener una interfaz atractiva
* Debe entregar datos precisos y de utlilidad para los investigadores biomédicos
* El algoritmo de número de resultados tiene que entregar el número de resultados para cada ecuación y para las intersecciones de P-I-C-O según se ha detallado previamente
* Se debe almacenar en la capa de presentación los datos ya obtenidos para evitar consumo adicional de recursos
* Se debe poder escoger fácilmente los DeCS importados
* Se debe poder generar una ecuación a partir de los DeCS importados
* El sistema de obtención de DeCS y generación de ecuaciones tiene que estar a prueba de fallos
* El sistema de obtención de DeCS deberá tener validez técnica y científica, comprobada y certificada por un stakeholder diferente al autor de la app.

### Requerimientos no funcionales

#### Funcionalidad

```
Adecuación ++
Exactitud ++
Interoperabilidad ++
Seguridad
```

#### Fiabilidad

```
Madurez
Recuperabilidad
Tolerancia a fallos +
```

#### Usabilidad

```
Aprendizaje ++
Entendibilidad ++
Operatividad ++
Atractividad +
```

#### Eficiencia

```
Comportamiento en el tiempo
Comportamiento de recursos +
```
	
#### Mantenibilidad

```
Estabilidad +
Facilidad de análisis +
Facilidad de cambio
Facilidad de pruebas +
```

#### Portabilidad

```
Capacidad de instalación
Adaptabilidad
Coexistencia
Remplazabilidad
```

#### Resumen

La arquitectura estará orientada a:
**1. Usabilidad**
**2. Funcionalidad**
**3. Mantenibilidad**

### Arquitectura de software

Se seleccionó una arquitectura en capas con MVC y microservicios unidos mediante singleton (Facades) y bind.

#### Caracteristicas

* Se dividió la arquitectura en las capas de presentación, negocio, integración y DTO. Todo desplegado en un único nodo, por petición de los stakeholders
* La presentación estará orientada a los NFR de usabilidad: atractibilidad, entendibilidad, aprendizaje
* El patrón MVC en la capa de negocio permite un enfoque en los modelos, donde yacen los datos. Favorece la funcionalidad al permitir un manejo de la adecuación y exactitud de los datos entregados a través de las reglas de validación de los modelos y de la gestión del DTO
* El patrón de microservicios, presente tanto en la capa de negocios como integración, permite a los controladores delegar su función, aumentando la mantebilidad y tolerancia a fallos, de modo que un fallo en un microservicio no genere una caída del sistema. Además aumenta la facilidad para encontrar errores y permite testear las entradas y salidas de cada microservicio, fortaleciendo la adecuación y exactitud de los datos entregados
* El patrón de consumo de web-service, presente en la capa de integración, y que enlaza con los web-service de BIREME, está orientado a la interoperabilidad, disponibilidad y tolerancia a fallos. El sistema de manejo de errores permite continuar la operación si los datos no se extran o no son correctos y además posee un algoritmo de Retry y loggeo


#### Lenguajes, frameworks y protocolos

* Se escogió Bootstrap para el Front-End por petición de los stakeholders, lo cual permitiría una mejor experiencia de usuario
* Se escogió PHP por petición de los stakeholders, para las demás capas
* Se escogió Laravel por ser el framework más conocido de MVC en PHP, lo cual permitiría incluir muchas dependecias que agilizar el proceso de construcción
* Se uso protocolo HTTP para la transferencia de datos

### Rutas relevantes

**Características de la app**

El folder root donde están contenidas las imagenes css y demás es el folder
```
/public/
```

**Folders de la app

* .htaccess está ubicado en el folder root por encima de /public/ 
* Todos los folders estará ocultos excepto /public/ que se expondrá mediante .htaccess
* La carpeta /storage/ debe ser reescribible en tiempo real
* /root/public/ generará error 404. Ya que la /public/ se redireccionó al estado anterior
* Se debe tener especial cuidado con NO EXPONER el folder root, donde se encuentran los .env

#### Rutas relevantes
```
/admin/dd/ -> Presentación viual de datos de logeo
/en/ -> Portal de login de admin
/es/ -> Link que regirige a los controladores de locales, que almacenan info previa y la transcriben
/{locale}/ -> Otros locales
/admin/home -> Portal de admin
/admin/login -> Portal de login de admin
/admin/logout -> logout de admin
/admin/home -> Portal de admin
/PICO/ResultsNumber -> Test de API de Número de resultados de búsqueda
/PICO/ResultsNumberIntegration -> Test de API de Integración a número de resultados de búsqueda con BIREME
/PICO/DeCS -> Test de API que entrega DeCS procesados
/PICO/DeCSIntegration -> Test de API de Integración que obtiene DeCS de BIREME
/PICO/QueryBuild -> Test de API que procesa resultado en ecuaciones de búsqueda
/health/panel ->  Modulo de testeo de componentes (En progreso)
```

### Paquetes Requeridos

#### Nodev

```
* "php": "^7.1.3",
* "ext-dom": "*",
* "ext-json": "*",
* "ext-pdo": "*",
* "appzcoder/laravel-admin": "^3.1",
* "doctrine/dbal": "^2.9",
* "fideloper/proxy": "^4.0",
* "graham-campbell/throttle": "^7.3",
* "guzzlehttp/guzzle": "^6.3",
* "hedronium/spaceless-blade": "^2.0",
* "jenssegers/model": "^1.2",
* "laravel/framework": "5.8.*",
* "laravel/tinker": "^1.0",
* "pragmarx/health": "^0.9.17",
* "rap2hpoutre/laravel-log-viewer": "^1.2",
* "symfony/psr-http-message-bridge": "^1.2",
* "zendframework/zend-diactoros": "^2.1"
```
		
#### Dev

```
* "barryvdh/laravel-ide-helper": "dev-master",
* "beyondcode/laravel-dump-server": "^1.0",
* "filp/whoops": "^2.0",
* "fzaninotto/faker": "^1.4",
* "mockery/mockery": "^1.0",
* "nunomaduro/collision": "^3.0",
* "spatie/laravel-analytics": "dev-master",
* "thepinecode/i18n": "dev-master"
```

## Copyright

PICOSearcher © Pan American Health Organization, 2019.

## Agradecimientos

Agradezco a la profesora Lyda Osorio por animarme a continuar en la investigación biomédica, tras casi retirarme por ciertos eventos

## Autor

**Daniel Nieto-González** - [GitHub Profile](https://github.com/nietodaniel) - [Send email](mailto:nieto.daniel221@gmail.com)
* Consultor Bireme | PAHO | OMS (2019)
* CEO - [Digital MedTools](Http://digitalmedtools.blogspot.com) 
