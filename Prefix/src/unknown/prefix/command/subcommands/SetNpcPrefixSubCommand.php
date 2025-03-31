<?php

namespace unknown\prefix\command\subcommands;

use CortexPE\Commando\BaseSubCommand;
use hcf\entity\server\PrefixNPC;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class SetNpcPrefixSubCommand extends BaseSubCommand
{
    public function __construct()
    {
        parent::__construct("setnpc", "Set the prefix NPC");
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
            $entity = PrefixNPC::create($sender);
            $entity->spawnToAll();
        }
    }

    public function getPermission(): ?string
    {
        return "prefix.admin";
    }
}