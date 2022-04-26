<?php

namespace AxelFeL\CmdCooldown;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class Main extends PluginBase implements Listener {
    
    public static $players = [];
    
    public function onEnable() : void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
    
    public function onJoin(PlayerJoinEvent $event){
        self::$players[$event->getPlayer()->getName()] = time();
    }
    
    public function onQuit(PlayerQuitEvent $event): void
    {
        if (isset(self::$players[$event->getPlayer()->getName()])) unset(self::$players[$event->getPlayer()->getName()]);
    }
    
    public function onCommandPre(PlayerCommandPreprocessEvent $event){
        $player = $event->getPlayer();
        $str = str_split($event->getMessage());
        if ($str[0] == "/" or $str[0] == "./")
        {
            if (time() >= self::$players[$player->getName()])
            {
                self::$players[$player->getName()] = time() + 4;
            }else{
                $player->sendMessage("§cYou need to wait §6" . self::$players[$player->getName()] - time() . " §cseconds to use command again.");
                $event->cancel(\true);
                return false;
            }
        }
    }
}
