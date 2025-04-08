import cocos
import pyglet
from pyglet.window import key

# Definir las acciones del personaje
acciones = {
    "Dead": {"frames": 10, "duration": 2.0},
    "Hurt": {"frames": 8, "duration": 1.0},
    "Idle": {"frames": 10, "duration": 0},
    "Jump": {"frames": 12, "duration": 1.5},
    "Run": {"frames": 8, "duration": 0},
    "Slide": {"frames": 5, "duration": 1.0}
}

# Cargar todas las imágenes de los sprites
sprites = {}
for accion, data in acciones.items():
    sprites[accion] = []
    for i in range(1, data["frames"] + 1):
        try:
            image = pyglet.image.load(f"redhatfiles/png/{accion} ({i}).png")
            sprites[accion].append(image)
        except FileNotFoundError:
            print(f"Archivo no encontrado: redhatfiles/png/{accion} ({i}).png")

class Personaje(cocos.sprite.Sprite):
    def __init__(self):
        super(Personaje, self).__init__(sprites["Idle"][0])
        self.position = 50, 50
        self.velocity = 0, 0
        self.en_suelo = True
        self.current_action = "Idle"
        self.frame_index = 0
        self.schedule(self.update)

    def update(self, dt):
        vx, vy = self.velocity
        x, y = self.position

        # Aplicar gravedad
        if not self.en_suelo:
            vy -= 500 * dt

        # Actualizar posición
        x += vx * dt
        y += vy * dt

        # Detectar colisiones con el suelo
        if y <= 50:
            y = 50
            vy = 0
            self.en_suelo = True

        self.velocity = vx, vy
        self.position = x, y

        # Actualizar animación
        self.frame_index += 1
        if self.frame_index >= len(sprites[self.current_action]):
            self.frame_index = 0
        self.image = sprites[self.current_action][self.frame_index]

    def set_action(self, action):
        if action != self.current_action:
            self.current_action = action
            self.frame_index = 0

class ControlLayer(cocos.layer.Layer):
    is_event_handler = True

    def __init__(self, personaje):
        super(ControlLayer, self).__init__()
        self.personaje = personaje

    def on_key_press(self, symbol, modifiers):
        if symbol == key.SPACE:
            if self.personaje.en_suelo:
                self.personaje.velocity = self.personaje.velocity[0], 300
                self.personaje.en_suelo = False
                self.personaje.set_action("Jump")
        elif symbol == key.A:
            self.personaje.velocity = -200, self.personaje.velocity[1]
            self.personaje.set_action("Run")
        elif symbol == key.D:
            self.personaje.velocity = 200, self.personaje.velocity[1]
            self.personaje.set_action("Run")
        elif symbol == key.LCTRL:
            self.personaje.set_action("Slide")

    def on_key_release(self, symbol, modifiers):
        if symbol in (key.A, key.D):
            self.personaje.velocity = 0, self.personaje.velocity[1]
            self.personaje.set_action("Idle")

if __name__ == "__main__":
    # Deshabilitar la inicialización del audio
    cocos.audio = None

    cocos.director.director.init(width=960, height=600, caption="Animación sprites")

    personaje = Personaje()
    control_layer = ControlLayer(personaje)

    main_scene = cocos.scene.Scene(personaje, control_layer)
    cocos.director.director.run(main_scene)