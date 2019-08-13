@extends('Centaur::layout')

@section('title', 'Rezultati anketa')
<link rel="stylesheet" href="{{ URL::asset('css/anketa.css') }}"/>
@section('content')
<div class="evaluation">
	<a class="btn btn-md gumb_natrag" href="{{ url()->previous() }}">
		<i class="fas fa-angle-double-left"></i>
		Natrag
	</a>
	<section>
		<h3>{{ $employee->user['first_name'] . ' ' . $employee->user['last_name'] . ', anketa ' . $questionnaire->name . ' - ' . $mjesec_godina  }}</h3>
			<table class="tbl_ocjene" id="">
				<thead>
					<tr>
						<th>Naziv grupe</th>
						<th>Osobna ocjena</th>
						<th>Ocjena direktora</th>
						<th>Ocjena djelatnika {{ '(' . count($evaluationEmployees->where('employee_id','!=', $employee->employee_id)) . ')'}} </th>
						<th>Ciljana ocjena / Cilj</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$ukupnaOS = 0;
						$ukupnaDI = 0;
						$ukupnaDJ = 0;
						$a = 0;
					?>
					
					@foreach($evaluationCategories as $evaluatingGroup)
						@if($evaluations->where('category_id', $evaluatingGroup->id)->first())
						<?php
							$i = 0;
							$j = 0;
							$x = 0;  // broj pitanja
							$osobnaOcjena = 0;
							$osobnaOcjenaKON = 0;
							$ocjenaDirektora = 0;
							$ocjenaDirektoraKON = 0;
							$ocjenaOstali = 0;	
							$ocjenaOstaliKON = 0;	
						?>
						<tr class="group">
							<td>{{  $evaluatingGroup->name }}</td>
								@foreach($evaluations->where('category_id', $evaluatingGroup->id ) as $evaluation)
									<?php
										$rezultat = 0;
										if($evaluation->user_id == $employee->id){
											$i++;
											$rezultat = number_format($evaluation->rating,2);
											$osobnaOcjena += $rezultat;	
										} elseif($evaluation->user_id == '58'){
											$j++;
											$rezultat = number_format($evaluation->rating,2);	
											$ocjenaDirektora += $rezultat;
										}else {
											$x++;
											$rezultat = number_format($evaluation->rating,2);
											$ocjenaOstali += $rezultat;	
										}
									?>
								@endforeach
								<?php
									if($osobnaOcjena === 0){
											$i = 1;
										} 
										if($ocjenaDirektora === 0){
											$j = 1;
										} 
										if($ocjenaOstali === 0 ){
											$x = 1;
										} 
										$osobnaOcjenaKON =  $osobnaOcjena / $i;
										$ocjenaDirektoraKON  = $ocjenaDirektora / $j;
										$ocjenaOstaliKON  =  $ocjenaOstali / $x;
								?>
							<td>{{ number_format($osobnaOcjenaKON,2) }} <small>{{ '(' . number_format($osobnaOcjenaKON / $ratings->max('rating') *100,2) . '%)' }}</td>
							<td>{{ number_format($ocjenaDirektoraKON,2)  }} <small>{{ '(' . number_format($ocjenaDirektoraKON / $ratings->max('rating') *100, 2) . '%)' }}</td>
							<td>{{ number_format($ocjenaOstaliKON,2) }} <small>{{ '(' . number_format($ocjenaOstaliKON / $ratings->max('rating') *100,2) . '%)' }}</td>
							<td>
								
							</td>
						</tr>
						@foreach($evaluatingQuestions->where('category_id',$evaluatingGroup->id) as $question)
							<tr>
								<td class="question_description">
									{{ $question->description }}
								</td>
							
								<?php
									if(! $evaluations->where('employee_id', $employee->id)->where('user_id',$employee->id)->where('category_id', $evaluatingGroup->id)->where('question_id', $question->id)->first()){
										$ratingOS = 0; 
									} else {
										$ratingOS = $evaluations->where('employee_id', $employee->id)->where('user_id', $employee->id)->where('category_id', $evaluatingGroup->id)->where('question_id', $question->id)->first()->rating;
									}
									if(! $evaluations->where('employee_id', $employee->id)->where('user_id',58)->where('category_id', $evaluatingGroup->id)->where('question_id', $question->id)->first()){
										$ratingDIR = 0;
									} else {
										$ratingDIR = $evaluations->where('employee_id', $employee->id)->where('user_id', '58')->where('category_id', $evaluatingGroup->id)->where('question_id', $question->id)->first()->rating;
									}
									$ocjena_Os  = $ratingOS;
									$ocjena_Dir = $ratingDIR ;
									$prosjecnaOcj = (($ocjena_Os + $ocjena_Dir ) / 2 ) // postotak procječna ocjena direktor i osobna
								?>
								<td class="question_description">
									{{$ocjena_Os }} <small>{{ ' (' . $ocjena_Os / $ratings->max('rating') *100 . '%)' }}</small>
								</td>
								<td class="question_description">
								<input name="question_id[{{ $question->id }}]" type="hidden" class="" value="{{ $question->id }}"  />
									@if(Sentinel::getUser()->last_name == 'Rendulić' && Sentinel::getUser()->first_name == 'Željko' || Sentinel::getUser()->last_name == 'Barberić' && Sentinel::getUser()->first_name == 'Matija' || Sentinel::getUser()->last_name == 'Juras')
									<select class="rating" name="rating[{{ $question->id }}]">
										<option value="" disabled ></option>
										@foreach($ratings as $rating)
											<option value="{{ $rating->rating }}" {!! $rating->rating == $ocjena_Dir ? 'selected' : '' !!}  >{{ $rating->rating }}</option>
										@endforeach
									</select>
									@else
									{{ $ocjena_Dir }}
									@endif
								</td>
								<input name="result[{{ $evaluatingGroup->id }}]" type="hidden" value="{{  $ratingDIR }}" />
								<td class="question_description"></td>
								<td  class="question_description"></td>
							</tr>
							<tr><td colspan="5" class="description"><small>{{ $question->opis2 }}</small></td>
							</tr>
							
						@endforeach
							<?php
								$ukupnaOS += $osobnaOcjenaKON;
								$ukupnaDI += $ocjenaDirektoraKON;
								$ukupnaDJ += $ocjenaOstaliKON;
								$a++;
							?>	
						
						<input name="category_id[{{ $evaluatingGroup->id }}]" type="hidden" class="" value="{{ $evaluatingGroup->id }}"  />
						@endif
					@endforeach
				</tbody>
				<tfoot>
				<!--
					<tr>
						<td colspan="5">
						<textarea name="comment_uprava" type="text" class="comment_upravi" value="{{ old('comment_uprava') }}" rows="3" placeholder="Upiši komentar upravi"></textarea></td>
					</tr>-->
					<tr>
						<td>Ukupna ocjena</td>
						<td>{{ number_format($ukupnaOS / $a,2) . ' (' .  number_format($ukupnaOS / $a / $ratings->max('rating') *100,2) . '%)'}}</td>
						<td>{{ number_format($ukupnaDI / $a,2) . ' (' . number_format($ukupnaDI/ $a/ $ratings->max('rating') *100,2) . '%)'}}</td>
						<td>{{ number_format($ukupnaDJ / $a,2) . ' (' . number_format($ukupnaDJ/ $a/ $ratings->max('rating') *100,2) . '%)'}}</td>
						<td><input name="_token" value="{{ csrf_token() }}" type="hidden">
							<!--<input class="btn btn-lg" type="submit" value="Upiši ciljeve" id="stil1"></td>-->
					</tr>
				</tfoot>
			</table>
		<!--</form>-->
	</section>
</div>
@stop