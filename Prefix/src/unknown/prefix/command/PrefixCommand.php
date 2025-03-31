<?php

namespace unknown\prefix\command;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use unknown\prefix\command\subcommands\RemoveNpcPrefixSubCommand;
use unknown\prefix\command\subcommands\RemovePrefixSubCommand;
use unknown\prefix\command\subcommands\SetNpcPrefixSubCommand;
use unknown\prefix\command\subcommands\SetPrefixCommand;
use unknown\prefix\command\subcommands\SetPrefixSubCommand;
use unknown\prefix\Loader;
use unknown\prefix\menu\PrefixMenu;

class PrefixCommand extends BaseCommand
{
    public function __construct()
    {
        parent::__construct(Loader::getInstance(), "prefix", "Prefix Commands");
    }

    protected function prepare(): void
    {
        $this->setPermission($this->getPermission());
        $this->registerSubCommand(new SetPrefixSubCommand());
        $this->registerSubCommand(new RemovePrefixSubCommand());
        $this->registerSubCommand(new SetNpcPrefixSubCommand());
        $this->registerSubCommand(new RemoveNpcPrefixSubCommand());
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage("This command can only be used in-game.");
        }

        if ($sender instanceof Player){
            $sender->sendMessage("Prefix Command");
            $sender->sendMessage("/prefix setprefix <prefix> <player>\n/prefix removeprefix <player>\n/prefix list\n/prefix setnpc\n/prefix removenpc");
        }
    }

    public function getPermission(): ?string
    {
        return "prefix.admin";
    }
}