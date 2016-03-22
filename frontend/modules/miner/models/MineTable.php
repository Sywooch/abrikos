<?php
/**
 * Created by PhpStorm.
 * User: abrikos
 * Date: 18.02.15
 * Time: 19:50
 */

namespace app\modules\miner\models;


class MineTable {
	private $cellEmpty = 'Empty';
	private $cellMine = 'Mine';
	private $cellInfo = 'Info';
	private $cellHidden = 'Hidden';

	public $rows;
	public $cols;
	public $mines;
	private $minesArray = [];
	private $cells;
	private $hiddencells = 0;


	public function getOpen($row,$col){
		$cell = $this->findOne($row,$col);
		$this->crowler($cell);
		return $this->getOpenedCells();
	}

	public function isFinished(){
		return !($this->hiddencells - $this->mines);
	}

	private function crowler($cell){
		$this->cellOpen($cell);
		if($cell->status === $this->cellInfo ){ return 0; }
		//error_log(" ".var_export($cell,1)."\n",3,'minerGame.txt');
		for($r=-1;$r<2;$r++){ for($c=-1;$c<2;$c++){
			if(($cell->row + $r>=0 && $cell->row + $r<=$this->rows-1) && ($cell->col + $c>=0 && $cell->col + $c<=$this->cols-1)) {
				$cellTest = $this->findOne($cell->row + $r, $cell->col + $c);
				if ($cellTest) {
					//error_log("CELLTEST: $r , $c :" . var_export($cellTest, 1) . "\n", 3, 'minerGame.txt');
					if ($this->cellCanCrowl($cellTest)) {
						$this->crowler($cellTest);
					} else {
						$this->cellOpen($cellTest);
					}
				}
			}
		}}
	}

	private function cellOpen($cell){
		if($cell->status != $this->cellMine){
			$cell->hidden = false;
		}
	}

	private function getOpenedCells(){
		$cells = [];
		$this->hiddencells =0;
		foreach($this->cells as $row){
			foreach($row as $cell){
				if(!$cell->hidden){
					$cells[] = $cell;
				}else{
					$this->hiddencells++;
				}
			}
		}
		return $cells;
	}


	private function cellCanCrowl($cell){
		return ( $cell->hidden && ($cell->status === $this->cellEmpty) &&  ($cell->status != $this->cellMine) && ($cell->status != $this->cellInfo));
	}

	private function cellIsHidden($cell){
		return $cell->hidden;
	}

	public function isMine($row,$col){
		$cell = $this->findOne($row,$col);
		return $cell->status === $this->cellMine;
	}

	public function getMines(){
		foreach($this->cells as $row){
			foreach($row as $cell){
				if($this->isCellMine($cell)) {
					$this->minesArray[] = $cell;
				}
			}
		}
		return $this->minesArray;
	}

	public function __construct($rows, $cols,$mines,$startRow,$startCol){
		$this->rows = $rows;
		$this->cols = $cols;
		$this->mines = $mines;
		$this->randomMines($this->fillTable(),$startRow,$startCol);
		$this->calcNearby();
	}

	public function findOne($row, $col){
		return $this->cells[$row][$col];
	}

	public function getCells(){
		return $this->cells;
	}


	private function isCellMine($cell){
		return $cell->status === $this->cellMine;
	}

	private function fillTable(){
		$cellsTmp = [];
		for($row=0;$row<$this->rows;$row++){
			for($col=0;$col<$this->cols;$col++){
				$cell = new \stdClass();
				$cell->status = $this->cellEmpty;
				$cell->count ='';
				$cell->row = $row;
				$cell->col = $col;
				$cell->hidden = true;
				$this->cells[$row][$col] = $cell;
				$cellsTmp[$row][$col] = 1;
			}
		}
		return $cellsTmp;
	}

	private function randomMines($cellsTmp,$startRow,$startCol){
		$i=0;
		unset($cellsTmp[$startRow][$startCol]);
		while($i<$this->mines){
			$row = array_rand($cellsTmp);
			if(count($cellsTmp[$row])<3){ unset($cellsTmp[$row]); continue; }
			$col = array_rand($cellsTmp[$row]);
			$mine = $this->findOne($row, $col);
			//error_log( "$i = $row, $col " . count($cellsTmp) . ', ' . count($cellsTmp[$row]) . "\n" ,3,'zzzz');
			$mine->status = $this->cellMine;
			unset($cellsTmp[$row][$col]);
			$i++;
		}
	}


	private function calcNearby(){
		for($row=0;$row<$this->rows;$row++){
			for($col=0;$col<$this->cols;$col++){
				$nearestMines = 0;
				for($r=-1;$r<2;$r++){ for($c=-1;$c<2;$c++){
					if(($row + $r>=0 && $row + $r<=$this->rows-1) && ($col + $c>=0 && $col + $c<=$this->cols-1)) {
						$cellCheck = $this->findOne($row + $r, $col + $c);
						if ($this->isCellMine($cellCheck)) {
							$nearestMines++;
						}
					}
				}}
				if($nearestMines>0){
					$cell = $this->findOne($row,$col);
					if($cell->status != $this->cellMine){
						$cell->status = $this->cellInfo;
						$cell->count = $nearestMines;
					}
				}
			}
		}
	}
}