import mysql.connector
from mysql.connector import Error
from werkzeug.security import generate_password_hash, check_password_hash
from flask import Flask, request, redirect, render_template, flash, url_for

app = Flask(__name__)
app.secret_key = 'your_secret_key'  # Necesario para usar flash messages

def create_connection():
    connection = None
    try:
        connection = mysql.connector.connect(
            host="localhost",
            user="root",  # Cambia esto si tienes un usuario diferente
            password="",  # Cambia esto si tienes una contraseña diferente
            database="albercas"
        )
    except Error as e:
        print(f"Error: '{e}'")
    return connection

@app.route('/registro', methods=['GET', 'POST'])
def registro():
    if request.method == 'POST':
        correo = request.form['correo']
        contrasena = request.form['contrasena']
        confirma_contrasena = request.form['confirma_contrasena']
        rol = 'usuario'  # Puedes cambiar esto según tus necesidades

        # Validaciones
        if not correo or not contrasena or not confirma_contrasena:
            flash("Por favor, complete todos los campos.")
            return redirect(url_for('registro'))
        if contrasena != confirma_contrasena:
            flash("Las contraseñas no coinciden.")
            return redirect(url_for('registro'))

        # Encriptar la contraseña
        hashed_password = generate_password_hash(contrasena, method='sha256')

        # Insertar en la base de datos
        connection = create_connection()
        cursor = connection.cursor()
        try:
            cursor.execute("INSERT INTO usuarios (correo, contrasena, rol) VALUES (%s, %s, %s)", (correo, hashed_password, rol))
            connection.commit()
        except Error as e:
            flash(f"Error al insertar en la base de datos: {e}")
            return redirect(url_for('registro'))
        finally:
            cursor.close()
            connection.close()

        flash("Registro exitoso. Por favor, inicie sesión.")
        return redirect(url_for('login'))

    return render_template('registro.html')

@app.route('/inicio')
def inicio():
    return "Registro exitoso. Bienvenido a la página de inicio."

@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        correo = request.form['correo']
        contrasena = request.form['contrasena']

        # Validaciones
        if not correo or not contrasena:
            flash("Por favor, complete todos los campos.")
            return redirect(url_for('login'))

        # Verificar en la base de datos
        connection = create_connection()
        cursor = connection.cursor()
        try:
            cursor.execute("SELECT contrasena FROM usuarios WHERE correo = %s", (correo,))
            user = cursor.fetchone()
            if user and check_password_hash(user[0], contrasena):
                flash("Inicio de sesión exitoso.")
                return redirect(url_for('inicio'))
            else:
                flash("Correo o contraseña incorrectos.")
                return redirect(url_for('login'))
        except Error as e:
            flash(f"Error al consultar la base de datos: {e}")
            return redirect(url_for('login'))
        finally:
            cursor.close()
            connection.close()

    return render_template('login.html')

if __name__ == '__main__':
    app.run(debug=True)