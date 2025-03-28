<?php

namespace unknown\prefix\events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\player\chat\LegacyRawChatFormatter;
use unknown\prefix\Loader;

class Events implements Listener
{
    public function onPlayerJoin(PlayerJoinEvent $event): void
    {
        $player = $event->getPlayer();
        $prefixManager = Loader::getInstance()->getPrefixManager();
        $tag = $prefixManager->getTag($player->getName());
        if ($tag !== null) {
            $player->setDisplayName($tag->getFormat() . " " . $player->getName());
        }
    }

    public function onPlayerChat(PlayerChatEvent $event): void
    {
        $player = $event->getPlayer();
        $prefixManager = Loader::getInstance()->getPrefixManager();
        $tag = $prefixManager->getTag($player->getName());
        if ($tag !== null) {
            $event->setFormatter(new LegacyRawChatFormatter($tag->getFormat() . " §r" . $player->getName() . ": " . $event->getMessage()));
        } else {
            $event->setFormatter(new LegacyRawChatFormatter($player->getName() . ": " . $event->getMessage()));
        }
    }
}