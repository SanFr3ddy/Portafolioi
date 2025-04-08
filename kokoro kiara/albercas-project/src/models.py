from sqlalchemy import Column, Integer, String
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.orm import sessionmaker
from sqlalchemy import create_engine
import bcrypt

Base = declarative_base()

class Usuario(Base):
    __tablename__ = 'usuarios'

    id = Column(Integer, primary_key=True, autoincrement=True)
    correo = Column(String, unique=True, nullable=False)
    contraseña_encriptada = Column(String, nullable=False)
    rol = Column(String, nullable=False)

    def __init__(self, correo, contraseña, rol):
        self.correo = correo
        self.contraseña_encriptada = self.encriptar_contraseña(contraseña)
        self.rol = rol

    def encriptar_contraseña(self, contraseña):
        return bcrypt.hashpw(contraseña.encode('utf-8'), bcrypt.gensalt()).decode('utf-8')

    @classmethod
    def validar_usuario(cls, correo, contraseña, session):
        usuario = session.query(cls).filter_by(correo=correo).first()
        if usuario and bcrypt.checkpw(contraseña.encode('utf-8'), usuario.contraseña_encriptada.encode('utf-8')):
            return usuario
        return None

    @classmethod
    def guardar_usuario(cls, correo, contraseña, rol, session):
        nuevo_usuario = cls(correo, contraseña, rol)
        session.add(nuevo_usuario)
        session.commit()

# Configuración de la base de datos
DATABASE_URL = "sqlite:///albercas.db"  # Cambiar a la URL de la base de datos deseada
engine = create_engine(DATABASE_URL)
Base.metadata.create_all(engine)

Session = sessionmaker(bind=engine)
session = Session()