from sqlalchemy import create_engine, Column, Integer, String
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.orm import sessionmaker

DATABASE_URL = "sqlite:///albercas.db"  # Cambia esto si usas otra base de datos

Base = declarative_base()

class Usuario(Base):
    __tablename__ = 'usuarios'
    
    id = Column(Integer, primary_key=True, autoincrement=True)
    correo = Column(String, unique=True, nullable=False)
    contraseña_encriptada = Column(String, nullable=False)
    rol = Column(String, nullable=False)

def init_db():
    engine = create_engine(DATABASE_URL)
    Base.metadata.create_all(engine)

def get_session():
    engine = create_engine(DATABASE_URL)
    Session = sessionmaker(bind=engine)
    return Session()