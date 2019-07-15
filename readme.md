1. Instalar composer y artisan
2. Establecer /Laravel-BIREME/public/ como directorio raíz de la imagen montada en el servidor
3. Copiar .env.example a .env
4. Usar el comando "php artisan key:generate" en cmd en el folder raiz de la app

5. Adicionar los datos de google analytics (https://blog.hashvel.com/posts/laravel-google-analytics/)
  a.Poner el JSON de analytics en /storage/app/analytic/
  b.Poner el view ID en el .env del directorio principal, en la variable ANALYTICS_VIEW_ID=######
  
  
  Características:
  Solo FrontEnd y middleware por ahora.
  
  **Locale adaptado a SEO con envio de datos de formulario al nuevo lenguaje para no perder información
  **Throttle (configurable) para proteger al servidor, se recomienda firewall de todos modos
  **Sistema de logs incipiente (se mejorará luego, con acceso solo a admin) en /admin/logs
  **Compilación en un solo archivo de los js y css para mejorar rendimiento y eliminar incompatibilidades con otros servidores (cros env) se utilizará minify en producción
  **Sistema de manejo de excepciones establecido, apoyandose en el sistema de log
  **Requests PSR-7 para los request POST
  **Compartimentalización del html en varios views alimentados por controladores, siguiendo el patrón MVC
