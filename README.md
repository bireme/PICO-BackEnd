# PICO-BackEnd
Search BVS via PICO (Patient, Intervention, Comparison, Outcome)
--------------------------------------------------------------------------------------------------------
Entrega #1. 
Contiene:
1.	Integración para obtener descriptores DeCS (Plazo máximo 13/04/2019)
2.	Integración para obtener sinónimos DeCS (Plazo máximo 18/04/2019)
3.	Integración para obtener número de resultados de una query (Plazo máximo 23/04/2019, es parte de entrega #2)
----------------------------------------------------------------------------------------------------------
¿Cómo se usa?

Se debe abrir index.php, el cual contiene dos formularios:

1- Buscador de sinónimos DeCS: Se debe introducir la palabra clave a la que se buscarán los descriptores, el número o posición
   en la lista de descriptores encontrados al que se le buscarán sus sinónimos y los lenguajes en que se buscarán los sinónimos
   separados por comas sin espacios
   
2- Recuperador de número de resultados: Se debe introducir un query de búsqueda del cual se obtendrá el número de resultados
--------------------------------------------------------------------------------------------------------------
¿Qué hace?

LayerIntegration contiene la capa de integración, encargada de conectarse a los servicios web de bireme que entregan:
1- Los descriptores DeCS
2- Los sinónimos para un descriptor DeCS
3- El número de resultados para un query de búsquedas usando 

Inicialmente los recupera en formato XML para posteriormente extraer la información necesitada en cada caso, y entregarla a la
capa de negocios, BusinessLayer, en formato de texto simple y arrays para que posteriormente los controladores de negocios gestionen
su uso.
------------------------------------------------------------------------------------------------------------------
Limitaciones y restricciones

Solo está construida la integración por lo que no existen controladores o funciones que se encarguen de gestionar que la información
enviada sea correcta y no genere errores, ni que la información recibida no corresponda a errores.
----------------------------------------------------------------------------------------------------------------
Arquitectura y diseño
Se diseño el sistema buscando:

1. Se utilizó arquitectura orientada a objetos y construcción de diagramas y paquetes para lograr trazabilidad
2. Máxima seguridad y protección: Todos los métodos y variables tienen el mayor grado de privacidad que les permita funcionar
3. Integridad de datos extensos: Se utilizaron POST requests para garantizar que no haya límite en el tamaño de los querys
4. Alta cohesión: En la capa de integración se encuentran elementos muy similares como proxys encargados de obtener la información
   y controladores encargados de gestionar la forma de las conexiones y de procesar la información reciba. Mientras que en la capa
   de negocios se encontrarán los elementos que gestionarán el proceso de información y la creación de entidades.
5. Bajo acoplamiento: Existen pocos puntos de unión entre las unidades o paquetes. El único punto de unión entre la capa de
   integración y la capa de negocios es el camino a través de la estrategia de contextos para integración (IntegrationStrategyContext)
   y la adyacente interfaz de integración (InterfaceIntegration) encargada de establecer el contrato de parametros para realizar la
   conexion a los distintos web service y la entrega de los resultados luego de procesar esa conexión.
-------------------------------------------------------------------------------------------------------
