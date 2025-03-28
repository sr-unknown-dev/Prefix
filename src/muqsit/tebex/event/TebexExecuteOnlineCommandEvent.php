<?php

declare(strict_types=1);

namespace muqsit\tebex\event;

use muqsit\tebex\Loader;
use muqsit\tebexapi\endpoint\queue\commands\online\TebexQueuedOnlineCommand;
use muqsit\tebexapi\endpoint\queue\TebexDuePlayer;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\player\Player;

final class TebexExecuteOnlineCommandEvent extends TebexEvent implements Cancellable{
	use CancellableTrait;

	/**
	 * @param Loader $plugin
	 * @param Player $player
	 * @param TebexDuePlayer $due_player
	 * @param TebexQueuedOnlineCommand $command
	 * @param array<non-empty-string, string> $original_placeholders
	 * @param array<non-empty-string, string> $placeholders
	 */
	public function __construct(
		Loader $plugin,
		readonly public Player $player,
		readonly public TebexDuePlayer $due_player,
		readonly public TebexQueuedOnlineCommand $command,
		readonly public array $original_placeholders,
		public array $placeholders
	){
		parent::__construct($plugin);
	}

	public function getFinalCommand() : string{
		return strtr($this->command->command->asRawString(), $this->placeholders);
	}
}