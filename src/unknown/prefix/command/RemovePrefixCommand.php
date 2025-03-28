<?php

namespace unknown\prefix\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use unknown\prefix\Loader;

class RemovePrefixCommand extends Command
{
    public function __construct()
    {
        parent::__construct("removeprefix", "Remove your prefix", "/removeprefix");
        $this->setPermission("prefix.admin");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage("This command can only be used in-game.");
            return false;
        }

        $prefixManager = Loader::getInstance()->getPrefixManager();
        $prefixManager->removeTag($sender);
        $sender->sendMessage("Your prefix has been removed.");
        return true;
    }
}