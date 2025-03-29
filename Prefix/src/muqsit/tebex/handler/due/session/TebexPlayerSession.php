<?php

declare(strict_types=1);

namespace muqsit\tebex\handler\due\session;

use Closure;
use muqsit\tebex\event\TebexExecuteOnlineCommandEvent;
use muqsit\tebexapi\endpoint\queue\commands\online\TebexQueuedOnlineCommand;
use muqsit\tebexapi\endpoint\queue\TebexDuePlayer;
use muqsit\tebex\handler\command\TebexCommandSender;
use muqsit\tebex\handler\TebexApiUtils;
use muqsit\tebex\Loader;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskScheduler;

final class TebexPlayerSession{

	private static TaskScheduler $scheduler;

	public static function init(Loader $plugin) : void{
		self::$scheduler = $plugin->getScheduler();
	}

	/** @var array<int, DelayedOnlineCommandHandler> */
	private array $delayed_online_command_handlers = [];

	public function __construct(
		readonly private Player $player
	){}

	public function getPlayer() : Player{
		return $this->player;
	}

	public function destroy() : void{
		foreach($this->delayed_online_command_handlers as $handler){
			$handler->handler->cancel();
		}
		$this->delayed_online_command_handlers = [];
	}

	/**
	 * @param Loader $plugin
	 * @param TebexQueuedOnlineCommand $command
	 * @param TebexDuePlayer $due_player
	 * @param Closure(string|null) : void $callback
	 */
	public function executeOnlineCommand(Loader $plugin, TebexQueuedOnlineCommand $command, TebexDuePlayer $due_player, Closure $callback) : void{
		$conditions = $command->conditions;
		$delay = $conditions->delay;
		if($delay > 0){
			$this->scheduleCommandForDelay($plugin, $command, $due_player, $delay * 20, $callback);
		}else{
			$callback($this->instantlyExecuteOnlineCommand($plugin, $command, $due_player));
		}
	}

	/**
	 * @param Loader $plugin
	 * @param TebexQueuedOnlineCommand $command
	 * @param TebexDuePlayer $due_player
	 * @param int $delay
	 * @param Closure(string|null) : void $callback
	 * @return bool
	 */
	private function scheduleCommandForDelay(Loader $plugin, TebexQueuedOnlineCommand $command, TebexDuePlayer $due_player, int $delay, Closure $callback) : bool{
		if(isset($this->delayed_online_command_handlers[$id = $command->id])){
			return false;
		}

		$this->delayed_online_command_handlers[$id] = new DelayedOnlineCommandHandler($command, self::$scheduler->scheduleDelayedTask(new ClosureTask(function() use($id, $command, $due_player, $callback, $plugin) : void{
			$callback($this->instantlyExecuteOnlineCommand($plugin, $command, $due_player));
			unset($this->delayed_online_command_handlers[$id]);
		}), $delay));
		return true;
	}

	private function instantlyExecuteOnlineCommand(Loader $plugin, TebexQueuedOnlineCommand $command, TebexDuePlayer $due_player) : ?string{
		$conditions = $command->conditions;
		$slots = $conditions->slots;
		if($slots > 0){
			$inventory = $this->player->getInventory();
			$free_slots = $inventory->getSize() - count($inventory->getContents());
			if($free_slots < $slots){
				return null;
			}
		}

		$original_placeholders = TebexApiUtils::onlineCommandParameters($this->player, $due_player);
		$event = new TebexExecuteOnlineCommandEvent($plugin, $this->player, $due_player, $command, $original_placeholders, $original_placeholders);
		$event->call();
		if($event->isCancelled()){
			return null;
		}

		$command_string = $event->getFinalCommand();
		if(!$this->player->getServer()->dispatchCommand(TebexCommandSender::getInstance(), $command_string)){
			return null;
		}

		return $command_string;
	}
}