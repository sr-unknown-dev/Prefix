<?php

declare(strict_types=1);

namespace unknown\prefix;

use muqsit\invmenu\InvMenuHandler;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use unknown\prefix\command\PrefixsCommand;
use unknown\prefix\manager\PrefixManager;
use unknown\prefix\command\SetPrefixCommand;
use unknown\prefix\command\RemovePrefixCommand;
use unknown\prefix\command\ViewPrefixCommand;
use unknown\prefix\command\PrefixCommand;
use unknown\prefix\events\Events;

class Loader extends PluginBase
{
    use SingletonTrait;

    public PrefixManager $prefixManager;

    public function onLoad(): void
    {
        self::setInstance($this);
        $this->prefixManager = new PrefixManager(); // Inicializar aquí
    }

    public function onEnable(): void
    {
        if (!InvMenuHandler::isRegistered()) {
            InvMenuHandler::register($this);
        }
        $this->getServer()->getPluginManager()->registerEvents(new Events(), $this);
        $this->getServer()->getCommandMap()->registerAll("prefixsystem", [
            new PrefixCommand(),
            new PrefixsCommand()
        ]);
    }

    public function getPrefixManager(): PrefixManager
    {
        return $this->prefixManager;
    }
}