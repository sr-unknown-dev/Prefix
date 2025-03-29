<?php

declare(strict_types=1);

namespace muqsit\tebex\handler;

use muqsit\tebexapi\endpoint\queue\TebexDuePlayer;
use muqsit\tebexapi\utils\TebexCommand;
use muqsit\tebexapi\utils\TebexGuiItem;
use muqsit\tebex\Loader;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\Server;

final class TebexApiUtils{

	public static function convertGuiItemToItem(TebexGuiItem $gui_item) : Item{
		$item = StringToItemParser::getInstance()->parse($gui_item->value);
		if($item === null){
			$plugin = Server::getInstance()->getPluginManager()->getPlugin("Tebex");
			if($plugin instanceof Loader){
				$plugin->getLogger()->warning("Failed to parse GUI item \"{$gui_item->value}\", using PAPER as fallback");
			}
			return VanillaItems::PAPER();
		}
		return $item;
	}

	public static function onlineFormatCommand(TebexCommand $command, Player $player, TebexDuePlayer $due_player) : string{
		return strtr($command->asRawString(), self::onlineCommandParameters($player, $due_player));
	}

	/**
	 * @param Player $player
	 * @param TebexDuePlayer $due_player
	 * @return array<non-empty-string, string>
	 */
	public static function onlineCommandParameters(Player $player, TebexDuePlayer $due_player) : array{
		$gamertag = "\"{$player->getName()}\"";
		return [
			"{name}" => $gamertag,
			"{player}" => $gamertag,
			"{username}" => "\"{$due_player->name}\"",
			"{id}" => $player->getXuid()
		];
	}

	public static function offlineFormatCommand(TebexCommand $command, TebexDuePlayer $due_player) : string{
		return strtr($command->asRawString(), self::offlineCommandParameters($due_player));
	}

	/**
	 * @param TebexDuePlayer $due_player
	 * @return array<non-empty-string, string>
	 */
	public static function offlineCommandParameters(TebexDuePlayer $due_player) : array{
		$gamertag = "\"{$due_player->name}\"";
		return [
			"{name}" => $gamertag,
			"{player}" => $gamertag,
			"{username}" => $gamertag,
			"{id}" => $due_player->uuid ?? ""
		];
	}
}