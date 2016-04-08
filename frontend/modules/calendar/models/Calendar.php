<?php
/**
 *
 * @property string $old
 * @property integer $year
 * @property integer $month
 * @property integer $day
 * @property boolean $leap

 */

namespace app\modules\calendar\models;


use DateTime;
use Yii;

class Calendar
{
	const YEAR = 1970;
	const DIGITAL_FORMAT = 1;
	const RUSSIAN_FORMAT = 2;
	public $year;
	public $yearDay;
	public $day;
	public $month;
	public $retro;
	public $leap;
	const MONTH = [
		'',
		'Alpha',
		'Beta',
		'Gamma',
		'Delta',
		'Epsilon',
		'Dzeta',
		'Ita',
		'Teta',
		'Yota',
		'Kappa',
		'Lyambda',
		'Mu',
		'Ni',
		'YearOver',
		'LeapDay'
];
	private $startYearTime;
	private $yearSecond;
	private $yearHour;
	private $yearWeek;
	private $monthDay;
	private $monthName;
	private $weekDay;

	public function getEpoch(){
		return date('Y-m-d H:i',0);
	}

	public function __construct($retro='')
	{
		$time = strtotime($retro);
		if (!$time ){ $time = time(); }
		$this->year = date('Y',$time) - self::YEAR;
		//echo $this->year; exit;
		$this->startYearTime = strtotime(date('Y-01-01',$time));
		$this->leap = $this->isLeap();
		$this->yearSecond = $time - $this->startYearTime;
		$this->yearHour = round($this->yearSecond/3600);
		$this->yearDay = round($this->yearHour/24)+1;
		$this->yearWeek = ceil($this->yearDay/7);
		$this->day = $this->monthDay = $this->getMonthDay();
		$this->month = ceil($this->yearWeek/4);
		$this->monthName = $this->getMonthName();
		$this->retro = date('Y-m-d',$time);
		return $this;
	}

	private function getMonthDay(){
		if($this->yearDay<365){
			return $this->yearDay%28 ? $this->yearDay%28 : 28;
		}else{
			return $this->yearDay - 364 + 28;
		}
	}

	public function formatDate($type=0){
		$year = str_pad($this->year, 4, "0", STR_PAD_LEFT);
		switch ($type){
			case self::DIGITAL_FORMAT:
				return $year . '-'. ($this->month>9?$this->month:('0'.$this->month))  .'-'. ($this->monthDay>9?$this->monthDay:('0'.$this->monthDay));
				break;

			case self::RUSSIAN_FORMAT:
				return ($this->yearDay<365 ? $this->monthDay : '') . ' ' . $this->getMonthName() . ' ' . $year;
				break;
			default:
				return $year . ' ' . $this->getMonthName() .  ($this->yearDay<365 ? ' ' .$this->monthDay : '');
		}

	}

	public function getMonthName()
	{
		if($this->yearDay<365)
			return Yii::t('calendar', self::MONTH[$this->month]);
		else
			return Yii::t('calendar', self::MONTH[$this->month + $this->yearDay - 364]);

	}

	public function isLeap()
	{
		$year = ($this->year +2);
		$leap = 0;
		if(!($year % 4)) $leap = 1;
		if(!(($year - 32) % 100))	$leap = 0;
		if(!(($year - 32) % 400)) $leap =1;

		return $leap;
	}

	public function yearFromretroToNew($retro)
	{
		return $retro - self::YEAR;
	}

	public function getYearText(){
		if($this->year>0):
			return Yii::t('calendar', '{0} year from UNIX epoch', $this->year);
		else:
			return Yii::t('calendar', '{0} year before UNIX epoch', abs($this->year));
		endif;
	}

	public function getRetro($year,$day)
	{
		$date = DateTime::createFromFormat('z Y', strval($day-1) . ' ' . strval(self::YEAR + $year));
		return ($date->format('d F Y'));
	}

	public function getModern($year,$day)
	{
		$this->yearDay = $day;
		$this->year = $year;
		return ($this->formatDate(2));
	}

}