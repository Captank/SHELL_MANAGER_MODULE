<?php

/**
 * Author:
 *  - Captank (RK2)
 *
 * @Instance
 *
 */
class ShellManagerController {

	/**
	 * Name of the module.
	 * Set automatically by module loader.
	 */
	public $moduleName;

	/** @Inject */
	public $chatBot;

	private $listfile = "list.pid";
	private $name;
	private $cfg;
	private $pid;
	
	/**
	 * @Setup
	 */
	public function setup() {
		$this->name = $this->chatBot->vars["name"];
		$this->cfg = $_SERVER["argv"][1];
		$this->pid = getmypid();
		
		$this->update(null);
	}
	

	/**
	 * @Event("10mins")
	 * @Description("updates the listfile")
	 */
	public function update($eventObj){
		$data = $this->readBotData();
		$this->saveBotData($data);
	}
	
	/*
	 * parses the list.pid file in <budabot>/list.pid
	 */
	private function readBotData(){
		$result = Array();
		if(file_exists($this->listfile)){
			$content = file_get_contents($this->listfile);
			$content = explode("\n",$content);
			foreach($content as $line){
				$data = explode(":",$line);
				if(count($data)==3 && $this->isActiveBot($data[0],$data[1],$data[2])){
					$result[$data[0]] = Array("cfg"=>$data[1], "pid"=>$data[2]);
				}
			}
		}
		$result[$this->name] = Array('cfg'=>$this->cfg,'pid'=>$this->pid);
		return $result;
	}

	/*
	 * checks if its a valid and running bot
	 */
	private function isActiveBot($name,$cfg,$pid){		
		if(!file_exists($d["cfg"])){
			return false;
		}
		else{
			if(!isWindows()){
				$x = exec($y="./smgr.sh --module $name $pid $cfg ".str_replace(".","\\\\.",$cfg));
				var_dump($x,$y);
				return $x=="1";
			}
		}
	}
	
	/*
	 * saves the data to <budabot>/list.pid
	 */
	private function saveBotData(&$data){
		$out = "";
		foreach($data as $name => $d){
			$out.=$name.":".$d['cfg'].":".$d['pid']."\n";
		}
		file_put_contents($this->listfile,$out);
	}
}

?>
