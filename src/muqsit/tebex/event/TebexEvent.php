<?php

declare(strict_types=1);

namespace muqsit\tebex\event;

use BadMethodCallException;
use muqsit\tebex\Loader;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\plugin\Plugin;
use function assert;

abstract class TebexEvent extends PluginEvent{

	public function __construct(Plugin $plugin){
		parent::__construct($plugin);
		$plugin instanceof Loader || throw new BadMethodCallException("Expected plugin to be " . Loader::class);
	}

	public function getPlugin() : Loader{
		$plugin = parent::getPlugin();
		assert($plugin instanceof Loader);
		return $plugin;
	}
}