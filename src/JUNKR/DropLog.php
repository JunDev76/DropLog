<?php

/*
    █████\ ██\   ██\ ██\   ██\       ███████\  ██\      ██\   ██\  ██████\  ██████\ ██\   ██\
   \__██ |██ |  ██ |███\  ██ |      ██  __██\ ██ |     ██ |  ██ |██  __██\ \_██  _|███\  ██ |
      ██ |██ |  ██ |████\ ██ |      ██ |  ██ |██ |     ██ |  ██ |██ /  \__|  ██ |  ████\ ██ |
      ██ |██ |  ██ |██ ██\██ |      ███████  |██ |     ██ |  ██ |██ |████\   ██ |  ██ ██\██ |
██\   ██ |██ |  ██ |██ \████ |      ██  ____/ ██ |     ██ |  ██ |██ |\_██ |  ██ |  ██ \████ |
██ |  ██ |██ |  ██ |██ |\███ |      ██ |      ██ |     ██ |  ██ |██ |  ██ |  ██ |  ██ |\███ |
\██████  |\██████  |██ | \██ |      ██ |      ████████\\██████  |\██████  |██████\ ██ | \██ |
 \______/  \______/ \__|  \__|      \__|      \________|\______/  \______/ \______|\__|  \__|

github.com/Jun-KR

Copyright 2021. JUN. Allrights reserved.
 */

namespace JUNKR;

use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;

class DropLog extends PluginBase implements Listener{

    private $data;

    public function onEnable(){
        $this->saveResource("settings.yml");

        $this->data = new Config($this->getDataFolder() . "settings.yml", Config::YAML);
        $this->data = $this->data->getAll();

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onDrop(PlayerDropItemEvent $ev){
        if($ev->isCancelled()){
            return;
        }
        if($this->data['op-only']){
            if(!$ev->getPlayer()->isOp()){
                return;
            }
        }

        $message = "§b§l[DropLog] §r§f" . ($ev->getPlayer()->isOp() ? " §cOP" : "§bUSER") . " §f{$ev->getPlayer()->getName()} dropped {$ev->getItem()->getName()}, {$ev->getItem()->getCount()} | ({$ev->getItem()->getId()}:{$ev->getItem()->getDamage()})";

        $this->getServer()->getLogger()->info($message);

        if($this->data['sendmessage-op']){
            foreach($this->getServer()->getOnlinePlayers() as $player){
                if($player->isOp()){
                    $player->sendMessage($message);
                }
            }
        }
    }
}