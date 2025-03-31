<?php

namespace unknown\prefix\npc;

use pocketmine\entity\Human;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use unknown\prefix\menu\PrefixMenu;

class PrefixNPC extends Human
{
    public bool $canCollide = false;
    protected bool $immobile = false;

    protected function getInitialDragMultiplier() : float{ return 0.00; }

    protected function getInitialGravity() : float{ return 0.00; }

    public static function create(Player $player): self
    {
        $nbt = CompoundTag::create()
            ->setTag("Pos", new ListTag([
                new DoubleTag($player->getLocation()->x),
                new DoubleTag($player->getLocation()->y),
                new DoubleTag($player->getLocation()->z)
            ]))
            ->setTag("Motion", new ListTag([
                new DoubleTag($player->getMotion()->x),
                new DoubleTag($player->getMotion()->y),
                new DoubleTag($player->getMotion()->z)
            ]))
            ->setTag("Rotation", new ListTag([
                new FloatTag($player->getLocation()->yaw),
                new FloatTag($player->getLocation()->pitch)
            ]));
        return new self($player->getLocation(), $player->getSkin(), $nbt);
    }

    public function canBeMovedByCurrents(): bool
    {
        return false;
    }

    /**
     * @param int $currentTick
     *
     * @return bool
     * @throws \Exception
     */
    public function onUpdate(int $currentTick): bool
    {
        $text = TextFormat::colorize("&8[&aPrefix&8] &7NPC");

        $this->setNameTagAlwaysVisible();
        $this->setNameTag($text);
        return parent::onUpdate($currentTick);
    }

    /**
     * @param EntityDamageEvent $source
     */
    public function attack(EntityDamageEvent $source): void {

        $source->cancel();

        if ($source instanceof EntityDamageByEntityEvent) {
            $damager = $source->getDamager();

            if ($damager instanceof Player) {
                $itemH = $damager->getInventory()->getItemInHand();
                if ($itemH->getNamedTag()->getTag("prefix") && $itemH->getNamedTag()->getString("prefix") === "remove" && $damager->hasPermission("prefix.admin")) {
                    $this->kill();
                    return;
                }
                PrefixMenu::open($damager);
            }
        }
    }
}