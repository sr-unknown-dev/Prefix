<?php

namespace unknown\prefix\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use unknown\prefix\Loader;
use unknown\prefix\Tag;

class SetPrefixCommand extends Command
{
    public function __construct()
    {
        parent::__construct("setprefix", "Set your prefix", "/setprefix <prefix>");
        $this->setPermission("prefix.admin");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage("This command can only be used in-game.");
            return false;
        }

        if (count($args) < 1) {
            $sender->sendMessage("Usage: /setprefix <prefix>");
            return false;
        }

        $prefix = implode(" ", $args);
        $prefixManager = Loader::getInstance()->getPrefixManager();
        if (!isset($prefixManager->tags[$prefix])) {
            $sender->sendMessage("Invalid prefix.");
            return false;
        }

        // Usar directamente el nombre del tag en lugar de crear un objeto Tag
        $prefixManager->setTag($sender, $prefix);
        $tagFormat = $prefixManager->tags[$prefix] ?? "";
        $sender->sendMessage("Your prefix has been set to: " . $tagFormat);
        
        // Actualizar el displayName del jugador
        $sender->setDisplayName($tagFormat . " " . $sender->getName());
        
        return true;
    }
}