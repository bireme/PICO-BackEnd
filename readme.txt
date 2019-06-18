Mejoras
**El log se movió a public_html para que se pudiera ver desde el tester
**Se separó la integración de la capa de negocios, uniendolas mediante JSON, abriendo la posibilidad para que otros servicios usen los datos
**Se reorganizaron las funciones siguiendo el patrón facade, para optimizar el flujo de datos y facilitar la modificación de elementos y la trazabilidad
**La reorganización de las funciones permitirá un manejo extremadamente fácil de los errores en la nueva versión en laravel en la proxima entrega

Puntos en contra
**Se eliminaron casi todos los error handlers en el proceso de modificación, ya que de todos modos probablemente sea necesario reconstruirlos al pasar a laravel
**No hay seguridad, consola, logeo avanzado ni configs, todo esto se introducira en la proxima entrega en laravel