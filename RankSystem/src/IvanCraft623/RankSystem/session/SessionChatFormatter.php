<?php

declare(strict_types=1);

namespace IvanCraft623\RankSystem\session;

use hcf\HCFLoader;
use hcf\player\Player;
use pocketmine\player\chat\ChatFormatter;
use unknown\prefix\Loader;
use unknown\prefix\Tag;
use pocketmine\utils\TextFormat;

final class SessionChatFormatter implements ChatFormatter
{
    public function __construct(
        private Session $session
    )
    {
    }

    public function format(string $username, string $message): string
    {
        $player = $this->session->getPlayer();
        if ($player instanceof Player) {
            // Obtener el prefijo del jugador primero
            $prefixManager = Loader::getInstance()->getPrefixManager();
            $tag = $prefixManager->getTag($player->getName());
            $prefixFormat = "";
            
            if ($tag !== null) {
                $prefixFormat = $tag->getFormat() . " ";
            }
            
            // Ahora obtenemos el formato del chat del RankSystem
            $factionT = $player->getSession()->getFaction();
            $rankFormat = $this->session->getChatFormat();
            if ($factionT !== null) {
                $faction = HCFLoader::getInstance()->getFactionManager()->getFaction($factionT);
                $name = $faction->getName();
                return str_replace("{message}", $message, $prefixFormat . '§c[§6' . $name . '§c] ' . $rankFormat);
            } else {
                return str_replace("{message}", $message, $prefixFormat . $rankFormat);
            }
        } else {
            return str_replace("{message}", $message, $this->session->getChatFormat());
        }
    }
}