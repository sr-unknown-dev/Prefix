<?php

namespace unknown\prefix\command\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use unknown\prefix\Loader;

class RemovePrefixSubCommand extends BaseSubCommand
{
    public function __construct()
    {
        parent::__construct("removeprefix", "Remove your prefix or another player's prefix");
    }

    protected function prepare(): void
    {
        $this->setPermission("prefix.admin");
        $this->registerArgument(0, new RawStringArgument("player", true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player && !isset($args["player"])) {
            $sender->sendMessage("Console usage: /removeprefix <player>");
            return;
        }

        $prefixManager = Loader::getInstance()->getPrefixManager();

        $targetPlayer = $sender;
        $targetName = $sender instanceof Player ? $sender->getName() : "Console";

        if (isset($args["player"]) && $sender->hasPermission("prefix.admin.others")) {
            $targetPlayer = $args["player"];
            $targetName = $targetPlayer->getName();
        } elseif (isset($args["player"]) && !$sender->hasPermission("prefix.admin.others")) {
            $sender->sendMessage("You don't have permission to remove prefixes from other players.");
            return;
        }

        $prefixManager->removeTag($targetPlayer);

        if ($targetPlayer !== $sender) {
            $sender->sendMessage("You removed $targetName's prefix.");
        } else {
            $sender->sendMessage("Your prefix has been removed.");
        }

        if ($targetPlayer !== $sender) {
            $targetPlayer->sendMessage("Your prefix has been removed by " . $sender->getName());
        }
    }
}