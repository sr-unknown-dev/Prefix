<?php

namespace unknown\prefix\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use unknown\prefix\menu\PrefixMenu;

class PrefixMenuCommand extends Command
{
    public function __construct()
    {
        parent::__construct("prefixmenu", "Open the prefix menu", "/prefixmenu");
        $this->setPermission("prefix.user");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage("This command can only be used in-game.");
            return false;
        }

        PrefixMenu::open($sender);
        return true;
    }
}