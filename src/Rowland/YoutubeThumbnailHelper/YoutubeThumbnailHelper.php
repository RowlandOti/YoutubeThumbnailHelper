<?php namespace Rowland\YoutubeThumbnailHelper;
/**
 * YouTube Thumbnail Enchancer by Hal Gatewood
 *
 * Refactoring by Otieno Rowland for Lravel & Personal Use
 *
 * You can visit the original resource here: https://github.com/halgatewood/youtube-thumbnail-enhancer
 */

 class YoutubeThumbnailHelper {
	/**
	 * boolean var checks if input is urlş
	 *
	 * @var boolean
	 **/
	public $is_url;

	/**
	 * string var handles youtube url or id
	 *
	 * @var string
	 **/
	public $input;

	/**
	 * string var handles play image
	 *
	 * @var string
	 **/
	public $play_image;

	/**
	 * string handles youtube id
	 *
	 * @var string
	 **/
	public $id;


	/**
	 * registers youtube link / id
	 *
	 * @return void
	 * @author Otieno Rowland
	 **/
	public function register($input)
	{
		$this->is_url = false;
		$this->input = trim($input);
		$this->play_image = 'play-mq';

		// ADD HTTP
		if(substr($this->input, 0, 4) == "www."){ $this->input = "http://" . $this->input; $this->is_url = true; }
		if(substr($this->input, 0, 8) == "youtube."){ $this->input = "http://" . $this->input; $this->is_url = true; }
		if(substr($this->input, 0, 8) == "youtu.be"){ $this->input = "http://" . $this->input; $this->is_url = true; }

		// IF URL GET ID
		if(substr($this->input, 0, 7) == "http://" OR substr($this->input, 0, 8) == "https://")
		{	
			$this->is_url = true;
			$this->get_youtube_id_from_url();
		}

		// IF NOT URL TRY ID AS INPUT
		if(!$this->is_url) { $this->id = $this->input; }

		return $this;
	}

	/**
	 * returns image and id if youtube video exists / else returns false.
	 *
	 * @return array
	 * @author Otieno Rowland
	 **/
	public function create_image()
	{
		// CHECK IF YOUTUBE VIDEO
		$handle = curl_init("http://www.youtube.com/watch/?v=" . $this->id);
		curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
		$response = curl_exec($handle);

		// CHECK FOR 404 OR NO RESPONSE
		$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
		if($httpCode == 404 OR !$response) 
		{
			// youtube timed out
			return false;
		}

		curl_close($handle);

		// IF NOT ID GO THROUGH AN ERROR
		if(!$this->id) 
		{
			// youtube id not found
			return false;
		}	

		$filename = $this->id.$this->play_image;
		// IF EXISTS, GO
		if(file_exists("/assets/images/youtube/" . $filename . ".jpg"))
		{
			return "/assets/images/youtube/" . $filename . ".jpg";
		}

		// CREATE IMAGE FROM YOUTUBE THUMB
		$image = imagecreatefromjpeg( "http://img.youtube.com/vi/" . $this->id . "/hq" . "default.jpg" );
		$image_width = imagesx($image);
		$image_height = imagesy($image);

		// ADD THE PLAY ICON
		$play_icon = public_path()."/assets/images/youtube/play/youtube_play-hq.png";
		$logo_image = imagecreatefrompng( $play_icon );

		imagealphablending($logo_image, true);

		$logo_width 	= imagesx($logo_image);
		$logo_height 	= imagesy($logo_image);

		// CENTER PLAY ICON
		$left = round($image_width / 2) - round($logo_width / 2);
		$top = round($image_height / 2) - round($logo_height / 2);

		// CONVERT TO PNG SO WE CAN GET THAT PLAY BUTTON ON THERE
		imagecopy( $image, $logo_image, $left, $top, 0, 0, $logo_width, $logo_height);
		imagepng( $image, $filename .".png", 9);
		// MASHUP FINAL IMAGE AS A JPEG
		$input = imagecreatefrompng($filename .".png");
		$output = imagecreatetruecolor($image_width, $image_height);
		$white = imagecolorallocate($output,  255, 255, 255);
		imagefilledrectangle($output, 0, 0, $image_width, $image_height, $white);
		imagecopy($output, $input, 0, 0, 0, 0, $image_width, $image_height);

		// OUTPUT TO 'i' FOLDER
		imagejpeg($output, public_path()."/assets/images/youtube/". $filename . ".jpg", 95);

		// UNLINK PNG VERSION
		@unlink($filename .".png");

		// return new image
		return "/assets/images/youtube/". $filename . ".jpg";
	}
	

	/**
	 * get youtube id from the slew of youtube urls
	 *
	 * @return boolean
	 * @author Otieno Rowland
	 **/
	public function get_youtube_id_from_url()
	{
		$pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i';
		preg_match($pattern, $this->input, $matches);
		$this->id = isset($matches[1]) ? $matches[1] : false;
	}
}