<?php

namespace unknown\prefix\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use unknown\prefix\Loader;

class ViewPrefixCommand extends Command
{
    public function __construct()
    {
        parent::__construct("viewprefix", "View your prefix", "/viewprefix");
        $this->setPermission("prefix.admin");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage("This command can only be used in-game.");
            return false;
        }

        $prefixManager = Loader::getInstance()->getPrefixManager();
        $tag = $prefixManager->getTag($sender->getName());
        if ($tag !== null) {
            $sender->sendMessage("Your prefix is: " . $tag->getFormat());
        } else {
            $sender->sendMessage("You do not have a prefix.");
        }
        return true;
    }
}