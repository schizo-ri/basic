<link rel="stylesheet" href="{{ URL::asset('/../css/slide_show.css') }}"/>
<link rel="stylesheet" href="{{ URL::asset('/../css/modal.css') }}"/>
@php
    use App\Models\Document;
    use App\Http\Controllers\DashboardController;
@endphp
<div class="modal-body">
    <div class="slideshow-container  col-9" >
        <span class="slide_index" hidden >{{ $id }}</span>
        @foreach ($images_interest as $image)
            @php
                $basename = str_replace('_small','',pathinfo($image)['basename']);
                $document = Document::where('path', $path)->where('title',$basename)->first();
                if($document) {
                    $document_id = $document->id;
                } else {
                    $document_id = 0;
                }
            @endphp
                @if(pathinfo($image)['extension'] == 'mp4')
                    <div class="mySlides">
                        <video width="1000" controls>
                            <source src="{{ URL::asset( $path . $image ) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <a href="{{ route('documents.destroy', $document_id) }}" class="action_confirm danger" data-method="delete" data-token="{{ csrf_token() }}" hidden="true" >
                            <i class="far fa-trash-alt"></i>
                        </a>
                    </div>
                @elseif(! strpos(pathinfo($image)['basename'], '_small') && (pathinfo($image)['extension'] == 'jpg' ||  pathinfo($image)['extension'] == 'jpeg' ||  pathinfo($image)['extension'] == 'png' ||  pathinfo($image)['extension'] == 'gif' ||  pathinfo($image)['extension'] == 'svg' ))    
                    <div class="mySlides">
                        <img src="{{ URL::asset( $path . $image ) }}" alt="image"  />
                        <a href="{{ route('documents.destroy', $document_id) }}" class="action_confirm danger" data-method="delete" data-token="{{ csrf_token() }}" hidden="true" >
                            <i class="far fa-trash-alt"></i>
                        </a>
                    </div>
                @endif
                
        @endforeach
    </div>
   <a class="prev">&#10094;</a>
    <a class="next">&#10095;</a>
</div>
<div class="modal_side"  >
    @foreach ($images_interest as $image)
        @php
            $basename = str_replace('_small','',pathinfo($image)['basename']);
            $document = Document::where('path', $path)->where('title',$basename)->first();
            if($document) {
                $profile_image = DashboardController::profile_image($document->employee['id']);
                $user_name =  DashboardController::user_name($document->employee['id']);
            }
        @endphp
            @if ($document)
                
                    @if(pathinfo($image)['extension'] == 'mp4')
                        
                            <h3>{{ $document->description }}</h3>
                            <p>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($document->created_at))->diffForHumans()  }}</p>
                            <p>
                                {{ $document->employee->user['first_name'] . ' ' .  $document->employee->user['last_name']  }}
                                <span class="profile_photo">
                                    @if(isset($profile_image))
                                        <img class="radius50" src="{{ URL::asset('storage/' . $user_name . '/profile_img/' . end($profile_image)) }}" alt="Profile image"  />
                                    @else
                                        <img class="radius50 " src="{{ URL::asset('img/profile.png') }}" alt="Profile image"  />
                                    @endif
                                </span>
                            </p>
                        </div>
                    @elseif(! strpos(pathinfo($image)['basename'], '_small') && (pathinfo($image)['extension'] == 'jpg' ||  pathinfo($image)['extension'] == 'jpeg' ||  pathinfo($image)['extension'] == 'png' ||  pathinfo($image)['extension'] == 'gif' ||  pathinfo($image)['extension'] == 'svg' ))
                        <div class="mySlides_info">
                            <h3>{{ $document->description }}</h3>
                            <p>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($document->created_at))->diffForHumans()  }}</p>
                            <p>
                                {{ $document->employee->user['first_name'] . ' ' .  $document->employee->user['last_name']  }}
                                <span class="profile_photo">
                                    @if(isset($profile_image) && is_array(isset($profile_image)))
                                        <img class="radius50" src="{{ URL::asset('storage/' . $user_name . '/profile_img/' . end($profile_image)) }}" alt="Profile image"  />
                                    @else
                                        <img class="radius50 " src="{{ URL::asset('img/profile.png') }}" alt="Profile image"  />
                                    @endif
                                </span>
                            </p>
                        </div>
                    @endif
                    
                
            @endif
    @endforeach
</div>
<script>
$( function () {
    $('.modal').addClass('slide');
    $.getScript( '/../js/slide_show.js');
});
</script>
