<?php

#Plugin By:

/*
	8888888                            .d8888b.                   .d888 888     .d8888b.   .d8888b.   .d8888b.  
	  888                             d88P  Y88b                 d88P"  888    d88P  Y88b d88P  Y88b d88P  Y88b 
	  888                             888    888                 888    888    888               888      .d88P 
	  888  888  888  8888b.  88888b.  888        888d888 8888b.  888888 888888 888d888b.       .d88P     8888"  
	  888  888  888     "88b 888 "88b 888        888P"      "88b 888    888    888P "Y88b  .od888P"       "Y8b. 
	  888  Y88  88P .d888888 888  888 888    888 888    .d888888 888    888    888    888 d88P"      888    888 
	  888   Y8bd8P  888  888 888  888 Y88b  d88P 888    888  888 888    Y88b.  Y88b  d88P 888"       Y88b  d88P 
	8888888  Y88P   "Y888888 888  888  "Y8888P"  888    "Y888888 888     "Y888  "Y8888P"  888888888   "Y8888P"  
*/

declare(strict_types=1);

namespace IvanCraft623\RankSystem\session;

use hcf\HCFLoader;
use hcf\player\Player;
use pocketmine\player\chat\ChatFormatter;
use unknown\prefix\Loader;

final class SessionChatFormatter implements ChatFormatter {

	public function __construct(
		private Session $session
	) {
	}

	public function format(string $username, string $message) : string {
        
        $player = $this->session->getPlayer();
        if ($player instanceof Player) {
            $factionT = $player->getSession()->getFaction();
            $prefixT = Loader::getInstance()->getPrefixManager()->getTagName($player->getName());
            if ($prefixT !== null && $factionT !== null) {
                $faction = HCFLoader::getInstance()->getFactionManager()->getFaction($player->getSession()->getFaction());
                $name = $faction->getName();
                $prefix = Loader::getInstance()->getPrefixManager()->getTagName($player->getName());
                $format = Loader::getInstance()->getPrefixManager()->getTagFormat($prefix);
                return str_replace("{message}", $message, '§r'.$format.'§r §c[§6'.$name.'§c] '.$this->session->getChatFormat());
            } elseif ($prefixT !== null) {
                $prefix = Loader::getInstance()->getPrefixManager()->getTagName($player->getName());
                $format = Loader::getInstance()->getPrefixManager()->getTagFormat($prefix);
                return str_replace("{message}", $message, '§r'.$format.'§r '.$this->session->getChatFormat());
            } elseif ($factionT !== null) {
                $faction = HCFLoader::getInstance()->getFactionManager()->getFaction($player->getSession()->getFaction());
                $name = $faction->getName();
                return str_replace("{message}", $message, '§c[§6'.$name.'§c] '.$this->session->getChatFormat());
            } else {
                return str_replace("{message}", $message, $this->session->getChatFormat());
            }
        } else {
            return str_replace("{message}", $message, $this->session->getChatFormat());
        }
        
	}
}