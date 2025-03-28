<?php

namespace unknown\prefix;

use pocketmine\utils\TextFormat;
use unknown\prefix\manager\PrefixManager;

class Tag
{
    public $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFormat(): string
    {
        $prefixManager = Loader::getInstance()->getPrefixManager();
        $prefix = $prefixManager->tags[$this->name] ?? "";
        return TextFormat::colorize($prefix);
    }
}