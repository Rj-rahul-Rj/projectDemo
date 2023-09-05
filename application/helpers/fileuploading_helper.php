<?php

 function UploadFile($key='',$path='',$thumb='')
 {
   $file = $_FILES;
   $ci =& get_instance();
   if(!$file)
   {
      // return 'Please upload any file';
      return '';
   } 
    else
    {
          $dir ='./uploads/'.$path;
            $dir =  checkDir($dir);

            $config['upload_path']          = $dir;
            $config['allowed_types']        = '*';
            $config['max_size']             = 10024;
            $config['max_width']            = 10024;
            $config['max_height']           = 10024;
            $ci->load->library('upload', $config);
            $ci->upload->initialize($config);

            if (! $ci->upload->do_upload($key))
            {
               //return  $ci->upload->display_errors();  
               return '';
            }
            else
            {
              $fileData  = $ci->upload->data();
              if($thumb)
              {
                 $dir ='uploads/'.$path.'/'.$thumb;
                 $dir =  checkDir($dir);
                $config1 = array(
                'source_image' => $fileData['full_path'],
                'new_image' => $dir,
                'quality' => '100%', 
                'maintain_ratio' => true,
                'width' => 125,
                'height' => 125);

                $ci->load->library('image_lib', $config1);
                $ci->image_lib->initialize($config1);
                // $ci->image_lib->resize(); 
                if($ci->image_lib->resize()){}
                else{
                    // $ci->image_lib->display_errors(); 
                    // die();
                }
              }
                 return $fileData['file_name'];   
            }
    }
 }

 // create new directory if requested not exist 
 function checkDir($dir)
 {
  if (!file_exists($dir)) {
     mkdir($dir, 0777, true);
   }
   return $dir.'/';
 }

 function UploadFiles($key='',$path='',$thumb='')
 {
    $uploaded = [];
    if(!$_FILES || !$_FILES[$key])  //  check file uploaded  or not
    {
      return $uploaded;
    }

      $ci =& get_instance();  // create instance like $this
      

      $dir ='./uploads/'.$path;  // directory for upload
      $dir =  checkDir($dir);    // check directory exist or not 

      $files = $_FILES;          // uploaded files
      $file_count = count($_FILES[$key]['name']); // count files
 
    
      $config = array();                //  start  configration
      $config['upload_path']   = $dir;
      $config['allowed_types'] = '*';
      $config['max_size']      = '0';
      $config['overwrite']     = FALSE;

      $ci->load->library('upload');  // initilize library
      

    for($i=0; $i< $file_count; $i++)  // start uploading one by one
    {           
        $_FILES[$key]['name']    = $files[$key]['name'][$i];
        $_FILES[$key]['type']    = $files[$key]['type'][$i];
        $_FILES[$key]['tmp_name']= $files[$key]['tmp_name'][$i];
        $_FILES[$key]['error']   = $files[$key]['error'][$i];
        $_FILES[$key]['size']    = $files[$key]['size'][$i];    

        $ci->upload->initialize($config);
        if (! $ci->upload->do_upload($key)) //  handle error here else success
        {
          // $uploaded[] =  $ci->upload->display_errors();  
        }
        else
        {
          $fileData  = $ci->upload->data();
          // create thumb if required
          if($thumb)
          {
            $dir ='uploads/'.$path.'/'.$thumb;
            $dir =  ($i==0)?checkDir($dir):$dir;

            $config1['source_image']   = $fileData['full_path'];
            $config1['new_image']      = $dir;
            $config1['quality']        = '100%';
            $config1['maintain_ratio'] = true;
            $config1['width']          = 125;
            $config1['height']         = 125;
            $ci->load->library('image_lib', $config1);
            $ci->image_lib->initialize($config1);
            $ci->image_lib->resize(); 
            // if($ci->image_lib->resize()){}
            // else{
            //   // $uploaded[] = $ci->image_lib->display_errors(); 
            // }
          }

          $uploaded[] = $fileData['file_name'];
        }
    }
    return $uploaded;
 }

 function removeFile($path,$thumb='')
 {
  if(file_exists($path))
  {
   unlink($path);
  }
  if($thumb)
  {
    if(file_exists($thumb))
    {
     unlink($thumb);
    }
  }
 }
 // refrences link
 //https://www.itsolutionstuff.com/post/codeigniter-upload-multiple-file-and-image-exampleexample.html
?>