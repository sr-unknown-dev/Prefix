<?php

declare(strict_types=1);

namespace IvanCraft623\RankSystem\command\subcommands;

use IvanCraft623\RankSystem\libs\CortexPE\Commando\args\RawStringArgument;
use IvanCraft623\RankSystem\libs\CortexPE\Commando\BaseCommand;
use IvanCraft623\RankSystem\libs\CortexPE\Commando\BaseSubCommand;

use IvanCraft623\RankSystem\command\args\RankArgument;
use IvanCraft623\RankSystem\command\args\TimeArgument;
use IvanCraft623\RankSystem\RankSystem;
use IvanCraft623\RankSystem\rank\Rank;
use IvanCraft623\RankSystem\utils\Utils;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;

final class SetRankCommand extends BaseSubCommand {

	public function __construct(private RankSystem $plugin) {
		parent::__construct("setrank", "Set a rank to a user", ["set"]);
		$this->setPermission("ranksystem.command.setrank");
	}

	protected function prepare() : void {
		$this->registerArgument(0, new RawStringArgument("user"));
		$this->registerArgument(1, new RankArgument("rank"));
		$this->registerArgument(2, new TimeArgument("time", true));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {
		$translator = $this->plugin->getTranslator();
		if (array_key_exists("time", $args) && $args["time"] === "null") {
			$sender->sendMessage(
				$translator->translate($sender, "time.invalid")."\n".
				$translator->translate($sender, "time.arguments")."\n".
				$translator->translate($sender, "time.example")
			);
		} else {
			$session = $this->plugin->getSessionManager()->get($args["user"]);
			$session->onInitialize(function () use ($session, $sender, $args, $translator) {
				if ($session->hasRank($args["rank"])) {
					$sender->sendMessage($translator->translate($sender, "user.set_rank.already_has", [
						"{%user}" => $session->getName(),
						"{%rank}" => $args["rank"]->getName()
					]));
				} else {
					$time = isset($args["time"]) ? ((int) ($args["time"])) : null;

					$session->setRank($args["rank"], $time);
					$sender->sendMessage($translator->translate($sender, "user.set_rank.success", [
						"{%user}" => $session->getName(),
						"{%rank}" => $args["rank"]->getName(),
						"{%time}" => (isset($args["time"]) ? Utils::getTimeTranslated($time - time(), $translator, $sender) : $translator->translate($sender, "text.never"))
					]));
				}
			});
		}
	}

	public function getParent() : BaseCommand {
		return $this->parent;
	}
}