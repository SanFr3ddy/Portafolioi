import pygame
import random

"""
Constantes globales
"""

# Colores
NEGRO = (0, 0, 0)
BLANCO = (255, 255, 255)
AZUL = (0, 0, 255)
ROJO = (255, 0, 0)

# Dimensiones de la pantalla
LARGO_PANTALLA = 1920
ALTO_PANTALLA = 1080

# Gravedad
GRAVEDAD = 0.35

class Protagonista(pygame.sprite.Sprite):
    """ Esta clase representa la barra inferior que controla el protagonista. """

    # Función Constructor
    def __init__(self, x, y):
        # Llama al constructor padre
        super().__init__()

        # Establecemos el alto y largo
        self.image = pygame.Surface([15, 15])
        self.image.fill(BLANCO)

        # Establece como origen la esquina superior izquierda.
        self.rect = self.image.get_rect()
        self.rect.y = y
        self.rect.x = x

        # Establecemos el vector velocidad
        self.cambio_x = 0
        self.cambio_y = 0
        self.paredes = None
        self.en_suelo = False

    def cambiovelocidad(self, x, y):
        """ Cambia la velocidad del protagonista. """
        self.cambio_x += x
        self.cambio_y += y

    def update(self):
        """ Actualiza la posición del protagonista. """
        # Desplazar izquierda/derecha
        self.rect.x += self.cambio_x

        # Hemos chocado contra la pared después de esta actualización?
        lista_impactos_bloques = pygame.sprite.spritecollide(self, self.paredes, False)
        for bloque in lista_impactos_bloques:
            # Si nos estamos desplazando hacia la derecha, hacemos que nuestro lado derecho sea el lado izquierdo del objeto que hemos tocado-
            if self.cambio_x > 0:
                self.rect.right = bloque.rect.left
            else:
                # En caso contrario, si nos desplazamos hacia la izquierda, hacemos lo opuesto.
                self.rect.left = bloque.rect.right

        # Aplicar gravedad
        self.cambio_y += GRAVEDAD

        # Desplazar arriba/abajo
        self.rect.y += self.cambio_y

        # Comprobamos si hemos chocado contra algo
        lista_impactos_bloques = pygame.sprite.spritecollide(self, self.paredes, False)
        for bloque in lista_impactos_bloques:
            # Reseteamos nuestra posición basándonos en la parte superior/inferior del objeto.
            if self.cambio_y > 0:
                self.rect.bottom = bloque.rect.top
                self.en_suelo = True
            else:
                self.rect.top = bloque.rect.bottom

            # Detener el movimiento vertical
            self.cambio_y = 0

    def saltar(self):
        """ Hace que el protagonista salte. """
        if self.en_suelo:
            self.cambio_y = -10
            self.en_suelo = False

class Pared(pygame.sprite.Sprite):
    """ Pared con la que el protagonista puede encontrarse. """
    def __init__(self, x, y, largo, alto):
        """ Constructor para la pared con la que el protagonista puede encontrarse """
        # Llama al constructor padre
        super().__init__()

        # Construye una pared azul con las dimensiones especificadas por los parámetros
        self.image = pygame.Surface([largo, alto])
        self.image.fill(AZUL)

        # Establece como origen la esquina superior izquierda.
        self.rect = self.image.get_rect()
        self.rect.y = y
        self.rect.x = x

class Objeto(pygame.sprite.Sprite):
    """ Objeto que se mueve desde diferentes direcciones. """
    def __init__(self, x, y, velocidad_x, velocidad_y):
        super().__init__()

        self.image = pygame.Surface([20, 20])
        self.image.fill(ROJO)

        self.rect = self.image.get_rect()
        self.rect.y = y
        self.rect.x = x

        self.velocidad_x = velocidad_x
        self.velocidad_y = velocidad_y

    def update(self):
        """ Mueve el objeto. """
        self.rect.x += self.velocidad_x
        self.rect.y += self.velocidad_y

        # Si el objeto sale de la pantalla, reaparece en una posición aleatoria
        if self.rect.right < 0 or self.rect.left > LARGO_PANTALLA or self.rect.bottom < 0 or self.rect.top > ALTO_PANTALLA:
            self.rect.x = random.randint(0, LARGO_PANTALLA)
            self.rect.y = random.randint(0, ALTO_PANTALLA)
            self.velocidad_x = random.choice([-5, 5])
            self.velocidad_y = random.choice([-5, 5])

def crear_escenario_aleatorio():
    """ Crea un escenario aleatorio con paredes y objetos. """
    pared_list = pygame.sprite.Group()
    objeto_list = pygame.sprite.Group()

    # Crear paredes en posiciones aleatorias
    for _ in range(10):
        x = random.randint(0, LARGO_PANTALLA - 100)
        y = random.randint(0, ALTO_PANTALLA - 100)
        largo = random.randint(50, 300)
        alto = random.randint(50, 300)
        pared = Pared(x, y, largo, alto)
        pared_list.add(pared)

    # Crear objetos que se mueven desde diferentes direcciones
    for _ in range(20):
        x = random.randint(0, LARGO_PANTALLA)
        y = random.randint(0, ALTO_PANTALLA)
        velocidad_x = random.choice([-5, 5])
        velocidad_y = random.choice([-5, 5])
        objeto = Objeto(x, y, velocidad_x, velocidad_y)
        objeto_list.add(objeto)

    return pared_list, objeto_list

def mostrar_mensaje(pantalla, mensaje, tamano, color, x, y):
    """ Muestra un mensaje en la pantalla. """
    fuente = pygame.font.SysFont(None, tamano)
    texto = fuente.render(mensaje, True, color)
    pantalla.blit(texto, [x, y])

# Llamamos a esta función para que la biblioteca Pygame pueda autoiniciarse.
pygame.init()

# Creamos una pantalla de 1920x1080
pantalla = pygame.display.set_mode([LARGO_PANTALLA, ALTO_PANTALLA])

# Creamos el título de la ventana
pygame.display.set_caption('Escenario Aleatorio')

# Lista que almacena todos los sprites
listade_todoslos_sprites = pygame.sprite.Group()

# Creamos al objeto protagonista
protagonista = Protagonista(50, 50)
listade_todoslos_sprites.add(protagonista)

# Variables de juego
vidas = 3
juego_terminado = False

# Crear el escenario aleatorio
pared_list, objeto_list = crear_escenario_aleatorio()
protagonista.paredes = pared_list
listade_todoslos_sprites.add(pared_list)
listade_todoslos_sprites.add(objeto_list)

# Definir el marco del juego (sin el piso)
marco_superior = Pared(0, 0, LARGO_PANTALLA, 10)
marco_izquierdo = Pared(0, 0, 10, ALTO_PANTALLA)
marco_derecho = Pared(LARGO_PANTALLA - 10, 0, 10, ALTO_PANTALLA)
pared_list.add(marco_superior, marco_izquierdo, marco_derecho)
listade_todoslos_sprites.add(marco_superior, marco_izquierdo, marco_derecho)

# Definir la base de inicio del protagonista
base_inicio = Pared(0, ALTO_PANTALLA - 50, 200, 10)
pared_list.add(base_inicio)
listade_todoslos_sprites.add(base_inicio)

# Definir la plataforma final
plataforma_final = Pared(1800, 1000, 100, 10)
pared_list.add(plataforma_final)
listade_todoslos_sprites.add(plataforma_final)

reloj = pygame.time.Clock()

while True:
    for evento in pygame.event.get():
        if evento.type == pygame.QUIT:
            pygame.quit()
            exit()

        elif evento.type == pygame.KEYDOWN:
            if evento.key == pygame.K_LEFT:
                protagonista.cambiovelocidad(-3, 0)
            elif evento.key == pygame.K_RIGHT:
                protagonista.cambiovelocidad(3, 0)
            elif evento.key == pygame.K_UP:
                protagonista.cambiovelocidad(0, -3)
            elif evento.key == pygame.K_DOWN:
                protagonista.cambiovelocidad(0, 3)
            elif evento.key == pygame.K_SPACE:
                protagonista.saltar()

        elif evento.type == pygame.KEYUP:
            if evento.key == pygame.K_LEFT:
                protagonista.cambiovelocidad(3, 0)
            elif evento.key == pygame.K_RIGHT:
                protagonista.cambiovelocidad(-3, 0)
            elif evento.key == pygame.K_UP:
                protagonista.cambiovelocidad(0, 3)
            elif evento.key == pygame.K_DOWN:
                protagonista.cambiovelocidad(0, -3)

    if not juego_terminado:
        listade_todoslos_sprites.update()

        # Comprobar colisiones con objetos
        if pygame.sprite.spritecollideany(protagonista, objeto_list):
            vidas -= 1
            if vidas > 0:
                protagonista.rect.x = 50
                protagonista.rect.y = 50
            else:
                juego_terminado = True

        # Comprobar si el protagonista ha caído al vacío
        if protagonista.rect.top > ALTO_PANTALLA:
            vidas -= 1
            if vidas > 0:
                protagonista.rect.x = 50
                protagonista.rect.y = 50
            else:
                juego_terminado = True

        # Comprobar si el protagonista ha llegado a la plataforma final
        if protagonista.rect.colliderect(plataforma_final.rect):
            juego_terminado = True
            mostrar_mensaje(pantalla, "¡Ganador!", 50, BLANCO, LARGO_PANTALLA // 2 - 150, ALTO_PANTALLA // 2)
            pygame.display.flip()
            pygame.time.wait(2000)

    pantalla.fill(NEGRO)

    listade_todoslos_sprites.draw(pantalla)

    mostrar_mensaje(pantalla, f"Vidas: {vidas}", 30, BLANCO, 10, 10)

    if juego_terminado:
        if vidas > 0:
            mostrar_mensaje(pantalla, "¡Ganador!", 50, BLANCO, LARGO_PANTALLA // 2 - 150, ALTO_PANTALLA // 2)
        else:
            mostrar_mensaje(pantalla, "¡Perdiste!", 50, ROJO, LARGO_PANTALLA // 2 - 100, ALTO_PANTALLA // 2)

    pygame.display.flip()

    reloj.tick(60)