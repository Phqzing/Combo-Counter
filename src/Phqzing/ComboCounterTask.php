<?php

namespace Phqzing;

use pocketmine\scheduler\Task;
use pocketmine\Server;
use Phqzing\ComboCounter;

class ComboCounterTask extends Task {
  
  public function onRun(int $tick){
  
    foreach(Server::getInstance()->getOnlinePlayers() as $players){
      if(isset(ComboCounter::getInstance()->combo[$players->getName()])){
        $counter = ComboCounter::getInstance()->getConfig()->get("counter");
        $counter = str_replace("{combo}", ComboCounter::getInstance()->combo[$players->getName()], $counter);
        $players->sendTip($counter);
      }
    }
  }
}
