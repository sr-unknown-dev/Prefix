<?php

declare(strict_types=1);

namespace unknown\prefix;

use muqsit\invmenu\InvMenuHandler;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use unknown\prefix\manager\PrefixManager;
use unknown\prefix\command\SetPrefixCommand;
use unknown\prefix\command\RemovePrefixCommand;
use unknown\prefix\command\ViewPrefixCommand;
use unknown\prefix\command\PrefixMenuCommand;
use unknown\prefix\events\Events;

class Loader extends PluginBase
{
    use SingletonTrait;

    public PrefixManager $prefixManager;

    public function onLoad(): void
    {
        self::setInstance($this);
    }

    public function onEnable(): void
    {
        if (!InvMenuHandler::isRegistered()) {
            InvMenuHandler::register($this);
        }
        $this->prefixManager = new PrefixManager();
        $this->getServer()->getPluginManager()->registerEvents(new Events(), $this);
        $this->getServer()->getCommandMap()->registerAll("prefix", [
            new SetPrefixCommand(),
            new RemovePrefixCommand(),
            new ViewPrefixCommand(),
            new PrefixMenuCommand()
        ]);
    }

    public function getPrefixManager(): PrefixManager
    {
        return $this->prefixManager;
    }
}