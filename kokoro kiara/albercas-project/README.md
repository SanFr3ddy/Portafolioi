# Albercas Project

Este proyecto es una aplicación web para gestionar un juego llamado "kokoro kiara". Permite a los usuarios registrarse y jugar, almacenando sus datos en una base de datos.

## Estructura del Proyecto

El proyecto tiene la siguiente estructura de archivos:

```
albercas-project
├── src
│   ├── app.py               # Punto de entrada de la aplicación
│   ├── database.py          # Lógica de conexión a la base de datos
│   ├── models.py            # Modelo de datos para los usuarios
│   └── templates
│       └── Inicio.html      # Interfaz del juego
├── requirements.txt         # Dependencias del proyecto
└── README.md                # Documentación del proyecto
```

## Requisitos

Asegúrate de tener Python instalado en tu sistema. Este proyecto utiliza las siguientes dependencias:

- Flask
- SQLAlchemy

Puedes instalar las dependencias ejecutando:

```
pip install -r requirements.txt
```

## Configuración

1. Clona este repositorio en tu máquina local.
2. Navega al directorio del proyecto.
3. Asegúrate de que la base de datos "albercas" esté creada en tu sistema.
4. Ejecuta el archivo `app.py` para iniciar la aplicación:

```
python src/app.py
```

## Uso

Una vez que la aplicación esté en funcionamiento, podrás acceder a ella a través de tu navegador en `http://localhost:5000`. Desde allí, podrás registrarte y comenzar a jugar "kokoro kiara".

## Contribuciones

Las contribuciones son bienvenidas. Si deseas mejorar este proyecto, siéntete libre de hacer un fork y enviar un pull request.