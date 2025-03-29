<?php

namespace unknown\prefix\command\subcommands;

use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\args\TargetPlayerArgument;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use unknown\prefix\Loader;

class SetPrefixSubCommand extends BaseSubCommand
{
    public function __construct()
    {
        parent::__construct("setprefix", "Set your prefix or another player's prefix");
    }

    protected function prepare(): void
    {
        $this->setPermission("prefix.admin");
        $this->registerArgument(0, new RawStringArgument("prefix"));
        $this->registerArgument(1, new RawStringArgument("player", true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player && !isset($args["player"])) {
            $sender->sendMessage("Console usage: /setprefix <prefix> <player>");
            return;
        }

        if (!isset($args["prefix"])) {
            $sender->sendMessage("Usage: /setprefix <prefix> [player]");
            return;
        }

        $prefixManager = Loader::getInstance()->getPrefixManager();
        $prefix = $args["prefix"];

        if (!isset($prefixManager->tags[$prefix])) {
            $sender->sendMessage("Invalid prefix.");
            return;
        }

        $targetPlayer = $sender;
        $targetName = $sender->getName();

        if (isset($args["player"]) && $sender->hasPermission("prefix.admin.others")) {
            $targetPlayer = $args["player"];
            $targetName = $targetPlayer->getName();
        } elseif (isset($args["player"]) && !$sender->hasPermission("prefix.admin.others")) {
            $sender->sendMessage("You don't have permission to set prefixes for other players.");
            return;
        }

        $prefixManager->setTag($targetPlayer, $prefix);
        $tagFormat = $prefixManager->tags[$prefix] ?? "";

        if ($targetPlayer !== $sender) {
            $sender->sendMessage("You set $targetName's prefix to: " . TextFormat::colorize($tagFormat));
        } else {
            $sender->sendMessage("Your prefix has been set to: " . TextFormat::colorize($tagFormat));
        }

        if ($targetPlayer !== $sender) {
            $targetPlayer->sendMessage("Your prefix has been set to: " . TextFormat::colorize($tagFormat) . " by " . $sender->getName());
        }

        $targetPlayer->setDisplayName(TextFormat::colorize($tagFormat) . " " . $targetPlayer->getName());
    }
}