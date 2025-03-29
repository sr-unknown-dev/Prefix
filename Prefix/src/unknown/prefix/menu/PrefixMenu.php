<?php

namespace unknown\prefix\menu;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;
use pocketmine\block\utils\DyeColor;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use unknown\prefix\Loader;
use unknown\prefix\Tag;

class PrefixMenu
{
    private const ITEMS_PER_PAGE = 28; // Reducido para ajustarse al nuevo diseño

    public static function open(Player $player, int $page = 1): void
    {
        $prefixManager = Loader::getInstance()->getPrefixManager();
        $tags = array_chunk(array_keys($prefixManager->tags), self::ITEMS_PER_PAGE);
        $totalPages = count($tags);

        if ($page < 1 || $page > $totalPages) {
            $page = 1;
        }

        $menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
        $menu->setName(TextFormat::GREEN . "Prefix Menu - Page $page/$totalPages");
        
        // Llenar el inventario con vidrio negro en las orillas
        $borderGlass = VanillaBlocks::STAINED_GLASS_PANE()->setColor(DyeColor::BLACK)->asItem()->setCustomName(" ");
        
        // Llenar la primera y última fila completamente
        for ($i = 0; $i < 9; $i++) {
            $menu->getInventory()->setItem($i, $borderGlass);
            $menu->getInventory()->setItem(45 + $i, $borderGlass);
        }
        
        // Llenar la primera y última columna de cada fila intermedia
        for ($row = 1; $row < 5; $row++) {
            $menu->getInventory()->setItem($row * 9, $borderGlass);
            $menu->getInventory()->setItem($row * 9 + 8, $borderGlass);
        }
        
        // Ahora colocamos los prefijos en el área central
        if (isset($tags[$page - 1])) {
            $tagIndex = 0;
            for ($row = 1; $row < 5; $row++) {
                for ($col = 1; $col < 8; $col++) {
                    if ($tagIndex < count($tags[$page - 1])) {
                        $tagName = $tags[$page - 1][$tagIndex];
                        $tagFormat = $prefixManager->tags[$tagName];
                        
                        $slot = $row * 9 + $col;
                        $item = VanillaItems::PAPER();
                        $item->setCustomName(TextFormat::colorize($tagFormat . " " . $tagName));
                        $item->getNamedTag()->setString("prefix", $tagName);
                        $menu->getInventory()->setItem($slot, $item);
                        
                        $tagIndex++;
                    }
                }
            }
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

        $menu->setListener(function (InvMenuTransaction $transaction) use ($prefixManager, $player, $menu): InvMenuTransactionResult {
            $itemClicked = $transaction->getItemClicked();
            $tagName = $itemClicked->getNamedTag()->getString("prefix", "");
            $page = $itemClicked->getNamedTag()->getInt("page", 0);

            if ($tagName !== "") {
                $permission = "prefix.use." . strtolower($tagName);
                
                if ($player->hasPermission($permission) || $player->hasPermission("prefix.use.*") || Server::getInstance()->isOp($player->getName())) {
                    $prefixManager->setTag($player, $tagName);
                    $tagFormat = $prefixManager->tags[$tagName] ?? "";
                    $player->sendMessage("Your prefix has been set to: " . TextFormat::colorize($tagFormat));
                    $menu->onClose($player);
                } else {
                    $player->sendMessage(TextFormat::RED . "You don't have permission to use this prefix!");
                }
            } elseif ($page > 0) {
                self::open($player, $page);
            }

            return $transaction->discard();
        });

        $menu->send($player);
    }
}