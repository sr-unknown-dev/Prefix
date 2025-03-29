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

use IvanCraft623\RankSystem\libs\jojoe77777\FormAPI\SimpleForm;

use IvanCraft623\RankSystem\libs\IvanCraft623\languages\Translator;
use IvanCraft623\RankSystem\RankSystem;
use IvanCraft623\RankSystem\rank\Rank;
use IvanCraft623\RankSystem\rank\RankManager;
use IvanCraft623\RankSystem\utils\Utils;

use pocketmine\player\Player;

final class RanksManageForm {

	private Translator $translator;
	
	public function __construct() {
		$this->translator = RankSystem::getInstance()->getTranslator();
	}

	public function send(Player $player) : void {
		$form = new SimpleForm(function (Player $player, int $result = null) {
			if ($result === null) {
				return;
			}
			switch ($result) {
				case 0:
					FormManager::getInstance()->sendInsertText(
						$player,
						$this->translator->translate($player, "form.ranks_manage.title"),
						$this->translator->translate($player, "form.ranks_manage.create"),
						$this->translator->translate($player, "text.rank") . ":"
					)->onCompletion(
						function (string $rank) use ($player) {
							if (RankManager::getInstance()->exists($rank)) {
								$player->sendMessage($this->translator->translate($player, "rank.already_exists", [
									"{%rank}" => $rank
								]));
							} else {
								FormManager::getInstance()->sendRankEditor(
									$player,
									$rank,
									["prefix" => "§8[§7" . $rank . "§8] ", "nameColor" => "§f"],
									["prefix" => "§8[§7" . $rank . "§8] ", "nameColor" => "§f", "chatFormat" => "§e: §7"]
								);
							}
						}, function () {} // No response
					);
					break;

				case 1:
					FormManager::getInstance()->sendSelectRank($player, $this->translator->translate($player, "form.ranks_manage.title"))->onCompletion(
						function (Rank $rank) use ($player) {
							FormManager::getInstance()->sendRankEditor(
								$player,
								$rank->getName(),
								$rank->getNameTagFormat(),
								$rank->getChatFormat(),
								$rank->getPermissions(),
								Utils::getRanksNames($rank->getInheritance())
							);
						}, function () {} // No response
					);
					break;

				case 2:
					FormManager::getInstance()->sendSelectRank($player, $this->translator->translate($player, "form.ranks_manage.title"))->onCompletion(
						function (Rank $rank) use ($player) {
							FormManager::getInstance()->sendConfirmation(
								$player,
								$this->translator->translate($player, "form.ranks_manage.title"),
								$this->translator->translate($player, "form.ranks_manage.delete.confirm", [
									"{%rank}" => $rank->getName()
								])
							)->onCompletion(
								function (bool $result) use ($player, $rank) {
									if ($result) {
										RankManager::getInstance()->delete($rank);
										$player->sendMessage($this->translator->translate($player, "rank.delete.success", [
											"{%rank}" => $rank->getName()
										]));
									}
								}, function () {} // No response
							);
						}, function () {} // No response
					);
					break;

				case 3:
					FormManager::getInstance()->sendSelectRank($player, $this->translator->translate($player, "form.ranks_manage.title"))->onCompletion(
						function (Rank $rank) use ($player) {
							FormManager::getInstance()->sendRankInfo($player, $rank);
						}, function () {} // No response
					);
					break;
				
				default:
					# Close Form
					break;
			}
		});
		$form->setTitle($this->translator->translate($player, "form.ranks_manage.title"));
		$form->setContent($this->translator->translate($player, "form.select_category"));
		$form->addButton($this->translator->translate($player, "text.create"), SimpleForm::IMAGE_TYPE_PATH, "textures/ui/anvil-plus");
		$form->addButton($this->translator->translate($player, "text.edit"), SimpleForm::IMAGE_TYPE_PATH, "textures/gui/newgui/Bundle/PaintBrush");
		$form->addButton($this->translator->translate($player, "text.delete"), SimpleForm::IMAGE_TYPE_PATH, "textures/ui/icon_trash");
		$form->addButton($this->translator->translate($player, "text.information"), SimpleForm::IMAGE_TYPE_PATH, "textures/items/map_filled");
		$form->addButton($this->translator->translate($player, "text.exit"), SimpleForm::IMAGE_TYPE_PATH, "textures/blocks/barrier");
		$form->sendToPlayer($player);
	}
}