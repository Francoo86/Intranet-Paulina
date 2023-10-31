# SIG-Proyecto
Este proyecto consiste en una elaboración de un software prototipo de gestión y manejo de publicidad.
# Instalación
TODO: Elaborar la que no tiene Docker.  
Se puede instalar por los diferentes medios, por via Docker o via tradicional.  
Para ambos casos se tiene que clonar el repositorio en Git, con el siguiente comando:  
``git clone https://github.com/Francoo86/SIG-Proyecto``

### Por Docker
Si es la primera vez usando Docker:  
1) Descargar e instalar [Docker.](https://hub.docker.com/)
2) Al momento de finalizar la instalación puede que requiera cuenta.
3) Se mostrarán algunas categorias para poder ajustar Docker a los proyectos, se tiene que elegir Local Development.
4) También pedirá que rol impartimos, seleccionamos backend developer.
5) Listo, ya está instalado.

Iniciar el proyecto:  
1) Para poder iniciar el proyecto, tenemos que abrir la Terminal, en este caso Windows.
2) Cambiamos directorios hasta llegar a la carpeta donde hemos guardado el proyecto. ``cd Directorio``.
3) Una vez en la carpeta raíz del proyecto, escribimos los siguientes comandos:
4) Si es la primera vez usando este proyecto, hay que escribir:  
   ```docker compose build --no-cache```
5) Para poder iniciarlo:  
   ```docker compose up --pull -d --wait```
6) Ingresamos a nuestro navegador de preferencia y escribimos ```https://localhost```.
7) Si se hicieron los pasos correctamente debería mostrar el proyecto.
8) Para poder terminar con su ejecución escribimos:
    ``docker compose down --remove-orphans``
