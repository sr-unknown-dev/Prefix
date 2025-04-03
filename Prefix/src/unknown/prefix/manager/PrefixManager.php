<?php

namespace unknown\prefix\manager;

use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use unknown\prefix\Loader;
use unknown\prefix\Tag;

class PrefixManager
{
    public Config $config;
    public array $prefixes = [];

    public array $tags = [
        # Países
        "MEX" => "§8[ §l§a|§f|§4| §r§8]§r",
        "USA" => "§8[ §l§f|§b|§4| §r§8]§r",
        "BRA" => "§8[ §l§2|§f|§e| §r§8]§r",
        "COL" => "§8[ §l§e|§f|§4| §r§8]§r",
        "ARG" => "§8[ §l§b|§f|§b| §r§8]§r",
        "PER" => "§8[ §l§r|§w|§r| §r§8]§r",
        "ESP" => "§8[ §l§e|§r|§r| §r§8]§r",

        # Rangos o estilos PvP
        "CHEATER" => "§8[ §cCheater §r§8]§r",
        "MONEY" => "§8[ §a$$$ §r§8]§r",
        "KILLER" => "§8[ §cKiller §r§8]§r",
        "GOD" => "§8[ §6God §r§8]§r",
        "HUNTER" => "§8[ §2Hunter §r§8]§r",
        "NOOB" => "§8[ §7Noob §r§8]§r",
        "TRYHARD" => "§8[ §5TryHard §r§8]§r",
        "DESTROYER" => "§8[ §cDestroyer §r§8]§r",
        "SNIPER" => "§8[ §3Sniper §r§8]§r",
        "BLOOD" => "§8[ §4Blood §r§8]§r",
        "COMBO" => "§8[ §bCombo §r§8]§r",
        "ASSASSIN" => "§8[ §8Assassin §r§8]§r",
        "GHOST" => "§8[ §7Ghost §r§8]§r",

        # Estado del jugador
        "RICH" => "§8[ §6Rich §r§8]§r",
        "POOR" => "§8[ §7Poor §r§8]§r",
        "WARRIOR" => "§8[ §cWarrior §r§8]§r",
        "KING" => "§8[ §eKing §r§8]§r",
        "QUEEN" => "§8[ §dQueen §r§8]§r",
        "RANDOM" => "§8[ §7Random §r§8]§r",

        # Memes o cosas graciosas
        "LAGGER" => "§8[ §8Lagger §r§8]§r",
        "BOT" => "§8[ §7Bot §r§8]§r",
        "EZ" => "§8[ §bEZ §r§8]§r",
        "SIMP" => "§8[ §dSimp §r§8]§r",
    ];

    public function __construct()
    {
        $this->config = new Config(Loader::getInstance()->getDataFolder() . "prefixes.json", Config::JSON);
        $this->load();
    }

    public function load(): void
    {
        $this->prefixes = $this->config->get("prefixes", []);
    }

    public function setTag(Player $player, string $tagName): void
    {
        if (isset($this->tags[$tagName])) {
            $this->prefixes[$player->getName()] = $tagName;
            $this->save();
        }
    }

    public function removeTag(Player $player): void
    {
        unset($this->prefixes[$player->getName()]);
        $this->save();
    }


    public
    function getTag(string $playerName): ?Tag
    {
        return isset($this->prefixes[$playerName]) ? $this->prefixes[$playerName] : null;
    }

    public
    function getTagFormat(string $tagName)
    {
        return $this->tags[$tagName] ?? null;
    }

    public
    function getTagName(string $playerName): ?string
    {
        return $this->prefixes[$playerName] ?? null;
    }

    public
    function getTags(): array
    {
        $result = [];
        foreach ($this->tags as $tagName => $tagFormat) {
            $result[] = "{$tagName}  format:  {$tagFormat}";
        }
        return empty($result) ? ["No tags existed"] : $result;
    }

    public
    function save(): void
    {
        $this->config->setAll("player", $this->prefixes);
        $this->config->save();
    }
}