<?php

namespace unknown\prefix\menu;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use unknown\prefix\Loader;
use unknown\prefix\Tag;

class PrefixMenu
{
    private const ITEMS_PER_PAGE = 45; // 54 slots in a double chest, 9 reserved for navigation

    public static function open(Player $player, int $page = 1): void
    {
        $prefixManager = Loader::getInstance()->getPrefixManager();
        $tags = array_chunk($prefixManager->tags, self::ITEMS_PER_PAGE, true);
        $totalPages = count($tags);

        if ($page < 1 || $page > $totalPages) {
            $page = 1;
        }

        $menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
        $menu->setName(TextFormat::GREEN . "Prefix Menu - Page $page/$totalPages");

        foreach ($tags[$page - 1] as $tagName => $tagFormat) {
            $item = VanillaItems::PAPER();
            $item->setCustomName(TextFormat::colorize($tagFormat . " " . $tagName));
            $item->getNamedTag()->setString("prefix", $tagName);
            $menu->getInventory()->addItem($item);
        }

        // Add navigation items
        if ($page > 1) {
            $prevItem = VanillaItems::ARROW();
            $prevItem->setCustomName(TextFormat::YELLOW . "Previous Page");
            $prevItem->getNamedTag()->setInt("page", $page - 1);
            $menu->getInventory()->setItem(45, $prevItem);
        }

        if ($page < $totalPages) {
            $nextItem = VanillaItems::ARROW();
            $nextItem->setCustomName(TextFormat::YELLOW . "Next Page");
            $nextItem->getNamedTag()->setInt("page", $page + 1);
            $menu->getInventory()->setItem(53, $nextItem);
        }

        $menu->setListener(function (InvMenuTransaction $transaction) use ($prefixManager, $player): InvMenuTransactionResult {
            $itemClicked = $transaction->getItemClicked();
            $tagName = $itemClicked->getNamedTag()->getString("prefix", "");
            $page = $itemClicked->getNamedTag()->getInt("page", 0);

            if ($tagName !== "") {
                $tag = new Tag($tagName);
                $prefixManager->setTag($player, $tag);
                $player->sendMessage("Your prefix has been set to: " . $tag->getFormat());
            } elseif ($page > 0) {
                self::open($player, $page);
            }

            return $transaction->discard();
        });

        $menu->send($player);
    }
}