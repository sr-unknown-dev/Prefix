<?php

declare(strict_types=1);

namespace IvanCraft623\RankSystem\event;

use IvanCraft623\RankSystem\rank\Rank;
use IvanCraft623\RankSystem\session\Session;

use pocketmine\event\Event;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;

class UserRankRemoveEvent extends Event implements Cancellable {
	use CancellableTrait;

	protected Session $session;

	protected Rank $rank;

	/**
	 * UserRankRemoveEvent constructor.
	 * @param Session $session
	 * @param Rank $rank
	 */
	public function __construct(Session $session, Rank $rank) {
		$this->session = $session;
		$this->rank = $rank;
	}

	/**
	 * @return Session
	 */
	public function getSession() : Session {
		return $this->session;
	}

	/**
	 * @return Rank
	 */
	public function getRank() : Rank {
		return $this->rank;
	}
}