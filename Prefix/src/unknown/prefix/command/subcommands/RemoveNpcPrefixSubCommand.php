<?php

namespace unknown\prefix\command\subcommands;

use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

class RemoveNpcPrefixSubCommand extends BaseSubCommand
{
    public function __construct()
    {
        parent::__construct("removenpc", "Remove the prefix NPC");
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

        if ($sender instanceof Player) {
            $item = VanillaItems::GOLDEN_AXE()->setCustomName("&cRemove Prefix NPC");
            $item->setLore(["&7Click to remove the prefix NPC"]);
            $item->getNamedTag()->setString("prefix", "remove");
            $sender->getInventory()->addItem($item);
        }
    }

    public function getPermission(): ?string
    {
        return "prefix.admin";
    }
}