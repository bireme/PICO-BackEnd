Mejoras
**El log se movi� a public_html para que se pudiera ver desde el tester
**Se separ� la integraci�n de la capa de negocios, uniendolas mediante JSON, abriendo la posibilidad para que otros servicios usen los datos
**Se reorganizaron las funciones siguiendo el patr�n facade, para optimizar el flujo de datos y facilitar la modificaci�n de elementos y la trazabilidad
**La reorganizaci�n de las funciones permitir� un manejo extremadamente f�cil de los errores en la nueva versi�n en laravel en la proxima entrega

Puntos en contra
**Se eliminaron casi todos los error handlers en el proceso de modificaci�n, ya que de todos modos probablemente sea necesario reconstruirlos al pasar a laravel
**No hay seguridad, consola, logeo avanzado ni configs, todo esto se introducira en la proxima entrega en laravel