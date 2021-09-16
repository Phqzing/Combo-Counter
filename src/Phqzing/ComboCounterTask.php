<?php

namespace Phqzing;

use pocketmine\scheduler\Task;
use pocketmine\Server;

class ComboCounterTask extends Task {
  
  public function onRun(int $tick){
  
    foreach(Server::getInstance()->getOnlinePlayers() as $players){
      if(isset($this->plugin->combo[$players->getName()])){
        $counter = ComboCounter::getInstance()->getConfig()->get("counter");
        $counter = str_replace("{combo}", $this->plugin->combo[$players->getName()], $counter);
        $players->sendTip($counter);
      }
    }
  }
}
