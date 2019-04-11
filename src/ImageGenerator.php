<?php
namespace WymarzonyLogin\Fingerprintah;

class ImageGenerator
{
    private $mapWidth;
    private $mapHeight;
    private $mirrorHorizontal;
    private $mirrorVertical;
    private $imageWidth;
    private $imageHeight;
    private $scale;
    
    public function __construct($scale = 2)
    {
        $this->scale = $scale;
        $this->mapWidth = 4;
        $this->mapHeight = 4;
        $this->mirrorHorizontal = true;
        $this->mirrorVertical = true;
        $this->imageWidth = $this->mapWidth * $this->scale * pow(2, (int) $this->mirrorHorizontal);
        $this->imageHeight = $this->mapHeight * $this->scale * pow(2, (int) $this->mirrorVertical);
    }
    
    public function getPngImage(string $inputText)
    {
        $imageData= $this->getGdImageData($inputText);
        
        header('Content-Type: image/png');
        imagepng($imageData);
    }
    
    public function getPngImageBase64Data(string $inputText)
    {
        $imageData = $this->getGdImageData($inputText);
        
        ob_start();
        imagepng($imageData);
        $outputBuffer = ob_get_clean();
        $base64data = base64_encode($outputBuffer);
        imagedestroy($imageData);
        
        return 'data:image/png;base64,'.$base64data;
    }
    
    private function getGdImageData(string $inputText)
    {
        $input = md5($inputText);
        
        $backgroundColorCode = substr($input, 0, 6);
        $backgroundColorFactorRed = base_convert(substr($backgroundColorCode, 0, 2), 16, 10);
        $backgroundColorFactorGreen = base_convert(substr($backgroundColorCode, 2, 2), 16, 10);
        $backgroundColorFactorBlue = base_convert(substr($backgroundColorCode, 4, 2), 16, 10);
        
        $foregroundColorCode = substr($input, 6, 6);
        $foregroundColorFactorRed = base_convert(substr($foregroundColorCode, 0, 2), 16, 10);
        $foregroundColorFactorGreen = base_convert(substr($foregroundColorCode, 2, 2), 16, 10);
        $foregroundColorFactorBlue = base_convert(substr($foregroundColorCode, 4, 2), 16, 10);
        
        $pixelMap = base_convert(substr($input, 12, 4), 16, 10);
        
        $image = imagecreatetruecolor($this->imageWidth, $this->imageHeight);
        $backgroundColor = imagecolorallocate($image, $backgroundColorFactorRed, $backgroundColorFactorGreen, $backgroundColorFactorBlue);
        $foregroundColor = imagecolorallocate($image, $foregroundColorFactorRed, $foregroundColorFactorGreen, $foregroundColorFactorBlue);
        
        imagefilledrectangle ($image , 0, 0, $this->imageWidth - 1, $this->imageHeight - 1, $backgroundColor);
        
        for ($y = 0; $y < $this->mapHeight; $y++) {
            for ($x = 0; $x < $this->mapWidth; $x++) {    
                $pixelMapIndex = $y * $this->mapHeight + $x;
                
                if (($pixelMap >> $pixelMapIndex) & 1) {
                    imagefilledrectangle($image, $x * $this->scale, $y * $this->scale, ($x + 1) * $this->scale, ($y + 1) * $this->scale, $foregroundColor);
                    
                    if ($this->mirrorHorizontal && $this->mirrorVertical) {
                        imagefilledrectangle($image, (2 * $this->mapWidth - $x) * $this->scale,  (2 * $this->mapHeight - $y) * $this->scale, ((2 * $this->mapWidth - $x) - 1) * $this->scale, ((2 * $this->mapHeight - $y) - 1)  * $this->scale, $foregroundColor);
                    }
                    
                    if ($this->mirrorHorizontal) {
                        imagefilledrectangle($image, (2 * $this->mapWidth - $x) * $this->scale, $y * $this->scale, ((2 * $this->mapWidth - $x) - 1) * $this->scale, ($y + 1) * $this->scale, $foregroundColor);    
                    }

                    if ($this->mirrorVertical) {
                        imagefilledrectangle($image, $x * $this->scale, (2 * $this->mapHeight - $y) * $this->scale, ($x + 1) * $this->scale, ((2 * $this->mapHeight - $y) - 1) * $this->scale, $foregroundColor);
                    }
                }
            }
        }
        
        return $image;
    }
}
