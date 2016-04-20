<?php
/**
 * Created by PhpStorm.
 * User: abrikos
 * Date: 08.04.16
 * Time: 11:38
 */

namespace app\modules\seawar\models;

use yii\base\Object;

class SeaTable
{
	const SIZE=10;
	public $free, $ships, $cells;
	//const SHIPS = [4=>1, 3=>2, 2=>3, 1=>4];
	const SHIPS = [1=>4,2=>3,3=>2,4=>1];
	const ORIENTATION = [1,0];


	public function __construct()
	{
		for($y = 1; $y <= self::SIZE; $y++ ){
			for($x = 1; $x <= self::SIZE; $x++ ){
				$cell = new \stdClass();
				$cell->x = $x; $cell->y = $y;
				$cell->data = '0';
				$this->cells["$x-$y"] = $cell;
			}
		}
		$this->free[0] = $this->free[1] = $this->cells;
	}

	private function getCell($x,$y)
	{
		return $this->cells["$x-$y"];
	}

	public function randomFill()
	{
		foreach (self::SHIPS as $size=>$count){
			$this->setFreeCells($size);
			for($i=0;$i<$count;$i++){
				$ship = new \stdClass();
				$ship->size = $size;
				$ship->orientation = rand(0,1);
				//print '<plaintext>';print_r(array_rand($this->free[$ship->orientation],1));exit;
				if(!count($this->free[$ship->orientation])) $ship->orientation = !$ship->orientation;
				try{
					$cell = $this->free[$ship->orientation][array_rand($this->free[$ship->orientation])];
				}catch (\Exception $e){
					$this->ships = [];
					$this->free[0] = $this->free[1] = $this->cells;
					self::randomFill();
					return $e;
				}

				$ship->id = $cell->x . '-' . $cell->y;
				//$cell = $this->free[$ship->orientation]['9-9'];
				for($j=0;$j<$size;$j++) {
					if ($ship->orientation) {
						$shipCell = $this->getCell($cell->x + $j, $cell->y);
					} else {
						$shipCell =$this->getCell($cell->x, $cell->y + $j);
					}
					$shipCell->shipId = $ship->id;
					$shipCell->size = $size;
					$ship->cells[] = $this->ships[] = $shipCell;
				}
				//Удаляем свободных соседей
				foreach ($ship->cells as $cell){
					for($y = $cell->y - 1; $y<$cell->y+2; $y++) {
						for ($x = $cell->x - 1; $x < $cell->x + 2; $x++) {
							if($x<=self::SIZE && $y<=self::SIZE) {
								unset($this->free[1]["$x-$y"]);
								unset($this->free[0]["$x-$y"]);
							}
						}
					}
				}
				//Удаляем  для своего и следующего size
				foreach ($this->ships as $cell){
					for($ii = -1; $ii<2;$ii++) {
						unset($this->free[1][($cell->x - $size).'-'.($cell->y + $ii)]);
						unset($this->free[0][($cell->x + $ii ).'-'.($cell->y - $size)]);
						unset($this->free[1][($cell->x - $size + 1).'-'.($cell->y + $ii)]);
						unset($this->free[0][($cell->x + $ii ).'-'.($cell->y - $size + 1)]);

					}
				}
			}
			//Удаляем пропущеные
			foreach (self::SHIPS as $size=>$count){
				foreach ($this->ships as $cell){
					for($ii = -1; $ii<2;$ii++) {
						unset($this->free[1][($cell->x - $size - 1 ).'-'.($cell->y + $ii)]);
						unset($this->free[0][($cell->x + $ii ).'-'.($cell->y - $size - 1)]);
					}
				}
			}
		}
		return false;
	}


	public function setFreeCells($size)
	{
		foreach ($this->cells as $key => $cell){
			if($cell->x>self::SIZE - $size +1){
				unset($this->free[1][$key]) ;
				//$this->free[1][$key]->data = $cell->x .'>' . self::SIZE . '-' . $size .'-'. 1;
			}

			if($cell->y>self::SIZE - $size + 1)
				unset($this->free[0][$key])  ;

		}
	}

	private function isFree($cell)
	{

	}

	public function drawTable(){
		$letters = range('A', 'Z');
		print '<table class="seatable">';
		for($i=0;$i<11;$i++){
			print '<tr class="row">';
			for($j=0;$j<11;$j++){
				if($i && $j)
					print '<td class="cell x'.$j.' y'.$i.'" id="cell-'.$j.'-'.$i.'" x="'.$j.'" y="'.$i.'" shipSize="0" shipId=""></td>';
				elseif(!$i && $j)
					print '<td class="axis axis-x" id="cell-'.$j.'-'.$i.'">'.$letters[$j-1].'</td>';
				elseif(!$j && $i)
					print '<td class="axis axis-y" id="cell-'.$j.'-'.$i.'">'.$i.'</td>';
				else
					print '<td class="start axis"></td>';

			}
			print '</tr>';
		}
		print '</table>';
	}
}