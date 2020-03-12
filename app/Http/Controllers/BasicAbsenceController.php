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

	/* ########################      DANI GODIŠENJEG ODMORA    ######################## */	

		// Vraća broj dana godišnjeg ova godina    
		public static function daysThisYear($user)
		{
			$all_service = BasicAbsenceController::yearsServiceAll($user);  /* ukupan staž  */			
			
			if( date('Y', strtotime($user->reg_date)) == date('Y') && $user->abs_days_this_y ) {
				$days = $user->abs_days_this_y;
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
	
	/* ########################      ZAHTJEVI    ######################## */	

		public static function zahtjevi ($user) 
		{
			$datum = new DateTime('now');    /* današnji dan */
			$ova_godina = date_format($datum,'Y');
			$prosla_godina = date_format($datum,'Y') - 1;
			$mjesec_danas = date_format($datum,'m');
			
			$requestAllYear = BasicAbsenceController::requestAllYear($user);
			
			$years = BasicAbsenceController::yearsRequests($user); // sve godine zahtjeva
			
			if(! in_array($ova_godina,$years)) {
				array_push($years,$ova_godina );
			}

			$array_godine = array();
			$array_godine['ukupnoPreostalo'] = 0;
			$array_godine['ukupnoGO'] = BasicAbsenceController::godisnjiGodina($user, $ova_godina);
			$ukupno_dana_zahtjevi = 0;
			$ukupno_dana_GO = 0;

			foreach ($years  as $year) {
				$razmjerni_dani =  BasicAbsenceController::razmjeranGO_Godina($user, $year);
				if( isset($requestAllYear[ $year ])) {
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
			
		//	$array_godine['ukupnoPreostalo'] = $ukupno_dana_GO - $ukupno_dana_zahtjevi;
			
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

			/*
				array:3 [▼
					2019 => array:4 [▼
						"zahtjevi" => array:7 [▼
						0 => "2019-10-21"
						1 => "2019-10-22"
						2 => "2019-10-23"
						3 => "2019-10-24"
						4 => "2019-10-25"
						6 => "2019-10-30"
						7 => "2019-10-31"
						]
						"razmjerniDani" => 25
						"dani_zahtjeva" => 7
						"preostalo_dana" => 18
					]
					2020 => array:4 [▼
						"zahtjevi" => []
						"razmjerniDani" => 4.0
						"dani_zahtjeva" => 0
						"preostalo_dana" => 4.0
					]
					"ukupnoPreostalo" => 22.0
				]
 			*/
		//	dd($array_godine);
		
			return $array_godine;
		}

		//računa iskorištene dane godišnjeg odmora ova godina  
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

		//računa iskorištene dane godišnjeg odmora zadana godina  
		public static function daniZahtjeviGodina($user, $year)
		{
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
							if(date_format($dan,'N') < 6 ){
								if(date_format($dan,'Y') == $year ){
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
			} else {
				$ukupnoGO = 0;
			}
			
		
			return $ukupnoGO;
		}

		// Računa broj radnih dana između dva datuma, ako je izlazak 
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

		/** bolovanje za određen mjesec */
		public static function bolovanje($user)
		{
			$datum = new DateTime('now');    /* današnji dan */
			$ova_godina = date_format($datum,'Y');
			$prosla_godina = date_format($datum,'Y') - 1;
			$mjesec_danas = date_format($datum,'m');
			$bolovanje = array();
			$bolovanje_OM = 0;
		

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
									$bolovanje_dani += 1;
									if(date_format($dan,'m') == $mjesec_danas && date_format($dan,'Y') == $ova_godina) {
										$bolovanje_OM += 1;
									}
								}
							}
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
			$requests = Absence::join('absence_types', 'absence_types.id', 'absences.type')->select('absences.*', 'absence_types.mark' )->where('approve',1)->where('employee_id', $user->id)->orderBy('start_date','ASC')->get();
		
			$years = array();
			if($requests->first()) {
				$first_year = date('Y', strtotime($requests->first()->start_date));
				while ($first_year <= $this_year) {
					array_push($years, strval($first_year));
					$first_year++;
				}
			}	
			
			return $years;
		}

		/* Vraća sve dane zahtjeva za sve godine */
		public static function requestAllYear ($user) 
		{
			$requestsArray = array();
		
			$years = BasicAbsenceController::yearsRequests($user); // sve godine zahtjeva
		
			$requests = Absence::join('absence_types', 'absence_types.id', 'absences.type')->select('absences.*', 'absence_types.mark' )->where('employee_id', $user->id)->where('approve',1)->orderBy('start_date','ASC')->get(); // svi zahtjevi djelatnika
			
			$requests_next_year = array();
			$requests_previous_year = array();

			foreach ($years as $year) {
				$GO_dani = BasicAbsenceController::razmjeranGO_Godina($user, $year); // razmjerni dani za godinu
				
				$requests_previous_year = array_unique(array_merge($requests_previous_year, $requests_next_year));
				$zahtjevi_godina = array();
				
				foreach($requests as $request){
					$begin = new DateTime($request->start_date);
					$end = new DateTime($request->end_date);
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
						}
					}	
				}
				$requestsArray[$year] = array_unique(array_merge( $zahtjevi_godina));		
			}
			
			return $requestsArray;
		}
}