<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Employee;
use Sentinel;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
     /**
   *
   * Set middleware to quard controller.
   * @return void
   */
    public function __construct()
    {
        $this->middleware('sentinel.auth');
    }
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $user = Employee::where('user_id',Sentinel::getUser()->id)->first();
      $empl = Sentinel::getUser()->employee;
      $permission_dep = array();

      if($empl) {
        $permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
      }
      
      if(isset($user)) {
        
        $user_name = explode('.',strstr($user->email,'@',true));
        if(count($user_name) == 2) {
          $user_name = $user_name[1] . '_' . $user_name[0];
        }else {
          $user_name = $user_name[0];
        }
        $documents = Document::where('path','like','%'.$user_name .'/documents/%')->orWhere('path','like','%svi/documents/%')->get();
     
        $employees = Employee::get();
        
        $path = 'storage/' . $user_name . '/documents/';
        
        if(file_exists($path)){
          $docs = array_diff(scandir($path), array('..', '.', '.gitignore'));
          
        }else {
          $docs = array();
        }
      
        $path2 = 'storage/svi/';
        if(file_exists($path2)){
          $docs2 = array_diff(scandir($path2), array('..', '.', '.gitignore'));
        }else {
          $docs2 = array();
        }
        
        return view('Centaur::documents.index', ['docs' => $docs,'docs2' => $docs2,'documents' => $documents, 'employees' => $employees, 'user_name' => $user_name, 'permission_dep' => $permission_dep]);
      } else {
        $message = session()->flash('error', __('ctrl.path_not_allow'));
        return redirect()->back()->withFlashMessage($message);
      }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		  $employees = Employee::where('checkout',null)->get();
		
		  return view('Centaur::documents.create',['employees' => $employees]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)                      // ne radi sa drag and drop slikama (nema slika u requestu)
    {
      $user = Employee::where('user_id', Sentinel::getUser()->id)->first();
        
        if(isset($request['employee_id'])) {
          if($request['employee_id'] != 'svi') {
            $employee = Employee::where('id',$request->employee_id)->first();
          }
        } else {
          $employee = Employee::where('user_id', Sentinel::getUser()->id)->first();
        }

        if(isset($employee)) {
          $user_name = explode('.',strstr($employee->email,'@',true));
          $user_name = $user_name[1] . '_' . $user_name[0];
        } else {
          $user_name = 'svi';
        }

        $path = 'storage/' . $user_name . '/';
        if (!file_exists($path)) {
          mkdir($path);
        }

          
        if(isset($request['users_interest']) && $request['users_interest'] == true) {
            $path .= 'interest/';
        } elseif(isset($request['profileIMG'])) {
            $path .= "profile_img/";
            
            if(file_exists($path)) {
              $files = glob($path .'*'); // get all file names
              foreach($files as $file){ // iterate files
                if(is_file($file)) {
                  unlink($file); // delete file
                }
              }
            }
        } else {
            $path .= 'documents/';
        }
         
        if (!file_exists($path)) {
          mkdir($path);
        }

        if ($request->hasFile('fileToUpload')) {
          $images = $request->file('fileToUpload');
          
          if (is_array($images)) {
            foreach ($images as $item) {
            //  Storage::putFileAs($path, new File($item), 'photo.jpg');  // snimi u storage folder

              $imageName = $item->getClientOriginalName();
              $imageSize =  $item->getClientSize();         //file size 
              $target_file = $path . $imageName;
              $imageType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));    //file extension 
              
              if($imageType == "exe" || $imageType == "bin") {                             // Allow certain file formats
                return redirect()->back()->with('error', __('ctrl.not_allow'));  
              } 
             
              $item->move($path, $imageName);
              DocumentController::createResizedImage($path . $imageName, $path . pathinfo($imageName)['filename'] . '_small.' . pathinfo($imageName)['extension'], 200, 250 );

              $data = array(
                'employee_id'  	=> $user->id,
                'title'  		    => $imageName,
                'path'  		    => $path,
                'description'   => $request['title']

              );
              $document = new Document();
              $document->saveDocument($data);
            }
          } else {
            
            $docName = $request->file('fileToUpload')->getClientOriginalName();  //file name
            $docSize =  $request->file('fileToUpload')->getClientSize();         //file size 
            $target_file = $path . $docName;
            $docType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));    //file extension 
            
            if (file_exists($target_file)) {                                         // Check if file already exists
              return redirect()->back()->with('error', __('ctrl.file_exists'));
            }
            
            if ($docSize > 5000000) {                                               // Check file size
              return redirect()->back()->with('error',  __('ctrl.file_toolarge'));  
            }
            
            if($docType == "exe" || $docType == "bin") {                             // Allow certain file formats
              return redirect()->back()->with('error', __('ctrl.not_allow'));  
            }
            
            try {
              $request->file('fileToUpload')->move($path, $docName);
              DocumentController::createResizedImage($path . $docName, $path . pathinfo($docName)['filename'] . '_small.' . pathinfo($docName)['extension'], 200, 250);

              $data = array(
                'employee_id'  	=> $user->id,
                'title'  		    => $docName,
                'path'  		    => $path,
                'description'   => $request['title']
              );

              $document = new Document();
              $document->saveDocument($data);

              return redirect()->back()->with('success', __('ctrl.uploaded'));

            } catch (\Throwable $th) {
              return redirect()->back()->with('error',  __('ctrl.not_uploaded')); 
            }
          }
        }
        return redirect()->back()->with('success',  __('ctrl.uploaded')); 
    }

    /**
         * Remove the specified resource from storage.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $document = Document::find($id);
        $link = $document->path . $document->title;
        $link_small = $document->path . pathinfo($link)['filename'] . '_small' . '.' . pathinfo($link)['extension'];
      
        unlink($link);
        unlink($link_small);

        $document->delete();

        $message = session()->flash('success',  __('ctrl.data_delete'));
		
		    return redirect()->back()->withFlashMessage($message);
	  }
  
  // vraća view za upload sa strane edit_user
	public function uploadImage (Request $request) {
  
    $profileIMG = false;
    if(isset( $request['profileIMG']) && $request['profileIMG'] ) {
      $profileIMG = true;
    }
    
		return view('Centaur::documents.upload_image', ['profileIMG' => $profileIMG]);
  }
  
  // stari kod za upload slika (sa w3)
  public static function uploadFile( $fileToUpload, $path)
  {

    $target_file = $path . basename($fileToUpload["name"]); //$target_file specifies the path of the file to be uploaded

    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));  //file extension 

    if (file_exists($target_file)) {                                         // Check if file already exists
      return redirect()->back()->with('error', __('ctrl.file_exists'));  
      $uploadOk = 0;
    }
    
    if ($fileToUpload["size"] > 5000000) {                         // Check file size
      return redirect()->back()->with('error',  __('ctrl.file_toolarge'));  
      $uploadOk = 0;
    }
    
    if($imageFileType == "exe" || $imageFileType == "bin") {                 // Allow certain file formats
      return redirect()->back()->with('error', __('ctrl.not_allow'));  
      $uploadOk = 0;
    }
    
    if ($uploadOk == 0) {                                                 // Check if $uploadOk is set to 0 by an error
      return redirect()->back()->with('error',  __('ctrl.not_uploaded')); 
    
    } else {                                                              // if everything is ok, try to upload file
      if (move_uploaded_file($fileToUpload["tmp_name"], $target_file)) {

        $data = array(
          'employee_id'  	=> $user->id,
          'title'  		=> basename($fileToUpload["name"]),
          'path'  		=> $path
        );
        
        $document = new Document();
        $document->saveDocument($data);
        
        return redirect()->back()->with('success',"The file ". basename( $fileToUpload["name"]).  __('ctrl.has_uploaded'));

      } else {
        return redirect()->back()->with('error',  __('ctrl.file_error')); 
      }
    }
  }

  // promjena veličina slika, snika kao thumbnail - vraća putanju)
  public static function createResizedImage(string $imagePath = '', string $newPath = '', int $newWidth = 0, int $newHeight = 0, string $outExt = 'DEFAULT') 
    {    
      $image_size = getimagesize($imagePath);
      $width = $image_size[0];
      $height = $image_size[1];

      if ($width <  $newWidth &&  $height < $newHeight ) {
        $newWidth = $width;
        $newHeight = $height;
      } else {
        $omjer_w = $width  / $height * 100;
        $omjer_h = $height / $width  * 100;

        if($width < $height) {
          $newWidth = $newHeight * $omjer_w / 100;
        } elseif ($height < $width ) {
          $newHeight = $newWidth *  $omjer_h / 100;
        } else {
          $newHeight = $newWidth;
        }
      }
    
    if (  $newPath === '' or  file_exists($imagePath) === false) {
        return null;
    }

    $type = DocumentController::imagetype($imagePath);

    list($width, $height) = getimagesize($imagePath);

    $outBool = in_array($outExt, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']);
    
      switch (true)
      {
          case  $type === IMAGETYPE_JPEG:
              $image = imagecreatefromjpeg($imagePath);
              if ($outBool === false) $outExt = 'jpg';
              break;
          case $type === IMAGETYPE_PNG:
              $image = imagecreatefrompng($imagePath);
              if ($outBool === false) $outExt = 'png';
              break;
          case $type === IMAGETYPE_GIF:
              $image = imagecreatefromgif($imagePath);
              if ($outBool === false) $outExt = 'gif';
              break;
          case $type === IMAGETYPE_BMP:
              $image = imagecreatefrombmp($imagePath);
              if ($outBool === false) $outExt = 'bmp';
              break;
          case $type === IMAGETYPE_WEBP:
              $image = imagecreatefromwebp($imagePath);
              if ($outBool === false) $outExt = 'webp';
      }
    
      try {
        $newImage = imagecreatetruecolor($newWidth, $newHeight);       
      } catch (\Throwable $th) {
        return null;
      }

      //TRANSPARENT BACKGROUND
      $color = imagecolorallocatealpha($newImage, 0, 0, 0, 127); //fill transparent back
      imagefill($newImage, 0, 0, $color);
      imagesavealpha($newImage, true);

      //ROUTINE
      imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

      if ($height < $width ) {
       // File and rotation      
        $degrees = 270;
        $source = imagecreatefromjpeg($imagePath);
        // Rotate
        $newImage = imagerotate($source, $degrees, 0);
      }
   
        switch (true)
        {
            case in_array($outExt, ['jpg', 'jpeg']) === true: $success = imagejpeg($newImage, $newPath);
                break;
            case $outExt === 'png': $success = imagepng($newImage, $newPath);
                break;
            case $outExt === 'gif': $success = imagegif($newImage, $newPath);
                break;
            case  $outExt === 'bmp': $success = imagebmp($newImage, $newPath);
                break;
            case  $outExt === 'webp': $success = imagewebp($newImage, $newPath);
        }
    
      if ($success === false)
      {
          return null;
      }
      if (file_exists($imagePath)) {
        unlink($imagePath);
      }
    return $newPath;
  }

  public static function imagetype ( $image )
  {
    if ( function_exists( 'exif_imagetype' ) )
      return exif_imagetype( $image);

    $r = getimagesize( $image );
    return $r[2];
  }

}
