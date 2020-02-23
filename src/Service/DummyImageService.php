<?php

namespace App\Service;

use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DummyImageService
{
    private $base_path = null;
    private $format = null;
    private $output = null;
    public const ONE_PIXEL_DUMMY = 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
    
    public function __construct(ParameterBagInterface $params)
    {
        $this->base_path = $params->get('kernel.project_dir').'/'.ltrim($params->get('web_root','public'),' /');
    }
    
    
    /**
     * @param string $buffer
     * @return string
     */
    private static function process_output_buffer($buffer = '')
    {
        $buffer = trim($buffer);
        if (strlen($buffer) == 0) {
            return '';
        }
        return $buffer;
    }
    
    /**
     * @param string $dimensions
     * @param string $format
     * @param string $bg_color
     * @param string $fg_color
     * @param string $text
     * @return $this
     */
    
    public function generate($dimensions = null, $format = null, $bg_color = null): DummyImageService
    {
        $dimensions = $dimensions ?? '800x600';
        $this->format = $format ?? 'png';
        $bg_color = $bg_color ?? 'ffffff';
        $background = new ColorService();
        $background->set_hex($bg_color);
        // Find the image dimensions
        if (substr_count($dimensions, ':') > 1) {
            throw new InvalidArgumentException('Too many colons in the dimension paramter! There should be 1 at most.');
        }
        if (strstr($dimensions, ':') && !strstr($dimensions, 'x')) {
            throw new InvalidArgumentException('To calculate a ratio you need to provide a height!');
        }
        $dimensions = explode('x', $dimensions);
        $width = preg_replace('/[^\d:\.]/i', '', $dimensions[0]);
        $height = $width;
        if ($dimensions[1] ?? false) {
            $height = preg_replace('/[^\d:\.]/i', '', $dimensions[1]);
        }
        if ($width < 1 || $height < 1) {
            throw new InvalidArgumentException('Too smal image dimensions!');
        }
        // If one of the dimensions has a colon in it, we can calculate the aspect ratio. Chances are the height will contain a ratio, so we'll check that first.
        if (preg_match('/:/', $height)) {
            $ratio = explode(':', $height);
            // If we only have one ratio value, set the other value to the same value of the first making it a ratio of 1
            if (!$ratio[1]) {
                $ratio[1] = $ratio[0];
            }
            if (!$ratio[0]) {
                $ratio[0] = $ratio[1];
            }
            $height = ($width * $ratio[1]) / $ratio[0];
        } else if (preg_match('/:/', $width)) {
            $ratio = explode(':', $width);
            //If we only have one ratio value, set the other value to the same value of the first making it a ratio of 1
            if (!$ratio[1]) {
                $ratio[1] = $ratio[0];
            }
            if (!$ratio[0]) {
                $ratio[0] = $ratio[1];
            }
            $width = ($height * $ratio[0]) / $ratio[1];
        }
        //Limit the size of the image to no more than an area of 16,000,000
        $area = $width * $height;
        if ($area >= 16000000 || $width > 9999 || $height > 9999) {
            throw new InvalidArgumentException('Too big image dimensions!');
        }
        //Let's round the dimensions to 3 decimal places for aesthetics
        $width = round($width, 3);
        $height = round($height, 3);
        
        $img = imageCreate($width, $height);
        $bg_color = imageColorAllocate($img, $background->get_rgb('r'), $background->get_rgb('g'), $background->get_rgb('b'));
        //Create the rectangle with the specified background color
        imageFilledRectangle($img, 0, 0, $width, $height, $bg_color);
        
        // Start output buffering so we can determine the Content-Length of the file
        ob_start(['self', 'process_output_buffer']);
        // Create the final image based on the provided file format.
        switch ($this->format) {
            case 'gif':
                imagegif($img);
                break;
            case 'jpg':
            case 'jpeg':
                imagejpeg($img);
                break;
            default:
                $this->format = 'png';
                imagepng($img);
                break;
        }
        $this->output = ob_get_contents();
        ob_end_clean();
        return $this;
    }
    
    /**
     * Return image as base64 string
     *
     * @return string
     */
    public function toBase64()
    {
        if (empty($this->output)) {
            return $this->generate()->toBase64();
        }
        return 'data:image/' . $this->format . ';base64,' . base64_encode($this->output);
    }
    
    public function generateBase64FitToFile($file_uri)
    {
        $file_full_path = $this->base_path.'/'.ltrim($file_uri,'/ ');
        if ( ! file_exists($file_full_path)) {
            return self::ONE_PIXEL_DUMMY;
        }
        try{
            list($width, $height) = getimagesize($file_full_path);
            if (!$width || !$height) {
                return self::ONE_PIXEL_DUMMY;
            }
            $dimensions = $width.'x'.$height;
            return $this->generate($dimensions)->toBase64();
        } catch (\Exception $e){
            return self::ONE_PIXEL_DUMMY;
        }
        
    }
    
    
}