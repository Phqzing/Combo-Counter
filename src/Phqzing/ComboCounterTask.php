<?php

namespace Phqzing;

use pocketmine\scheduler\Task;
use pocketmine\Player;

class ComboCounterTask extends Task {
  
 private $plugin;
  
  public function __construct(ComboCounter $plugin){
    $this->plugin = $plugin;
  }
  
  public function onRun(int $tick){
  
    foreach($this->plugin->getServer()->getOnlinePlayers() as $players){
      if(isset($this->plugin->combo[$players->getName()])){
        $counter = $this->plugin->getConfig()->get("counter");
        $counter = str_replace("{combo}", $this->plugin->combo[$players->getName()], $counter);
        $players->sendTip($counter);
      }
    }
  }
}
