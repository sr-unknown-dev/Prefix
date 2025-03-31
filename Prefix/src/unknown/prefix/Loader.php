<?php

declare(strict_types=1);

namespace unknown\prefix;

use muqsit\invmenu\InvMenuHandler;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\World;
use unknown\prefix\command\PrefixsCommand;
use unknown\prefix\manager\PrefixManager;
use unknown\prefix\command\SetPrefixCommand;
use unknown\prefix\command\RemovePrefixCommand;
use unknown\prefix\command\ViewPrefixCommand;
use unknown\prefix\command\PrefixCommand;
use unknown\prefix\events\Events;
use unknown\prefix\npc\PrefixNPC;

class Loader extends PluginBase
{
    use SingletonTrait;

    public PrefixManager $prefixManager;

    public function onLoad(): void
    {
        self::setInstance($this);
        $this->prefixManager = new PrefixManager(); // Inicializar aquí zzzzzz Dormí malo
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

        EntityFactory::getInstance()->register(PrefixNPC::class, function (World $world, CompoundTag $nbt): PrefixNPC {
            return new PrefixNPC(EntityDataHelper::parseLocation($nbt, $world), PrefixNPC::parseSkinNBT($nbt), $nbt);
        }, ['PrefixNPC']);

        $this->getLogger()->info("_____        _    _       _                              ");
        $this->getLogger()->info("/ ____|      | |  | |     | |                             ");
        $this->getLogger()->info("| (___  _ __  | |  | |_ __ | | ___ __   _____      ___ __  ");
        $this->getLogger()->info("\___ \| '__| | |  | | '_ \| |/ / '_ \ / _ \ \ /\ / / '_ \ ");
        $this->getLogger()->info("____) | |    | |__| | | | |   <| | | | (_) \ V  V /| | | |");
        $this->getLogger()->info("|_____/|_|     \____/|_| |_|_|\_\_| |_|\___/ \_/\_/ |_| |_|");
        $this->getLogger()->info("");
        $this->getLogger()->info("______               _                       ");
        $this->getLogger()->info("|  _  \             | |                      ");
        $this->getLogger()->info("| | | |_____   _____| | ___  _ __   ___ _ __ ");
        $this->getLogger()->info("| | | / _ \ \ / / _ \ |/ _ \| '_ \ / _ \ '__|");
        $this->getLogger()->info("| |/ /  __/\ V /  __/ | (_) | |_) |  __/ |  ");
        $this->getLogger()->info("|___/ \___| \_/ \___|_|\___/| .__/ \___|_|   ");
        $this->getLogger()->info("                            | |              ");
        $this->getLogger()->info("                            |_|              ");
        $this->getLogger()->info("");
        $this->getLogger()->info("");


    }

    public function getPrefixManager(): PrefixManager
    {
        return $this->prefixManager;
    }
}