<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Absence;
use App\Models\AbsenceType;
use DateTime;
use DateInterval;
use DatePeriod;

class BasicAbsenceController extends Controller
{
    /* Računa trenutan staz u tvrtki */	               
	public static function yearsServiceCompany($user)
	{
		$today = new DateTime('now');    				/* današnji dan */
		$years_service = 0;								/* godine staža */
		$date_register = new DateTime($user->reg_date);  /* datum prijave - registracija */
		if($date_register) {
			$years_service = $date_register->diff($today);  	/* staz u firmi  */
		}

		return $years_service;
	}
	
	/* Računa ukupan staž */	
	public static function yearsServiceAll($user)  
	{
		// Staž u firmi
		$years_service_company = BasicAbsenceController::yearsServiceCompany($user);
		$years = $years_service_company->format('%y');  
		$months = $years_service_company->format('%m');
		$days = $years_service_company->format('%d');
		// Staž prošli 
		$serviceY = 0;
		$serviceM = 0;
		$serviceD = 0;
		if($user->years_service) {
			$stazPrijasnji = $user->years_service;
			$stazPrijasnji = explode('-',$user->years_service);
			$serviceY = $stazPrijasnji[0];
			$serviceM = $stazPrijasnji[1];
			$serviceD = $stazPrijasnji[2];
		} 
		/* Staž ukupan */
		$all_days = $days+$serviceD;
		$all_month = $months+$serviceM;
		$all_years = $years + $serviceY;
		
		if( $all_days >= 30 ) {
			$all_days -= 30;
			$all_month += 1;
		} 
		
		if( $all_month >= 12 ){
			$all_month -= 12;
			$all_years += 1;
		} 
	
		$staz = array($all_years, $all_month, $all_days);
		
		return $staz;
	}
	
	// Vraća broj dana godišnjeg ova godina            
	public static function daysThisYear($user)
	{
		$all_service = BasicAbsenceController::yearsServiceAll($user);  /* ukupan staž  */
		
		
		/* Godišnji odmor - dani*/
		$days = AbsenceType::where('mark','GO')->first()->min_days;
		$_max_days = AbsenceType::where('mark','GO')->first()->max_days;
		
		if(! $days) {
			$days = 20;
		}

		$days += (int)($all_service[0]/ 4) ;
		
		If($days > 25){
			if($_max_days) {
				$days = $_max_days;
			} else {
				$days = 25;
			}
		}
		
		return $days;
	}
	
	/*  računa razmjeran GO */
	public static function razmjeranGO($user)   
	{
		$date = new DateTime('now');    /* današnji dan */
		$ova_godina = date_format($date,'Y');
		$ovaj_mjesec = date_format($date,'m');
		$ovaj_dan = date_format($date,'d');
		
		if($ovaj_dan < 15){
			$ovaj_mjesec -=1;
		} 
		
		$GO  = BasicAbsenceController::daysThisYear($user);

		if($user->reg_date) {
			$datum_prijave = $user->reg_date;
			$datum_prijave = explode('-',$user->reg_date);
			
			$prijavaGodina = $datum_prijave[0];
			$prijava = new DateTime($user->reg_date);
			$staz = $prijava->diff($date);   /* staz u Duplicu*/
			$mjesec = $staz->format('%m');
			$dan = $staz->format('%d');
			if($dan >= 15){
				$mjesec +=1;
			}
			if($prijavaGodina < $ova_godina){
				$razmjeranGO = round($GO/12 * $ovaj_mjesec, 0, PHP_ROUND_HALF_UP);
			} else {
				if($user->prekidStaza == 'DA' || $user->prvoZaposlenje == 'DA'){
					if($mjesec >= 6){
						$razmjeranGO = $GO;
					} else {
						$razmjeranGO = round($GO/12 * $mjesec, 0, PHP_ROUND_HALF_UP);
					}
				} else {
					$razmjeranGO = round($GO/12 * $mjesec, 0, PHP_ROUND_HALF_UP);
				}
			}
			
		} else {
			$razmjeranGO = 0;
		}
		
		if($razmjeranGO > 25){
			$razmjeranGO = 25;
		}
		if($razmjeranGO > $GO){
			$razmjeranGO = $GO;
		}
			
		return $razmjeranGO;
	}
	
	/* Računa staz u Duplicu za prošlu godinu - do 31.12. */
	public static function yearsCompany_PG($user)      /************ RADI!!!!!!! ***************/
	{
		$date = new DateTime('now');    /* današnji dan */
		$prosla_godina = date_format($date,'Y')-1;
		
		$datePG = new DateTime($prosla_godina . '-12-31');
		
		$stazPG = 0;
		$datum_prijave = new DateTime($user->reg_date);  /* datum prijave - registracija */
		if( date_format($datum_prijave,'Y') <= $prosla_godina) {
			$stazPG = $datum_prijave->diff($datePG);  /* staz u Duplicu PG*/
		}

		return $stazPG;
	}
	
	/* Računa ukupan staž za prošlu godinu */	
	public static function stazUkupnoPG($user)   
	{
		$stazPG = BasicAbsenceController::yearsCompany_PG($user);
		
		$godina = 0;
		$mjeseci = 0;
		$dana = 0;
		$stazY = 0;
		$stazM = 0;
		$stazD = 0;
		$danaUk=0;
		$mjeseciUk=0;
		$godinaUk=0;
		
		if($stazPG){
			$godina = $stazPG->format('%y');  
			$mjeseci = $stazPG->format('%m');
			$dana = $stazPG->format('%d');
		}

		if( $user->years_service) {
			$stazPrijasnji = explode('-',$user->years_service);
			$stazY = $stazPrijasnji[0];
			$stazM = $stazPrijasnji[1];
			$stazD = $stazPrijasnji[2];
		} 
		/* Staž ukupan */
		if(($dana+$stazD) > 30){
			$danaUk = ($dana+$stazD) -30;
			$mjeseciUk = 1;
		} else {
			$danaUk = ($dana+$stazD);
		}
		if(($mjeseci+$stazM) > 12){
			$mjeseciUk += ($mjeseci+$stazM) -12;
			$godinaUk = 1;
		}else {
			$mjeseciUk += ($mjeseci+$stazM);
		}
		$godinaUk += ($godina + $stazY);
					
		$stazPG = array($godinaUk,$mjeseciUk,$danaUk);

		return $stazPG;
	}


	/* dani GO PROŠLA godina */
	public static function godisnjiPG($user)      /************ RADI!!!!!!! ***************/
	{
		/* Računa ukupan staz za prošlu godinu - do 31.12.*/
		$stazPG =  BasicAbsenceController::stazUkupnoPG($user);

		$dana = $stazPG[2];
		$mjeseci = $stazPG[1];
		$godina = $stazPG[0];
		
		if(($dana) > 30){
			$dana = $dana -30;
			$mjeseci += 1;
		}
		
		if(($mjeseci) > 12){
			$mjeseci += $mjeseci - 12;
			$godina += 1;
		}
		
		/* Godišnji odmor - dani*/
		$days = AbsenceType::where('mark','GO')->first()->min_days;
		$_max_days = AbsenceType::where('mark','GO')->first()->max_days;
		
		if(! $days) {
			$days = 20;
		}
		$days += (int)($godina/ 4) ;
		
		If($days > 25){
			if($_max_days) {
				$days = $_max_days;
			} else {
				$days = 25;
			}
		}
	
		return $days;
	}

	/*  razmjeran GO PROŠLA godina*/
	public static function razmjeranGO_PG($user)    /************ RADI!!!!!!! ***************/
	{
		$date = new DateTime('now');    /* današnji dan */
		$ova_godina = date_format($date,'Y');
		$mjesec_danas = date_format($date,'m');
		$prosla_godina = date_format($date,'Y')-1;
		$datumPG = new DateTime($prosla_godina . '-12-31');
		$razmjeranGO_PG = 0;
		
		$GO  = BasicAbsenceController::godisnjiPG($user); /*dani GO prošla godina */

		if($user->reg_date) {
			$datum_prijave = $user->reg_date;
			$datum_prijave = explode('-', $user->reg_date);
			$prijavaGodina = $datum_prijave[0];
			$prijava = new DateTime($user->reg_date);
			$staz = $prijava->diff($datumPG);   /* staz u Duplicu do 31.12. prošla godina*/
			$mjesec = $staz->format('%m');
			$dan = $staz->format('%d');

			if($prijavaGodina < $prosla_godina){
				$razmjeranGO_PG = $GO; 
			}  elseif ($prijavaGodina == $prosla_godina) {
				if($dan >= 15){
					$mjesec +=1;
				}
				if($user->prekidStaza == 'DA' || $user->prvoZaposlenje == 'DA'){
					if($mjesec >= 6){
						$razmjeranGO_PG = $GO;
					} else {
						$razmjeranGO_PG = round($GO/12 * $mjesec, 0, PHP_ROUND_HALF_UP);
					}
				} else {
					$razmjeranGO_PG = round($GO/12 * $mjesec, 0, PHP_ROUND_HALF_UP);
				}
			
			} elseif ($prijavaGodina ==  $ova_godina) {
				$razmjeranGO_PG = 0;
			}
		}
		
		return $razmjeranGO_PG;
	}
	
	public static function zahtjevi ($user) 
	{
		$datum = new DateTime('now');    /* današnji dan */
		$ova_godina = date_format($datum,'Y');
		$prosla_godina = date_format($datum,'Y') - 1;
		$mjesec_danas = date_format($datum,'m');
		
		$GO_razmjeran = BasicAbsenceController::razmjeranGO($user); // razmjerni dani ova godina
		$GO_PG = BasicAbsenceController::razmjeranGO_PG($user); // razmjerni dani prošla godina
		$zahtjevi = Absence::where('employee_id',$user->id)->where('approve',1)->get();
		
		$preostalo_PG = $GO_PG;
		$preostalo_OG = $GO_razmjeran;
		$zahtjevi_Dani_OG = 0;
		$zahtjevi_Dani_PG = 0;
	
		foreach($zahtjevi as $zahtjev){
			$begin = new DateTime($zahtjev->start_date);
			$end = new DateTime($zahtjev->end_date);
			$end->setTime(0,0,1);
			$interval = DateInterval::createFromDateString('1 day');
			$period = new DatePeriod($begin, $interval, $end);
			foreach ($period as $dan) {
				if(date_format($dan,'N') < 6 ){
					if(date_format($dan,'d') == '01' && date_format($dan,'m') == '01' ||
						date_format($dan,'d') == '06' && date_format($dan,'m') == '01' ||
						date_format($dan,'d') == '01' && date_format($dan,'m') == '05' ||
						date_format($dan,'d') == '22' && date_format($dan,'m') == '06' ||
						date_format($dan,'d') == '25' && date_format($dan,'m') == '06' ||
						date_format($dan,'d') == '15' && date_format($dan,'m') == '08' ||
						date_format($dan,'d') == '05' && date_format($dan,'m') == '08' ||
						date_format($dan,'d') == '08' && date_format($dan,'m') == '10' ||
						date_format($dan,'d') == '01' && date_format($dan,'m') == '11' ||
						date_format($dan,'d') == '25' && date_format($dan,'m') == '12' ||
						date_format($dan,'d') == '26' && date_format($dan,'m') == '12' ||
						date_format($dan,'d') == '02' & date_format($dan,'m') == '04' & date_format($dan,'Y') == '2018' ||
						date_format($dan,'d') == '31' & date_format($dan,'m') == '05' & date_format($dan,'Y') == '2018' ||
						date_format($dan,'d') == '22' & date_format($dan,'m') == '04' & date_format($dan,'Y') == '2019' ||
						date_format($dan,'d') == '20' & date_format($dan,'m') == '06' & date_format($dan,'Y') == '2019' ||
						date_format($dan,'d') == '13' & date_format($dan,'m') == '04' & date_format($dan,'Y') == '2020' ||
						date_format($dan,'d') == '11' & date_format($dan,'m') == '06' & date_format($dan,'Y') == '2020'){
							//
					} else {
							if($preostalo_PG > 0) {
								if(date_format($dan,'Y') == $prosla_godina) {
									$preostalo_PG -= 1;
									$zahtjevi_Dani_PG += 1;
								} elseif(date_format($dan,'m') < '07' && date_format($dan,'Y') == $ova_godina) {
									$preostalo_PG -= 1;
									$zahtjevi_Dani_PG += 1;
								} else {
									$preostalo_OG -= 1;
									$zahtjevi_Dani_OG += 1;
								}
							} else {
								$preostalo_OG -= 1;
								$zahtjevi_Dani_OG += 1;
							}
					}
				}
			}	
		}
		if ($mjesec_danas >= 7 ) {
			$preostalo_PG = 0;
		}
		
		$zahtjevi = array(
			'preostalo_PG' => $preostalo_PG,
			'preostalo_OG' => $preostalo_OG, 
			'preostalo_ukupno' => $preostalo_OG + $preostalo_PG, 
			'zahtjevi_Dani_PG' => $zahtjevi_Dani_PG, 
			'zahtjevi_Dani_OG' => $zahtjevi_Dani_OG,
		);
		
		return $zahtjevi;
	}

	//računa iskorištene dane godišnjeg odmora ova godina  /************ RADI!!!!!!! ***************/
	public static function daniZahtjevi($user)
	{
		/* Zahtjevi ova godina */	
		$zahtjevi = Absence::where('employee_id',$user->employee->id)->where('approve','1')->get();
		$datum = new DateTime('now');    /* današnji dan */
		$ova_godina = date_format($datum,'Y');
		
		/* ukupno iskorišteno godišnji zaposlenika*/
		$ukupnoGO = 0;
		
		foreach($zahtjevi as $zahtjev){
			if($zahtjev->absence['mark'] == 'GO') {
				$begin = new DateTime($zahtjev->start_date);
				$end = new DateTime($zahtjev->end_date);
				$end->setTime(0,0,1);
				$interval = DateInterval::createFromDateString('1 day');
				$period = new DatePeriod($begin, $interval, $end);
				foreach ($period as $dan) {
					if(date_format($dan,'N') < 6 ){
						if(date_format($dan,'Y') == $ova_godina ){
							if(date_format($dan,'d') == '01' && date_format($dan,'m') == '01' ||
								date_format($dan,'d') == '06' && date_format($dan,'m') == '01' ||
								date_format($dan,'d') == '01' && date_format($dan,'m') == '05' ||
								date_format($dan,'d') == '22' && date_format($dan,'m') == '06' ||
								date_format($dan,'d') == '25' && date_format($dan,'m') == '06' ||
								date_format($dan,'d') == '15' && date_format($dan,'m') == '08' ||
								date_format($dan,'d') == '05' && date_format($dan,'m') == '08' ||
								date_format($dan,'d') == '08' && date_format($dan,'m') == '10' ||
								date_format($dan,'d') == '01' && date_format($dan,'m') == '11' ||
								date_format($dan,'d') == '25' && date_format($dan,'m') == '12' ||
								date_format($dan,'d') == '26' && date_format($dan,'m') == '12' ||
								date_format($dan,'d') == '02' && date_format($dan,'m') == '04' && date_format($dan,'Y') == '2018' ||
								date_format($dan,'d') == '31' && date_format($dan,'m') == '05' && date_format($dan,'Y') == '2018' ||
								date_format($dan,'d') == '22' && date_format($dan,'m') == '04' && date_format($dan,'Y') == '2019' ||
								date_format($dan,'d') == '20' && date_format($dan,'m') == '06' && date_format($dan,'Y') == '2019' ||
								date_format($dan,'d') == '13' && date_format($dan,'m') == '04' && date_format($dan,'Y') == '2020' ||
								date_format($dan,'d') == '11' && date_format($dan,'m') == '06' && date_format($dan,'Y') == '2020'){
									//
							} else {
								$ukupnoGO += 1;
							}
						}
					}
				}	
			}
		}
		return $ukupnoGO;
	}

	// Računa broj radnih dana između dva datuma
	public static function daniGO($zahtjev)
	{
		$begin = new DateTime($zahtjev['start_date']);
		$end = new DateTime($zahtjev['end_date']);
		$end->setTime(0,0,1);
		$interval = DateInterval::createFromDateString('1 day');
		$period = new DatePeriod($begin, $interval, $end);
		
		$brojDana = 0;
		
		foreach ($period as $dan) {
			if( date_format($dan,'N') < 6 &&
				date_format($dan,'d-m') != '01-01' &&
				date_format($dan,'d-m') != '06-01' &&
				date_format($dan,'d-m') != '01-05' && 
				date_format($dan,'d-m') != '22-06' &&
				date_format($dan,'d-m') != '25-06' && 
				date_format($dan,'d-m') != '15-08' && 
				date_format($dan,'d-m') != '05-08' && 
				date_format($dan,'d-m') != '08-10' && 
				date_format($dan,'d-m') != '01-11' && 
				date_format($dan,'d-m') != '25-12' &&
				date_format($dan,'d-m') != '26-12' &&
				date_format($dan,'d-m-Y') != '02-04-2018' &&
				date_format($dan,'d-m-Y') != '31-05-2018' &&
				date_format($dan,'d-m-Y') != '22-04-2019' && 
				date_format($dan,'d-m-Y') != '20-06-2019' &&
				date_format($dan,'d-m-Y') != '13-04-2020' &&
				date_format($dan,'d-m-Y') != '11-06-2020' )
			{
				$brojDana += 1;
			}
		}
		return $brojDana;
	}

	public static function bolovanje($user)
	{
		$datum = new DateTime('now');    /* današnji dan */
		$ova_godina = date_format($datum,'Y');
		$prosla_godina = date_format($datum,'Y') - 1;
		$mjesec_danas = date_format($datum,'m');
		$zahtjevi = Absence::where('employee_id',$user->id)->where('approve',1)->get();
		
		$bolovanje_OG = 0;
		$bolovanje_PG = 0;
		$bolovanje_OM = 0;
		
		foreach($zahtjevi as $zahtjev){
			if($zahtjev->absence['mark'] == 'BOL') {
				$begin = new DateTime($zahtjev->start_date);
				$end = new DateTime($zahtjev->end_date);
				$end->setTime(0,0,1);
				$interval = DateInterval::createFromDateString('1 day');
				$period = new DatePeriod($begin, $interval, $end);
				foreach ($period as $dan) {
					if(date_format($dan,'N') < 6 ){
						if(date_format($dan,'d') == '01' && date_format($dan,'m') == '01' ||
						date_format($dan,'d') == '06' && date_format($dan,'m') == '01' ||
						date_format($dan,'d') == '01' && date_format($dan,'m') == '05' ||
						date_format($dan,'d') == '22' && date_format($dan,'m') == '06' ||
						date_format($dan,'d') == '25' && date_format($dan,'m') == '06' ||
						date_format($dan,'d') == '15' && date_format($dan,'m') == '08' ||
						date_format($dan,'d') == '05' && date_format($dan,'m') == '08' ||
						date_format($dan,'d') == '08' && date_format($dan,'m') == '10' ||
						date_format($dan,'d') == '01' && date_format($dan,'m') == '11' ||
						date_format($dan,'d') == '25' && date_format($dan,'m') == '12' ||
						date_format($dan,'d') == '26' && date_format($dan,'m') == '12' ||
						date_format($dan,'d') == '02' && date_format($dan,'m') == '04' && date_format($dan,'Y') == '2018' ||
						date_format($dan,'d') == '31' && date_format($dan,'m') == '05' && date_format($dan,'Y') == '2018' ||
						date_format($dan,'d') == '22' && date_format($dan,'m') == '04' && date_format($dan,'Y') == '2019' ||
						date_format($dan,'d') == '20' && date_format($dan,'m') == '06' && date_format($dan,'Y') == '2019' ||
						date_format($dan,'d') == '13' && date_format($dan,'m') == '04' && date_format($dan,'Y') == '2020' ||
						date_format($dan,'d') == '11' && date_format($dan,'m') == '06' && date_format($dan,'Y') == '2020'){
							//
						} else {
							if(date_format($dan,'Y') == $prosla_godina) {	
								$bolovanje_PG += 1;
							} elseif(date_format($dan,'Y') == $ova_godina) {
								$bolovanje_OG += 1;
							} 
							if(date_format($dan,'m') == $mjesec_danas && date_format($dan,'Y') == $ova_godina) {
								$bolovanje_OM += 1;
							}
						}
					}
				}
			}
		}

		$bolovanje = array(
			'bolovanje_PG' => $bolovanje_PG,
			'bolovanje_OG' => $bolovanje_OG, 
			'bolovanje_OM' => $bolovanje_OM
		);

		return $bolovanje;
	}

}