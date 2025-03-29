<?php

#Plugin By:

/*
	8888888                            .d8888b.                   .d888 888     .d8888b.   .d8888b.   .d8888b.  
	  888                             d88P  Y88b                 d88P"  888    d88P  Y88b d88P  Y88b d88P  Y88b 
	  888                             888    888                 888    888    888               888      .d88P 
	  888  888  888  8888b.  88888b.  888        888d888 8888b.  888888 888888 888d888b.       .d88P     8888"  
	  888  888  888     "88b 888 "88b 888        888P"      "88b 888    888    888P "Y88b  .od888P"       "Y8b. 
	  888  Y88  88P .d888888 888  888 888    888 888    .d888888 888    888    888    888 d88P"      888    888 
	  888   Y8bd8P  888  888 888  888 Y88b  d88P 888    888  888 888    Y88b.  Y88b  d88P 888"       Y88b  d88P 
	8888888  Y88P   "Y888888 888  888  "Y8888P"  888    "Y888888 888     "Y888  "Y8888P"  888888888   "Y8888P"  
*/

declare(strict_types=1);

namespace IvanCraft623\RankSystem\form;

use IvanCraft623\RankSystem\RankSystem;

use IvanCraft623\RankSystem\libs\jojoe77777\FormAPI\SimpleForm;

use pocketmine\player\Player;

final class ManageForm {
	
	public function __construct() {
	}

	public function send(Player $player) : void {
		$form = new SimpleForm(function (Player $player, int $result = null) {
			if ($result === null) {
				return;
			}
			switch ($result) {
				case 0:
					FormManager::getInstance()->sendRanksManager($player);
					break;

				case 1:
					FormManager::getInstance()->sendUserManager($player);
					break;
				
				default:
					# Close Form
					break;
			}
		});
		$translator = RankSystem::getInstance()->getTranslator();
		$form->setTitle($translator->translate($player, "form.manage.title"));
		$form->setContent($translator->translate($player, "form.select_category"));
		$form->addButton($translator->translate($player, "text.ranks"), SimpleForm::IMAGE_TYPE_PATH, "textures/ui/op");
		$form->addButton($translator->translate($player, "text.users"), SimpleForm::IMAGE_TYPE_PATH, "textures/ui/FriendsIcon");
		$form->addButton($translator->translate($player, "text.exit"), SimpleForm::IMAGE_TYPE_PATH, "textures/blocks/barrier");
		$form->sendToPlayer($player);
	}
}