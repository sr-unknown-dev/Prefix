<?php

namespace unknown\prefix\command\subcommands;

use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use unknown\prefix\Loader;

class ListPrefixSubCommand extends BaseSubCommand
{
    public function __construct()
    {
        parent::__construct("list", "View list the prefixs");
    }

    /**
     * @inheritDoc
     */
    protected function prepare(): void
    {
        $this->setPermission($this->getPermission());
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player) return;

        if ($sender instanceof Player)
        {
            foreach (Loader::getInstance()->getPrefixManager()->getTags() as $tag) {
                $sender->sendMessage(TextFormat::colorize($tag));
            }
        }
    }

    public function getPermission(): ?string
    {
        return "prefix.admin";
    }
}