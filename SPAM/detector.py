import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.naive_bayes import MultinomialNB
from sklearn.metrics import accuracy_score, classification_report
import re
import string
# Clasificar mensaje
def clasificar_mensaje(modelo, vectorizador, mensaje):
    mensaje = preprocesar_texto(mensaje)
    mensaje_vectorizado = vectorizador.transform([mensaje])
    prediccion = modelo.predict(mensaje_vectorizado)
    return "Spam" if prediccion[0] == 1 else "No Spam"

# Interfaz interactiva para clasificar mensajes
def interfaz_clasificacion(modelo, vectorizador):
    print("\n=== Clasificador de Mensajes ===")
    print("Escribe un mensaje para clasificarlo como 'Spam' o 'No Spam'.")
    print("Escribe 'salir' para terminar.\n")
    
    while True:
        mensaje = input("Ingresa un mensaje: ")
        if mensaje.lower() == "salir":
            print("Saliendo del clasificador. ¡Adiós!")
            break
        clasificacion = clasificar_mensaje(modelo, vectorizador, mensaje)
        print(f"Clasificación: {clasificacion}\n")

# Preprocesamiento de texto
def preprocesar_texto(texto):
    # Convertir a minúsculas
    texto = texto.lower()
    # Eliminar URLs
    texto = re.sub(r'http\S+|www\S+|https\S+', '', texto, flags=re.MULTILINE)
    # Eliminar signos de puntuación
    texto = texto.translate(str.maketrans('', '', string.punctuation))
    # Eliminar números
    texto = re.sub(r'\d+', '', texto)
    # Eliminar espacios adicionales
    texto = texto.strip()
    return texto

# Cargar datos
def cargar_datos():
    ruta_archivo = r"c:\xampp1\htdocs\Albercas\SPAM\SMSSpamCollection1.csv"
    filas_validas = []
    
    with open(ruta_archivo, 'r', encoding='latin-1') as archivo:
        for linea in archivo:
            partes = linea.strip().split(',', 1)
            if len(partes) == 2:
                filas_validas.append(partes)
            else:
                print(f"Línea ignorada por formato incorrecto: {linea.strip()}")
    
    df = pd.DataFrame(filas_validas, columns=['etiqueta', 'mensaje'])
    df['mensaje'] = df['mensaje'].apply(preprocesar_texto)
    df = df[df['mensaje'] != '']  # Eliminar mensajes vacíos
    
    print(f"Datos cargados con {len(df)} filas.")
    return df

# Entrenar modelo
def entrenar_modelo():
    df = cargar_datos()
    X = df['mensaje']
    
    # Vectorización
    vectorizador = TfidfVectorizer(stop_words='english', max_features=5000, min_df=1)
    X = vectorizador.fit_transform(X)
    
    # Dividir datos en entrenamiento y prueba
    X_entrenamiento, X_prueba, y_entrenamiento, y_prueba = train_test_split(X, df.index, test_size=0.2, random_state=42)
    
    print(f"Tamaño de entrenamiento: {X_entrenamiento.shape[0]}")
    print(f"Tamaño de prueba: {X_prueba.shape[0]}")
    
    # Entrenar modelo
    modelo = MultinomialNB()
    modelo.fit(X_entrenamiento, y_entrenamiento)
    
    # Evaluar modelo
    y_pred = modelo.predict(X_prueba)
    print("Precisión:", accuracy_score(y_prueba, y_pred))
    print("Informe de clasificación:\n", classification_report(y_prueba, y_pred, zero_division=1))
    
    return modelo, vectorizador

# Clasificar mensaje
def clasificar_mensaje(modelo, vectorizador, mensaje):
    mensaje = preprocesar_texto(mensaje)
    mensaje_vectorizado = vectorizador.transform([mensaje])
    prediccion = modelo.predict(mensaje_vectorizado)
    return "Spam" if prediccion[0] == 1 else "No Spam"

if __name__ == "__main__":
    print("Entrenando el modelo...")
    modelo, vectorizador = entrenar_modelo()
    interfaz_clasificacion(modelo, vectorizador)
    # Ejemplo de clasificación
    mensaje = "¡Felicidades! Has ganado una tarjeta de regalo de $1,000. Ve a http://bit.ly/12345 para reclamarla."
    print(f"Mensaje: {mensaje}")
    print(f"Clasificación: {clasificar_mensaje(modelo, vectorizador, mensaje)}")