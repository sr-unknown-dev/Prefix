<?php

declare(strict_types=1);

namespace IvanCraft623\RankSystem\command\subcommands;

use IvanCraft623\RankSystem\libs\CortexPE\Commando\BaseCommand;
use IvanCraft623\RankSystem\libs\CortexPE\Commando\BaseSubCommand;
use IvanCraft623\RankSystem\libs\CortexPE\Commando\constraint\InGameRequiredConstraint;

use IvanCraft623\RankSystem\command\args\RankArgument;
use IvanCraft623\RankSystem\RankSystem;
use IvanCraft623\RankSystem\utils\Utils;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;

final class EditCommand extends BaseSubCommand {

	public function __construct(private RankSystem $plugin) {
		parent::__construct("edit", "Edit a Rank");
		$this->setPermission("ranksystem.command.edit");
	}

	protected function prepare() : void {
		$this->registerArgument(0, new RankArgument("rank"));
		$this->addConstraint(new InGameRequiredConstraint($this));
	}

	/**
	 * @param Player $sender
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {
		$this->plugin->getFormManager()->sendRankEditor(
			$sender,
			$args["rank"]->getName(),
			$args["rank"]->getNameTagFormat(),
			$args["rank"]->getChatFormat(),
			$args["rank"]->getPermissions(),
			Utils::getRanksNames($args["rank"]->getInheritance())
		);
	}

	public function getParent() : BaseCommand {
		return $this->parent;
	}
}