import cocos
from cocos.director import director
from cocos.scene import Scene
from cocos.layer import Layer
from cocos.sprite import Sprite
from cocos.actions import MoveTo, Delay
from cocos.text import Label
import pyglet
import random

pyglet.options['audio'] = ('silent')
import cocos.audio
cocos.audio.initialize = lambda audio_settings: None

class Juego(Layer):
    def __init__(self):
        super().__init__()
        self.personaje = Sprite('IMG_1614.JPG')
        self.personaje.position = (100, 100)
        self.add(self.personaje)

        self.obstaculo = Sprite('fondo.jpg')
        self.obstaculo.position = (random.randint(0, 600), 600)
        self.add(self.obstaculo)

        self.obstaculo.do(MoveTo((self.obstaculo.x, -100), 5))

        self.label_puntos = Label('Puntos: 0', font_size=32, anchor_x='center', anchor_y='center')
        self.label_puntos.position = (300, 550)
        self.add(self.label_puntos)

        self.puntos = 0

        self.is_event_handler = True

    def on_mouse_motion(self, x, y, dx, dy):
        self.personaje.position = (x, y)

    def update(self, dt):
        if self.obstaculo.y < -100:
            self.obstaculo.position = (random.randint(0, 600), 600)
            self.obstaculo.do(MoveTo((self.obstaculo.x, -100), 5))
            self.puntos += 1
            self.label_puntos.element.text = 'Puntos: ' + str(self.puntos)

        if self.obstaculo.get_rect().intersects(self.personaje.get_rect()):
            director.replace(Scene(GameOver()))

class GameOver(Layer):
    def __init__(self):
        super().__init__()
        label = Label('Game Over', font_size=50, anchor_x='center', anchor_y='center')
        label.position = (320, 240)
        self.add(label)

def main():
    director.init(width=640, height=480, caption='Mi Juego')
    scene = Scene(Juego())
    director.run(scene)

if __name__ == '__main__':
    main()