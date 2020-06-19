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
	/* ########################      STAŽ    ######################## */	
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
		
		/* Računa staž u Firmi za određenu godinu - do 31.12. */
		public static function yearsCompany_PG($user, $year)     
		{
			$date = new DateTime('now');    /* današnji dan */
			//	$prosla_godina = date_format($date,'Y')-1;
			
			$datePG = new DateTime($year . '-12-31');
			
			$stazPG = 0;
			$datum_prijave = new DateTime($user->reg_date);  /* datum prijave - registracija */
			if( date_format($datum_prijave,'Y') <= $year) {
				$stazPG = $datum_prijave->diff($datePG);  /* staz u Duplicu PG*/
			}

			return $stazPG;
		}
		
		/* Računa ukupan staž za određenu godinu */	
		public static function stazUkupnoPG($user, $year)   
		{
			$stazPG = BasicAbsenceController::yearsCompany_PG($user, $year);
			
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
	/* ########################      STAŽ kraj     ######################## */	

	/* ########################      DANI GODIŠENJEG ODMORA    ######################## */	

		// Vraća broj dana godišnjeg ova godina    
		public static function daysThisYear($user)
		{
			$all_service = BasicAbsenceController::yearsServiceAll($user);  /* ukupan staž  */
			$date = new DateTime('now');    /* današnji dan */
			$ova_godina = date_format($date,'Y');

			if($user->abs_days != null && array_key_exists($ova_godina, unserialize( $user->abs_days)) ) {
				$absence_days = unserialize( $user->abs_days);
				$days = intval($absence_days[$ova_godina]);
			} else {
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
			}
			
					
			return $days;
		}
		
		/*  računa trenutan razmjeran GO */
		public static function razmjeranGO( $user )   
		{
			$date = new DateTime('now');    /* današnji dan */
			$ova_godina = date_format($date,'Y');
			$ovaj_mjesec = date_format($date,'m');
			$ovaj_dan = date_format($date,'d');
			
			if($user->abs_days != null && array_key_exists($ova_godina, unserialize( $user->abs_days)) ) {
				$absence_days = unserialize( $user->abs_days);
				$GO = $absence_days[$ova_godina];
			} else {
				$GO  = BasicAbsenceController::daysThisYear($user);
			}
			if($ovaj_dan < 15){
				$ovaj_mjesec -=1;
			} 

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
																															/***  dani go iz $user->abs_days ****/
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
				
			$days = round($razmjeranGO, 0, PHP_ROUND_HALF_UP); // razmjerni dani za traženu godinu			

			return $days;
		}

		/* dani GO PROŠLA godina */
		public static function godisnjiPG($user)      /*** OK abs days */
		{
			$date = new DateTime('now');    /* današnji dan */
			$year = date_format($date,'Y') - 1;

			if($user->abs_days != null && array_key_exists($year, unserialize( $user->abs_days)) ) {
				$absence_days = unserialize( $user->abs_days);
				$days= $absence_days[$year];				
			} else {
				/* Računa ukupan staz za prošlu godinu - do 31.12.*/
				$stazPG =  BasicAbsenceController::stazUkupnoPG($user, $year);

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
			}
		
			return $days;
		}

		/* dani GO ODREĐENA godina */
		public static function godisnjiGodina($user, $godina)          /*** OK abs days */
		{
			if($user->abs_days != null && array_key_exists($godina, unserialize( $user->abs_days)) ) {
				$absence_days = unserialize( $user->abs_days);
				$days= $absence_days[$godina];				
			} else {
				/* Računa ukupan staz za određenu godinu - do 31.12.*/
				$stazPG =  BasicAbsenceController::stazUkupnoPG($user, $godina);

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
			}
		
			return $days;
		}

		/*  razmjeran GO PROŠLA godina*/
		public static function razmjeranGO_PG( $user)    
		{
			$date = new DateTime('now');    /* današnji dan */
			$ova_godina = date_format($date,'Y');
			$mjesec_danas = date_format($date,'m');
			$prosla_godina = date_format($date,'Y')-1;
			$datumPG = new DateTime($prosla_godina . '-12-31');
			$razmjeranGO_PG = 0;
			
			$GO  = BasicAbsenceController::godisnjiPG($user); /* dani GO prošla godina */

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

		/*  razmjeran GO ODREĐENA godina*/
		public static function razmjeranGO_Godina($user, $year)    
		{
			$date = new DateTime('now');    /* današnji dan */
			$ova_godina = date_format($date,'Y');
			$mjesec_danas = date_format($date,'m');
		
			$datumPG = new DateTime($year . '-12-31');
			$razmjeranGO_PG = 0;
			
			$GO  = BasicAbsenceController::godisnjiGodina($user, $year); /* dani GO određena godina */

			if($user->reg_date) {
				$datum_prijave = $user->reg_date;
				$datum_prijave = explode('-', $user->reg_date);
				$prijavaGodina = $datum_prijave[0];
				$prijava = new DateTime($user->reg_date);
				$staz = $prijava->diff($datumPG);   /* staz u Duplicu do 31.12. prošla godina*/
				$mjesec = $staz->format('%m');
				$dan = $staz->format('%d');

				if ($year == $ova_godina ) {
					$razmjeranGO_PG  = round(BasicAbsenceController::razmjeranGO($user), 0,PHP_ROUND_HALF_UP); 
				} else {
					if($prijavaGodina < $year){
						$razmjeranGO_PG = $GO; 
					}  elseif ($prijavaGodina == $year) {
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
				
			}
			
			return $razmjeranGO_PG;
		}
	/* ########################      DANI GODIŠENJEG ODMORA  kraj  ######################## */	

	/* ########################      ZAHTJEVI    ######################## */	

		public static function zahtjevi ($user) 
		{
			$datum = new DateTime('now');    /* današnji dan */
			$ova_godina = date_format($datum,'Y');
			$prosla_godina = date_format($datum,'Y') - 1;
			$mjesec_danas = date_format($datum,'m');
			
			$requestAllYear = BasicAbsenceController::requestAllYear($user);
			
			$years = BasicAbsenceController::yearsRequests($user); // sve godine zahtjeva
			
			if(! in_array($ova_godina, $years)) {
				array_push($years, $ova_godina );
			}

			$array_godine = array();
			$array_godine['ukupnoPreostalo'] = 0;
			$array_godine['ukupnoGO'] = BasicAbsenceController::godisnjiGodina($user, $ova_godina);
			$ukupno_dana_zahtjevi = 0;
			$ukupno_dana_GO = 0;

			foreach ($years  as $year) {
				$razmjerni_dani =  BasicAbsenceController::razmjeranGO_Godina($user, $year);  
				if( isset($requestAllYear[ $year ] ) && ! empty($requestAllYear[ $year ]) ) {
					$preostali_dani = BasicAbsenceController::razmjeranGO_Godina($user, $year)- count($requestAllYear[ $year ]);
					$zahtjevi = $requestAllYear[ $year ];
					$dani_zahtjeva = count($requestAllYear[ $year ]);
					$ukupno_dana_zahtjevi =  count($requestAllYear[ $year ]);
				} else {
					$preostali_dani = $razmjerni_dani;
					$zahtjevi = array();
					$dani_zahtjeva = 0;
					$ukupno_dana_zahtjevi = 0;
				}
				
				$array_godine[$year] = ["zahtjevi" => $zahtjevi];
				$array_godine[$year] += ["razmjerniDani" => $razmjerni_dani ];
				$array_godine[$year] += ["dani_zahtjeva" => $dani_zahtjeva];
				$array_godine[$year] += ["preostalo_dana" => $preostali_dani] ;
				$ukupno_dana_GO += $razmjerni_dani;
				$ukupno_dana_zahtjevi += $ukupno_dana_zahtjevi;

				$array_godine['ukupnoPreostalo'] += $preostali_dani;
				
			}
		
			return $array_godine;
		}

		//računa iskorištene dane godišnjeg odmora ova godina  
		public static function daniZahtjevi($user)
		{
			/* Zahtjevi ova godina */	
			$zahtjevi = Absence::where('employee_id',$user->employee->id)->where('approve','1')->get();
			$holidays = BasicAbsenceController::holidays();

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
						
						if(date_format($dan,'Y') == $ova_godina ){
							if(! in_array(date_format($dan,'Y-m-d'), $holidays) && date_format($dan,'N') < 6) {
								$ukupnoGO += 1;
							}
							/* 
							if(date_format($dan,'d') == '01' && date_format($dan,'m') == '01' ||
								date_format($dan,'d') == '06' && date_format($dan,'m') == '01' ||
								date_format($dan,'d') == '01' && date_format($dan,'m') == '05' ||
								date_format($dan,'d') == '22' && date_format($dan,'m') == '06' ||
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
							} */
						}
						
					}	
				}
			}
			return $ukupnoGO;
		}

		//računa iskorištene dane godišnjeg odmora zadana godina  
		public static function daniZahtjeviGodina($user, $year)
		{
			$holidays = BasicAbsenceController::holidays();

			/* Zahtjevi zadana godina */	
			if($user->employee) {
				$zahtjevi = Absence::where('employee_id', $user->employee->id)->where('approve','1')->get();
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
							if(date_format($dan,'Y') == $year ){
								if(! in_array(date_format($dan,'Y-m-d'), $holidays) && date_format($dan,'N') < 6) {
									$ukupnoGO += 1;
								}

								/* if(date_format($dan,'d') == '01' && date_format($dan,'m') == '01' ||
									date_format($dan,'d') == '06' && date_format($dan,'m') == '01' ||
									date_format($dan,'d') == '01' && date_format($dan,'m') == '05' ||
									date_format($dan,'d') == '22' && date_format($dan,'m') == '06' ||
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
								} */
							}
						}	
					}
				}
			} else {
				$ukupnoGO = 0;
			}
			
		
			return $ukupnoGO;
		}

		// Računa broj radnih dana između dva datuma, ako je izlazak 
		public static function daniGO($zahtjev)
		{
			$holidays = BasicAbsenceController::holidays();

			$begin = new DateTime($zahtjev['start_date']);
			$end = new DateTime($zahtjev['end_date']);
			$end->setTime(0,0,1);
			$interval = DateInterval::createFromDateString('1 day');
			$period = new DatePeriod($begin, $interval, $end);
			
			$brojDana = 0;
			
			foreach ($period as $dan) {
				if(! in_array(date_format($dan,'Y-m-d'), $holidays) && date_format($dan,'N') < 6) {
					$brojDana += 1;
				}
			}
			return $brojDana;
		}

		// Računa broj radnih dana između dva datuma, ako je izlazak 
		public static function array_dani_zahtjeva($zahtjev)
		{
			$holidays = BasicAbsenceController::holidays();

			$begin = new DateTime($zahtjev['start_date']);
			$end = new DateTime($zahtjev['end_date']);
			$end->setTime(0,0,1);
			$interval = DateInterval::createFromDateString('1 day');
			$period = new DatePeriod($begin, $interval, $end);
			
			$array_dani = array();
			
			foreach ($period as $dan) {
				if(! in_array(date_format($dan,'Y-m-d'), $holidays) && date_format($dan,'N') < 6) {
					array_push($array_dani,date_format($dan,'Y-m-d'));
				}
			}
			return $array_dani;
		}


		/** bolovanje za određen mjesec */
		public static function bolovanje($user)
		{
			$datum = new DateTime('now');    /* današnji dan */
			$ova_godina = date_format($datum,'Y');
			$prosla_godina = date_format($datum,'Y') - 1;
			$mjesec_danas = date_format($datum,'m');
			$bolovanje = array();
			$bolovanje_OM = 0;
			$holidays = BasicAbsenceController::holidays();

			$zahtjevi = Absence::join('absence_types', 'absence_types.id', 'absences.type')->select('absences.*', 'absence_types.mark' )->where('employee_id',$user->id)->where('mark','BOL')->where('approve', 1)->orderBy('start_date','ASC')->get();
			$years = BasicAbsenceController::yearsRequests($user); // sve godine zahtjeva	
			
			foreach ($years as $year) {
				$bolovanje_dani = 0;
				foreach($zahtjevi as $zahtjev){		
					$begin = new DateTime($zahtjev->start_date);
					$end = new DateTime($zahtjev->end_date);
					$end->setTime(0,0,1);
					$interval = DateInterval::createFromDateString('1 day');
					$period = new DatePeriod($begin, $interval, $end);
					foreach ($period as $dan) {
						if(date_format($dan,'Y') == $year) {

							if(! in_array(date_format($dan,'Y-m-d'), $holidays) && date_format($dan,'N') < 6) {
								$bolovanje_dani += 1;
								if(date_format($dan,'m') == $mjesec_danas && date_format($dan,'Y') == $ova_godina) {
									$bolovanje_OM += 1;
								}
							}

						/* 	if(date_format($dan,'N') < 6 ){
								if(date_format($dan,'d') == '01' && date_format($dan,'m') == '01' ||
								date_format($dan,'d') == '06' && date_format($dan,'m') == '01' ||
								date_format($dan,'d') == '01' && date_format($dan,'m') == '05' ||
								date_format($dan,'d') == '22' && date_format($dan,'m') == '06' ||
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
									$bolovanje_dani += 1;
									if(date_format($dan,'m') == $mjesec_danas && date_format($dan,'Y') == $ova_godina) {
										$bolovanje_OM += 1;
									}
								}
							} */
						}
					}
				}
				$bolovanje[ $year ]  = $bolovanje_dani;
			}
			$bolovanje[ 'bolovanje_OM' ]  = $bolovanje_OM;
			return $bolovanje;
		}

		/* godine zahtjeva */
		public static function yearsRequests ($user) 
		{
			$date = new DateTime('now');    /* današnji dan */
			$this_year = date_format($date,'Y');
			$requests = Absence::join('absence_types', 'absence_types.id', 'absences.type')->select('absences.*', 'absence_types.mark' )->where('employee_id', $user->id)->orderBy('start_date','ASC')->get();
		
			$years = array();
			if( count($requests) > 0 ) {
				$first_year = date('Y', strtotime($requests->first()->start_date));
				while ($first_year <= $this_year) {
					array_push($years, strval($first_year));
					$first_year++;
				}
			}	
			if(! in_array($this_year, $years)) {
				array_push($years, strval($this_year) );
			}

				
            $abs_days = '';
			if( $user->abs_days) {
				$abs_days = unserialize( $user->abs_days);
				
			}
			if(is_array($abs_days)) {
			
				foreach ($abs_days as $year => $days) {
					if(! in_array($year, $years)) {
						array_push($years, strval($year) );
					}
				}
			}

			return $years;
		}

		/* Vraća sve dane zahtjeva za sve godine */
		public static function requestAllYear ($user) 
		{
			$requestsArray = array();
			$requests_next_year = array();
			$requests_previous_year = array();

			$years = BasicAbsenceController::yearsRequests($user); // sve godine zahtjeva
			$holidays = BasicAbsenceController::holidays();

			$requests = Absence::join('absence_types', 'absence_types.id', 'absences.type')->select('absences.*', 'absence_types.mark' )->where('employee_id', $user->id)->where('approve',1)->orderBy('start_date','ASC')->get(); // svi zahtjevi djelatnika
			asort($years);
			foreach ($years as $year) {
				
				
				$requestsArray[$year] = array();
				$GO_dani = BasicAbsenceController::razmjeranGO_Godina($user, $year); // razmjerni dani za godinu
				
				$requests_previous_year = array_unique(array_merge($requests_previous_year, $requests_next_year));// zahtjevi_PG - zahtjevi iz slijedeće godine do 30.6. ako ima razmjernih dana slobodnih + prebačeni zahtjevi iz prošle godine ako nije bilo dana - maknuti dupli datumi
				
				$zahtjevi_godina = array();
				
				foreach($requests as $request){
					$begin = new DateTime($request->start_date);
					$end = new DateTime($request->end_date);
					$end->setTime(0,0,1);
					$interval = DateInterval::createFromDateString('1 day');
					$period = new DatePeriod($begin, $interval, $end);

					foreach ($period as $dan) {
						if(! in_array(date_format($dan,'Y-m-d'), $holidays) && date_format($dan,'N') < 6) {
							if($GO_dani > 0) {
								if(date_format($dan,'Y') == $year) {
									if(isset( $requestsArray[$year-1] )) {
										
										if( ! in_array( date_format($dan,'Y-m-d'), $requestsArray[$year-1])) {
											array_push($zahtjevi_godina, date_format($dan,'Y-m-d'));
											$GO_dani--;	
										}
									} else {
										array_push($zahtjevi_godina, date_format($dan,'Y-m-d'));
										$GO_dani--;	
									}
																
								} elseif(date_format($dan,'m') < '07' && date_format($dan,'Y') == $year+1 ) {
									array_push($zahtjevi_godina, date_format($dan,'Y-m-d'));
									array_push($requests_previous_year, date_format($dan,'Y-m-d'));
									$GO_dani--;
								}	
							} else {   // ako nema slobodnih dana u godini prijenos zahtjeva u drugu godinu
								if(date_format($dan,'Y') == $year) {
									array_push($requests_next_year, date_format($dan,'Y-m-d'));
								}
								if( $year == date('Y') ) {
									array_push($zahtjevi_godina, date_format($dan,'Y-m-d'));
									$GO_dani--;	
								} else if(date_format($dan,'Y') == $year) {
									array_push($requests_next_year, date_format($dan,'Y-m-d'));
									$GO_dani--;	
								}
							}
						}

						/* if(date_format($dan,'N') < 6 ){
							if(date_format($dan,'d') == '01' && date_format($dan,'m') == '01' ||
								date_format($dan,'d') == '06' && date_format($dan,'m') == '01' ||
								date_format($dan,'d') == '01' && date_format($dan,'m') == '05' ||
								date_format($dan,'d') == '22' && date_format($dan,'m') == '06' ||
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
								if($GO_dani > 0) {
									if(date_format($dan,'Y') == $year) {
										if(isset( $requestsArray[$year-1] )) {
											if( ! in_array( date_format($dan,'Y-m-d'), $requestsArray[$year-1])) {
												array_push($zahtjevi_godina, date_format($dan,'Y-m-d'));
												$GO_dani--;	
											}
										} else {
											array_push($zahtjevi_godina, date_format($dan,'Y-m-d'));
											$GO_dani--;	
										}
																	
									} elseif(date_format($dan,'m') < '07' && date_format($dan,'Y') == $year+1 ) {
										array_push($zahtjevi_godina, date_format($dan,'Y-m-d'));
										array_push($requests_previous_year, date_format($dan,'Y-m-d'));
										$GO_dani--;
									}	
								} else {   // ako nema slobodnih dana u godini prijenos zahtjeva u drugu godinu
									if(date_format($dan,'Y') == $year) {
										array_push($requests_next_year, date_format($dan,'Y-m-d'));
									}
								}
							}
						} */
					}	
				}
				$requestsArray[$year] = array_unique(array_merge( $zahtjevi_godina));		
			}
			
			return $requestsArray;
		}
	/* ########################      ZAHTJEVI  kraj  ######################## */	

	public static function holidays ()
	{
		$holidays = array();

		//2018
		array_push($holidays,
		'2018-01-01','2018-01-06','2018-04-01','2018-04-02','2018-05-01','2018-05-31','2018-06-22','2018-06-25','2018-08-05','2018-08-15','2018-10-08','2018-11-01','2018-12-25','2018-12-26');
		//2019
		array_push($holidays,
		'2019-01-01','2019-01-06','2019-04-21','2019-04-22','2019-05-01','2019-06-20','2019-06-22','2019-06-25','2019-08-05','2019-08-15','2019-10-08','2019-11-01','2019-12-25','2019-12-26');
		//2020
		array_push($holidays,
		'2020-01-01','2020-01-06','2020-04-12','2020-04-13','2020-05-01','2020-05-30','2020-06-11','2020-06-22','2020-08-05','2020-08-15','2020-11-01','2020-11-18','2020-12-25','2020-12-26');
		//2021
		array_push($holidays,
		'2021-01-01','2021-01-06','2021-04-04','2021-04-05','2021-05-01','2021-05-30','2021-06-03','2021-06-22','2021-08-05','2021-08-15','2021-11-01','2021-11-18','2021-12-25','2021-12-26');
		//2022
		array_push($holidays,
		'2022-01-01','2022-01-06','2022-04-17','2022-04-18','2022-05-01','2022-05-30','2022-06-16','2022-06-22','2022-08-05','2022-08-15','2022-11-01','2022-11-18','2022-12-25','2022-12-26');

		return $holidays;
	}

	public static function holidays_with_names () 
	{
		$holidays = array(
			"2019-01-01"	=>	"Nova godina",
			"2019-01-06"	=>	"Sveta tri kralja",
			"2019-04-21"	=>	"Uskrs",
			"2019-04-22"	=>	"Uskrsni ponedjeljak",
			"2019-05-01"	=>	"Praznik rada",
			"2019-06-20"	=>	"Tijelovo",
			"2019-06-22"	=>	"Dan antifašističke borbe",
			"2019-06-25"	=>	"Dan državnosti",
			"2019-08-05"	=>	"Dan pobjede i domovinske zahvalnosti i Dan hrvatskih branitelja",
			"2019-08-15"	=>	"Velika Gospa",
			"2019-10-08"	=>	"Dan neovisnosti",
			"2019-11-01"	=>	"Dan svih svetih",
			"2019-12-25"	=>	"Božić",
			"2019-12-26"	=>	"Sveti Stjepan",

			"2020-01-01"	=>	"Nova godina",
			"2020-01-06"	=>	"Sveta tri kralja",
			"2020-04-12"	=>	"Uskrs",
			"2020-04-13"	=>	"Uskrsni ponedjeljak",
			"2020-05-01"	=>	"Praznik rada",
			"2020-05-30"	=>	"Dan državnosti",
			"2020-06-11"	=>	"Tijelovo",
			"2020-06-22"	=>	"Dan antifašističke borbe",
			"2020-08-05"	=>	"Dan pobjede i domovinske zahvalnosti i Dan hrvatskih branitelja",
			"2020-08-15"	=>	"Velika Gospa",
			"2020-11-01"	=>	"Dan svih svetih",
			"2020-11-18"	=>	"Dan sjećanja na žrtve Domovinskog rata i Dan sjećanja na žrtvu Vukovara i Škabrnje",
			"2020-12-25"	=>	"Božić",
			"2020-12-26"	=>	"Sveti Stjepan ",

			"2021-01-01"	=>	"Nova godina",
			"2021-01-06"	=>	"Sveta tri kralja",
			"2021-04-04"	=>	"Uskrs",
			"2021-04-05"	=>	"Uskrsni ponedjeljak",
			"2021-05-01"	=>	"Praznik rada",
			"2021-05-30"	=>	"Dan državnosti",
			"2021-06-03"	=>	"Tijelovo",
			"2021-06-22"	=>	"Dan antifašističke borbe",
			"2021-08-05"	=>	"Dan pobjede i domovinske zahvalnosti i Dan hrvatskih branitelja",
			"2021-08-15"	=>	"Velika Gospa",
			"2021-11-01"	=>	"Dan svih svetih",
			"2021-11-18"	=>	"Dan sjećanja na žrtve Domovinskog rata i Dan sjećanja na žrtvu Vukovara i Škabrnje",
			"2021-12-25"	=>	"Božić",
			"2021-12-26"	=>	"Sveti Stjepan",

			"2022-01-01"	=>	"Nova godina",
			"2022-01-06"	=>	"Sveta tri kralja",
			"2022-04-17"	=>	"Uskrs",
			"2022-04-18"	=>	"Uskršnji ponedjeljak",
			"2022-05-01"	=>	"Praznik rada",
			"2022-05-30"	=>	"Dan državnosti",
			"2022-06-16"	=>	"Tijelovo",
			"2022-06-22"	=>	"Dan antifašističke borbe",
			"2022-08-05"	=>	"Dan pobjede i domovinske zahvalnosti i Dan hrvatskih branitelja",
			"2022-08-15"	=>	"Velika Gospa",
			"2022-11-01"	=>	"Dan svih svetih",
			"2022-11-18"	=>	"Dan sjećanja na žrtve Domovinskog rata i Dan sjećanja na žrtvu Vukovara i Škabrnje",
			"2022-12-25"	=>	"Božić",
			"2022-12-26"	=>	"Sveti Stjepan",
		);


		return $holidays;
	}
}
