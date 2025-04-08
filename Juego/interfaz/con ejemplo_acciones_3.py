import cocos
from cocos.actions import *
from pygame import mixer

class MisAcciones(cocos.layer.ColorLayer):

    def __init__(self):
        super().__init__(64, 200, 255, 255)

        mi_sprite = cocos.sprite.Sprite('nave.png')
        mi_sprite.position = 590, 240
        mi_sprite.scale = 1.5
        self.add(mi_sprite)

        mi_sprite.do(CallFuncS(lambda obj: self.discontinuo(obj, 5, (-100, 0), 1)))
        mi_sprite.do(Delay(6) + CallFuncS(lambda obj: self.mover_girando(obj, (550, 80), 3, 3)))
        mi_sprite.do(Delay(9) + AccelDeccel(MoveBy((0, 350), 3) | RotateBy(360, 3)))
        mi_sprite.do(Delay(12) + Accelerate((MoveBy((-450, 0), 3) | RotateBy(360, 3)), 4))
        mi_sprite.do(Delay(16) + (MoveBy((0, -400), 3) | FadeOut(3)))

    def discontinuo(self, obj, n_pasos, vector, tiempo):
        coor_x = obj.x
        coor_y = obj.y

        for i in range(1, n_pasos + 1):
            obj.do(Delay(i * tiempo) + Place((coor_x + i * vector[0], coor_y)))

    def mover_girando(self, obj, coor, n_giros, tiempo):
        obj.do(MoveTo((coor[0], coor[1]), tiempo) | RotateBy(360 * n_giros, tiempo))


if __name__ == "__main__":
    # Inicializar el director de cocos2d sin audio
    cocos.director.director.init(caption='Ejemplo 3 de acciones', audio=False)

    # Inicializar el mezclador de pygame
    mixer.init()

    # Cargar y reproducir música de fondo
    mixer.music.load('Albercas/Juego/interfaz/WOS-PAREDDECRISTAL(Concept Video).mp3')
    mixer.music.play(-1)  # Reproducir en bucle

    # Crear la capa y la escena
    mi_capa = MisAcciones()
    mi_escena = cocos.scene.Scene(mi_capa)

    # Ejecutar la escena
    cocos.director.director.run(mi_escena)