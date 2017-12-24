<?php
/*
 * jQuery File Upload Plugin PHP Class 5.9.1
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

class UploadHandler
{
	var $_db;

	var $chpu;
	var $id_transport;
	var $hid;
	var $alt_text;
	var $foto_rel_dir;

	protected $options;

    function __construct($options=null) {
		$this->_db = &getDBInstance();

		$this->alt_text = $_REQUEST['alt_text'];

		$this->foto_rel_dir = 'fotogr/'.date("Y_m");
		$fotodir = RESOURCE_PATH . '/' . $this->foto_rel_dir . '/';
		$foto_upload_url = SITE_URL . '/resources/'.$this->foto_rel_dir.'/';

		if (!is_dir($fotodir))
			{
			mkdir($fotodir);
			@chmod($fotodir, 0777);
			}

        $this->options = array(
            'script_url' => $this->getFullUrl().'/',
            'upload_dir' => $fotodir,
            'upload_url' => $foto_upload_url,
            'param_name' => 'files',
            // Set the following option to 'POST', if your server does not support
            // DELETE requests. This is a parameter sent to the client:
            'delete_type' => 'DELETE',
            // The php.ini settings upload_max_filesize and post_max_size
            // take precedence over the following max_file_size setting:
            'max_file_size' => null,
            'min_file_size' => 1,
            'accept_file_types' => '/.+$/i',
            'max_number_of_files' => null,
        	'watermark' => RESOURCE_PATH . '/watermark.png',
            // Set the following option to false to enable resumable uploads:
            'discard_aborted_uploads' => true,
            // Set to true to rotate images based on EXIF meta data, if available:
            'orient_image' => false,
            'image_versions' => array(
                // Uncomment the following version to restrict the size of
                // uploaded images. You can also add additional versions with
                // their own upload directories:

                'image' => array(
                    'upload_dir' => $fotodir,
                    'upload_url' => $foto_upload_url,
                    'max_width' => 230,
                    'max_height' => 155,
        			'watermark' => false,
                    'jpeg_quality' => 95,
                    'preview' => true
                ),
                'thumb_image' => array(
                    'upload_dir' => $fotodir,
                    'upload_url' => $foto_upload_url,
                    'max_width' => 80,
                    'max_height' => 80,
        			'watermark' => false,
        			'thumbnail' => true
                )
			)
        );
        if ($options) {
            $this->options = array_replace_recursive($this->options, $options);
        }
    }

	protected function resolveThumbName($name, $thumb)
		{
		$name_array = explode('.',$name);
		$ext = $name_array[count($name_array) - 1];
		array_pop($name_array);
		return implode('.',$name_array).'_'.$thumb.'.'.$ext;
		}

    protected function getFullUrl() {
      	return
    		(isset($_SERVER['HTTPS']) ? 'https://' : 'http://').
    		(isset($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'].'@' : '').
    		(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ($_SERVER['SERVER_NAME'].
    		(isset($_SERVER['HTTPS']) && $_SERVER['SERVER_PORT'] === 443 ||
    		$_SERVER['SERVER_PORT'] === 80 ? '' : ':'.$_SERVER['SERVER_PORT']))).
    		substr($_SERVER['SCRIPT_NAME'],0, strrpos($_SERVER['SCRIPT_NAME'], '/'));
    }

    protected function set_file_delete_url($file) {
        $file->delete_url = $this->options['script_url']
            .'index.php?file=/resources/'.$this->foto_rel_dir.'/'.rawurlencode($file->name);
        $file->delete_type = $this->options['delete_type'];
        if ($file->delete_type !== 'DELETE') {
        $file->delete_url .= '&_method=DELETE';
        }
    }

    protected function get_file_object($file_name) {
        $file_path = $this->options['upload_dir'].$file_name;
        if (is_file($file_path) && $file_name[0] !== '.') {
            $file = new stdClass();
            $file->name = $file_name;
            $file->size = filesize($file_path);
            $file->url = $this->options['upload_url'].rawurlencode($file->name);
            foreach($this->options['image_versions'] as $version => $options) {
            	$thumb_file_name = $this->resolveThumbName($file_name, $version);
                if (is_file($options['upload_dir'].$thumb_file_name)) {
                    $file->{$version.'_url'} = $options['upload_url']
                        .rawurlencode($thumb_file_name);
                }
            }
            $this->set_file_delete_url($file);
            return $file;
        }
        return null;
    }

    protected function get_file_objects($id_album) {
    	$photos = $this->_db->get_all("SELECT * FROM " . CFG_DBTBL_MOD_PHOTO . " WHERE id_album = ? ORDER BY pos ASC", intval($id_album));
    	if (is_array($photos)) {
    		foreach ($photos as $photo) {
    			$mas_photos[] = array(
    				'id' => $photo['id'],
					'name' => $photo['alt_text'],
    				'size' => filesize(BASE_PATH . $photo['path']),
    				'url' =>  $photo['path'],
    				'normal_url' => $photo['path'],
    				'thumbnail_url' => $photo['tmb_path'],
    				'delete_url' => $this->options['script_url'].'index.php?file=' . $photo['path_orig'],
    				'delete_type' => 'DELETE'
    			);
    		}
    	}
    	//$mas_photos = array();
    	return $mas_photos;
    }

    protected function create_scaled_image($file_name, $thumb_file_name, $options) {
        $file_path = $this->options['upload_dir'].$file_name;
        $new_file_path = $options['upload_dir'].$thumb_file_name;
        list($img_width, $img_height) = @getimagesize($file_path);
        if (!$img_width || !$img_height) {
            return false;
        }
        $scale = min(
            $options['max_width'] / $img_width,
            $options['max_height'] / $img_height
        );
        if ($scale >= 1) {
            if ($file_path !== $new_file_path) {
                return copy($file_path, $new_file_path);
            }
            return true;
        }
        $new_width = $img_width * $scale;
        $new_height = $img_height * $scale;
        $new_img = @imagecreatetruecolor($new_width, $new_height);

		$znak_hw = getimagesize($this->options['watermark']);
		$znak = imagecreatefrompng($this->options['watermark']);

        switch (strtolower(substr(strrchr($file_name, '.'), 1))) {
            case 'jpg':
            case 'jpeg':
            	$src_img = @imagecreatefromjpeg($file_path);

            	$write_image = 'imagejpeg';
                $image_quality = isset($options['jpeg_quality']) ?
                    $options['jpeg_quality'] : 75;
                break;
            case 'gif':
                @imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
                $src_img = @imagecreatefromgif($file_path);
                $write_image = 'imagegif';
                $image_quality = null;
                break;
            case 'png':
                @imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
                @imagealphablending($new_img, false);
                @imagesavealpha($new_img, true);
                $src_img = @imagecreatefrompng($file_path);
                $write_image = 'imagepng';
                $image_quality = isset($options['png_quality']) ?
                    $options['png_quality'] : 9;
                break;
            default:
                $src_img = null;
        }
        $success = $src_img && @imagecopyresampled(
            $new_img,
            $src_img,
            0, 0, 0, 0,
            $new_width,
            $new_height,
            $img_width,
            $img_height
        );
        if ($success && $options['watermark']) {
			$sx = imageSX($new_img);
			$sy = imageSY($new_img);

			$im2 = imagecreatetruecolor($sx, $sy);
			imagesavealpha($im2, true);
			$transparent = imagecolorallocatealpha($im2, 0, 0, 0, 127);
			imagefill($im2, 0, 0, $transparent);
			imageCopy($im2, $new_img, 0, 0, 0, 0, $sx, $sy);
			imageCopy($new_img, $im2, 0, 0, 0, 0, $sx, $sy);
			imagecopy($new_img, $znak, 1, 1, 0, 0, $znak_hw[0], $znak_hw[1]);
        }

		$write_image($new_img, $new_file_path, $image_quality);

        // Free up memory (imagedestroy does not delete files):
        @imagedestroy($src_img);
        @imagedestroy($new_img);
        return $success;
    }

    protected function has_error($uploaded_file, $file, $error) {
        if ($error) {
            return $error;
        }
        if (!preg_match($this->options['accept_file_types'], $file->name)) {
            return 'acceptFileTypes';
        }
        if ($uploaded_file && is_uploaded_file($uploaded_file)) {
            $file_size = filesize($uploaded_file);
        } else {
            $file_size = $_SERVER['CONTENT_LENGTH'];
        }
        if ($this->options['max_file_size'] && (
                $file_size > $this->options['max_file_size'] ||
                $file->size > $this->options['max_file_size'])
            ) {
            return 'maxFileSize';
        }
        if ($this->options['min_file_size'] &&
            $file_size < $this->options['min_file_size']) {
            return 'minFileSize';
        }
        if (is_int($this->options['max_number_of_files']) && (
                count($this->get_file_objects()) >= $this->options['max_number_of_files'])
            ) {
            return 'maxNumberOfFiles';
        }
        return $error;
    }

    protected function upcount_name_callback($matches) {
        $index = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
        $ext = isset($matches[2]) ? $matches[2] : '';
        return ' ('.$index.')'.$ext;
    }

    protected function upcount_name($name) {
        return preg_replace_callback(
            '/(?:(?: \(([\d]+)\))?(\.[^.]+))?$/',
            array($this, 'upcount_name_callback'),
            $name,
            1
        );
    }

    protected function trim_file_name($name, $type, $temp = false) {
        // Remove path information and dots around the filename, to prevent uploading
        // into different directories or replacing hidden system files.
        // Also remove control characters and spaces (\x00..\x20) around the filename:
        $new_name = substr(md5(uniqid(rand())), 0, 15);
        if ($temp) {
        	$new_name .= '_temp';
        }
        $file_name = trim(basename(stripslashes($name)), ".\x00..\x20");

		$file_info = pathinfo($file_name);
		$extension = strtolower($file_info['extension']);
		$new_name = strtolower($new_name . '.' . $extension);

        // Add missing file extension for known image types:
        if (strpos($file_name, '.') === false &&
            preg_match('/^image\/(gif|jpe?g|png)/', $type, $matches)) {
            $file_name .= '.'.$matches[1];
        }
        if ($this->options['discard_aborted_uploads']) {
            while(is_file($this->options['upload_dir'].$file_name)) {
                $file_name = $this->upcount_name($file_name);
            }
        }
        return $new_name;
    }

    protected function orient_image($file_path) {
      	$exif = exif_read_data($file_path);
      	$orientation = intval(@$exif['Orientation']);
      	if (!in_array($orientation, array(3, 6, 8))) {
      	    return false;
      	}
      	$image = @imagecreatefromjpeg($file_path);
      	switch ($orientation) {
        	  case 3:
          	    $image = @imagerotate($image, 180, 0);
          	    break;
        	  case 6:
          	    $image = @imagerotate($image, 270, 0);
          	    break;
        	  case 8:
          	    $image = @imagerotate($image, 90, 0);
          	    break;
          	default:
          	    return false;
      	}
      	$success = imagejpeg($image, $file_path);
      	// Free up memory (imagedestroy does not delete files):
      	@imagedestroy($image);
      	return $success;
    }

    protected function handle_file_upload($uploaded_file, $name, $size, $type, $error) {
        $file = new stdClass();
        $file->name = $this->trim_file_name($name, $type);
        $file_name = $file->name;
        $file->name = $this->resolveThumbName($file->name, 'orig');

        $file->name_temp = $this->trim_file_name($name, $type, true);
        $file->size = intval($size);
        $file->type = $type;
        $error = $this->has_error($uploaded_file, $file, $error);
        if (!$error && $file->name) {
            $file_path = $this->options['upload_dir'].$file->name;
            $append_file = !$this->options['discard_aborted_uploads'] &&
                is_file($file_path) && $file->size > filesize($file_path);
            clearstatcache();
            if ($uploaded_file && is_uploaded_file($uploaded_file)) {
                // multipart/formdata uploads (POST method uploads)
                if ($append_file) {
                    file_put_contents(
                        $file_path,
                        fopen($uploaded_file, 'r'),
                        FILE_APPEND
                    );
                } else {
                    move_uploaded_file($uploaded_file, $file_path);
                }
            } else {
                // Non-multipart uploads (PUT method support)
                file_put_contents(
                    $file_path,
                    fopen('php://input', 'r'),
                    $append_file ? FILE_APPEND : 0
                );
            }
            $file_size = filesize($file_path);
            if ($file_size === $file->size) {
            	if ($this->options['orient_image']) {
            		$this->orient_image($file_path);
            	}
                $file->url = $this->options['upload_url'].rawurlencode($file->name);
                foreach($this->options['image_versions'] as $version => $options) {
                	$thumb_file_name = $this->resolveThumbName($file_name, $version);
                	//echo $thumb_file_name; exit;
                    if ($this->create_scaled_image($file->name, $thumb_file_name, $options)) {
                        if ($this->options['upload_dir'] !== $options['upload_dir']) {
                            $file->{$version.'_url'} = $options['upload_url']
                                .rawurlencode($file->name);
                        } else {
                            clearstatcache();
                            //$file_size = filesize($file_path);
                        }
                    	if($options['thumbnail'])
                    		{
                    		$file->thumbnail_url = $this->options['upload_url'] . rawurlencode($thumb_file_name);
                    		}
                    	if($options['preview'])
                    		{
                    		$file->url = $this->options['upload_url'] . rawurlencode($thumb_file_name);
                    		}
						$file->{$version.'_path'} = '/resources/'.$this->foto_rel_dir.'/'.$thumb_file_name;
					}
                }

                // успешно записали картинки - теперь запишем в базу данных
                $id_album = intval($_REQUEST['id_album']);

                $this->alt_text = stripslashes($this->alt_text[$name]);

                $max_pos = (int) $this->_db->get_one("SELECT MAX(pos) FROM " . CFG_DBTBL_MOD_PHOTO . " WHERE id_album = ?", $id_album);

                $this->_db->query("INSERT INTO " . CFG_DBTBL_MOD_PHOTO . "
                		SET id_album = ?
                		  , path = ?
                		  , tmb_path = ?
                		  , is_privat = 0
                		  , is_system = 0
                		  , pos = ?
                		  , path_orig = ?
                		  , alt_text = ?
                		"
                		, $id_album
                		, $file->image_path
                		, $file->thumb_image_path
                		, $max_pos + 1
                		, '/resources/'.$this->foto_rel_dir.'/' . $file->name
                		, $this->alt_text);
                $file->id = $this->_db->insert_id;
            } else if ($this->options['discard_aborted_uploads']) {
                unlink($file_path);
                $file->error = 'abort';
            }
            $file->size = $file_size;
            $this->set_file_delete_url($file);
        } else {
            $file->error = $error;
        }
        $file->name = $this->alt_text;
        return $file;
    }

    public function get() {
    	$id_album = intval($_REQUEST['id_album']);
        $file_name = isset($_REQUEST['file']) ?
            basename(stripslashes($_REQUEST['file'])) : null;
        if ($file_name) {
            $info = $this->get_file_object($file_name);
        } else {
            $info = $this->get_file_objects($id_album);
        }
        header('Content-type: application/json');
        echo json_encode($info);
    }

    public function post() {
        if (isset($_REQUEST['_method']) && $_REQUEST['_method'] === 'DELETE') {
            return $this->delete();
        }
        $upload = isset($_FILES[$this->options['param_name']]) ?
            $_FILES[$this->options['param_name']] : null;
        $info = array();
        if ($upload && is_array($upload['tmp_name'])) {
            // param_name is an array identifier like "files[]",
            // $_FILES is a multi-dimensional array:
            foreach ($upload['tmp_name'] as $index => $value) {
                $info[] = $this->handle_file_upload(
                    $upload['tmp_name'][$index],
                    isset($_SERVER['HTTP_X_FILE_NAME']) ?
                        $_SERVER['HTTP_X_FILE_NAME'] : $upload['name'][$index],
                    isset($_SERVER['HTTP_X_FILE_SIZE']) ?
                        $_SERVER['HTTP_X_FILE_SIZE'] : $upload['size'][$index],
                    isset($_SERVER['HTTP_X_FILE_TYPE']) ?
                        $_SERVER['HTTP_X_FILE_TYPE'] : $upload['type'][$index],
                    $upload['error'][$index]
                );
            }
        } elseif ($upload || isset($_SERVER['HTTP_X_FILE_NAME'])) {
            // param_name is a single object identifier like "file",
            // $_FILES is a one-dimensional array:
            $info[] = $this->handle_file_upload(
                isset($upload['tmp_name']) ? $upload['tmp_name'] : null,
                isset($_SERVER['HTTP_X_FILE_NAME']) ?
                    $_SERVER['HTTP_X_FILE_NAME'] : (isset($upload['name']) ?
                        $upload['name'] : null),
                isset($_SERVER['HTTP_X_FILE_SIZE']) ?
                    $_SERVER['HTTP_X_FILE_SIZE'] : (isset($upload['size']) ?
                        $upload['size'] : null),
                isset($_SERVER['HTTP_X_FILE_TYPE']) ?
                    $_SERVER['HTTP_X_FILE_TYPE'] : (isset($upload['type']) ?
                        $upload['type'] : null),
                isset($upload['error']) ? $upload['error'] : null
            );
        }
        header('Vary: Accept');
        $json = json_encode($info);
        $redirect = isset($_REQUEST['redirect']) ?
            stripslashes($_REQUEST['redirect']) : null;
        if ($redirect) {
            header('Location: '.sprintf($redirect, rawurlencode($json)));
            return;
        }
        if (isset($_SERVER['HTTP_ACCEPT']) &&
            (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
            header('Content-type: application/json');
        } else {
            header('Content-type: text/plain');
        }
        echo $json;
    }

    public function delete() {
        $file_name = isset($_REQUEST['file']) ? $_REQUEST['file'] : 0;
        echo $file_name;
        if ($file_name) {
        	$photo_row = $this->_db->get_row("SELECT * FROM " . CFG_DBTBL_MOD_PHOTO . " WHERE path_orig = ?", $file_name);

            if (is_file(BASE_PATH . $photo_row['path_orig'])) {
	        	unlink(BASE_PATH . $photo_row['path_orig']);
            }

            if (is_file(BASE_PATH . $photo_row['path'])) {
	        	unlink(BASE_PATH . $photo_row['path']);
            }
            if (is_file(BASE_PATH . $photo_row['tmb_path'])) {
	        	unlink(BASE_PATH . $photo_row['tmb_path']);
            }
        	$this->_db->query("DELETE FROM " . CFG_DBTBL_MOD_PHOTO . " WHERE path_orig = ?", $file_name);
        }
        header('Content-type: application/json');
        echo json_encode(true);
    }

}
