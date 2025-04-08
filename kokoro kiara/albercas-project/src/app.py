from flask import Flask, request, render_template
from werkzeug.security import generate_password_hash
import sqlite3

app = Flask(__name__)

DATABASE = 'albercas.db'

def get_db():
    conn = sqlite3.connect(DATABASE)
    return conn

def create_users_table():
    conn = get_db()
    cursor = conn.cursor()
    cursor.execute('''
        CREATE TABLE IF NOT EXISTS usuarios (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            correo TEXT NOT NULL UNIQUE,
            contraseña_encriptada TEXT NOT NULL,
            rol TEXT NOT NULL
        )
    ''')
    conn.commit()
    conn.close()

@app.route('/')
def index():
    return render_template('Inicio.html')

@app.route('/register', methods=['POST'])
def register():
    correo = request.form['correo']
    contraseña = request.form['contraseña']
    rol = request.form['rol']
    
    contraseña_encriptada = generate_password_hash(contraseña)

    conn = get_db()
    cursor = conn.cursor()
    cursor.execute('INSERT INTO usuarios (correo, contraseña_encriptada, rol) VALUES (?, ?, ?)', 
                   (correo, contraseña_encriptada, rol))
    conn.commit()
    conn.close()
    
    return 'Usuario registrado con éxito'

if __name__ == '__main__':
    create_users_table()
    app.run(debug=True)