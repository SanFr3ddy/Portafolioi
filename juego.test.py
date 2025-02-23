import unittest
from cocos.menu import Menu, MenuItem, CENTER
from juego import MainMenu

class TestMainMenu(unittest.TestCase):
    def setUp(self):
        self.main_menu = MainMenu()

def test_main_menu_creation(self):
    menu = MainMenu()
    self.assertEqual(len(menu.children), 2)
    self.assertEqual(menu.children[0].label, 'Play')
    self.assertEqual(menu.children[1].label, 'Exit')
    self.assertEqual(menu.font_title['font_size'], 50)
    self.assertEqual(menu.font_item['font_size'], 32)
    self.assertEqual(menu.font_item_selected['font_size'], 42)

def test_main_menu_title_font_size(self):
    menu = MainMenu()
    self.assertEqual(menu.font_title['font_size'], 50)

def test_main_menu_layout_strategy(self):
    menu = MainMenu()
    self.assertIsInstance(menu.layout_strategy, str)
    self.assertEqual(menu.layout_strategy.lower(), 'center')

if __name__ == '__main__':
    unittest.main()
