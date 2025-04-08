import cocos
from cocos.scenes.transitions import FadeTransition
from cocos.actions import Delay, CallFunc

class VerTexto(cocos.layer.Layer):
    def __init__(self):
        super().__init__()

        mi_etiqueta = cocos.text.Label(
            "Escena 1",
            font_name="Consolas",
            font_size=18,
            anchor_x="center", anchor_y="center"
        )
        mi_etiqueta.position = (320, 240)
        self.add(mi_etiqueta)

        
        self.do(Delay(3) + CallFunc(self.cambiar_escena))

    def cambiar_escena(self):
        nueva_escena = cocos.scene.Scene(VerTexto2())
        cocos.director.director.replace(FadeTransition(nueva_escena, duration=2))

class VerTexto2(cocos.layer.Layer):
    def __init__(self):
        super().__init__()

        mi_etiqueta = cocos.text.Label(
            "Escena 2",
            font_name="Consolas",
            font_size=18,
            anchor_x="center", anchor_y="center"
        )
        mi_etiqueta.position = (320, 240)
        self.add(mi_etiqueta)

if __name__ == "__main__":

    cocos.director.director.init(caption="Sencilla transición entre escenas")

    
    escena_1 = cocos.scene.Scene(VerTexto())

    
    cocos.director.director.run(escena_1)

