<?php

namespace Phqzing;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as TE;
use pocketmine\Player;
use pocketmine\event\entity\{EntityDamageByEntityEvent, EntityDamageEvent};
use pocketmine\event\player\{PlayerJoinEvent, PlayerQuitEvent};
use pocketmine\command\{CommandSender, Command};

class ComboCounter extends PluginBase implements Listener {
 
  public $combo = [];
  
  public function onEnable(){
    @mkdir($this->getDataFolder());
    $this->saveDefaultConfig();
    $this->getResource("config.yml");
    $this->getServer()->getPluginManager()->registerEvents($this, $this); 
    $this->getScheduler()->scheduleRepeatingTask(new ComboCounterTask($this), 1);
  }
  
  public function onDamage(EntityDamageEvent $ev){
    $player = $ev->getEntity();
    $cause = $player->getLastDamageCause();
    
    if($cause instanceof EntityDamageByEntityEvent){
      $damger = $cause->getDamager();
      if($damager instanceof Player and $player instanceof Player){
         if(isset($this->combo[$damager->getName()])){
           $this->combo[$damager->getName()]++;
         }
         if(isset($this->combo[$player->getName()])){
           $this->combo[$player->getName()] = 0; 
         }
      }
    }
  }
                               
  public function onJoim(PlayerJoinEvent $ev){
    $player = $ev->getPlayer();
    
    if($this->getConfig()->get("on-by-default") == "true"){
      $this->combo[$player->getName()] = 0;
    }
  }
                               
  public function onQuit(PlayerQuitEvent $ev){
    $player = $ev->getPlayer();
    
    if(isset($this->combo[$player->getName()])){
      unset($this->combo[$player->getName()]); 
    }
  }
   
   public function onCommand(CommandSender $sender, Command $command, string $label, array $args):bool{
    switch($command->getName()){
     case "combocounter":
      if($sender instanceof Player){
       if(isset($this->combo[$sender->getName()])){
        unset($this->combo[$sender->getName()]);
        $sender->sendMessage(TE::RED."- Combo counter turned off");
       }else{
        $this->combo[$sender->getName()] = 0;
        $sender->sendMessage(TE::GREEN."Combo counter turned on");
       }
      }else{
       $sender->sendMessage("You can only use this command in game");
      }
      break;
    }
    return true;
   }
}
