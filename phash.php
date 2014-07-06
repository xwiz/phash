<?php

/**
 * |----------------------------------------------------------------------
 * | 
 * |----------------------------------------------------------------------
*/
class Phash{
    
    protected $bitCounts = array(0,1,1,2,1,2,2,3,1,2,2,3,2,3,3,4,1,2,2,3,
        2,3,3,4,2,3,3,4,3,4,4,5,1,2,2,3,2,3,3,4,2,3,3,4,3,4,4,5,2,3,3,
        4,3,4,4,5,3,4,4,5,4,5,5,6,1,2,2,3,2,3,3,4,2,3,3,4,3,4,4,5,2,3,
        3,4,3,4,4,5,3,4,4,5,4,5,5,6,2,3,3,4,3,4,4,5,3,4,4,5,4,5,5,6,3,
        4,4,5,4,5,5,6,4,5,5,6,5,6,6,7,1,2,2,3,2,3,3,4,2,3,3,4,3,4,4,5,
        2,3,3,4,3,4,4,5,3,4,4,5,4,5,5,6,2,3,3,4,3,4,4,5,3,4,4,5,4,5,5,
        6,3,4,4,5,4,5,5,6,4,5,5,6,5,6,6,7,2,3,3,4,3,4,4,5,3,4,4,5,4,5,
        5,6,3,4,4,5,4,5,5,6,4,5,5,6,5,6,6,7,3,4,4,5,4,5,5,6,4,5,5,6,5,
        6,6,7,4,5,5,6,5,6,6,7,5,6,6,7,6,7,7,8);


    function bitCount($num)
    {
        $num += 0;
        $count = 0;
        for (; $num > 0; $num >>= 8) $count += $this->bitCounts[($num & 0xff)];
        return $count;
    }
    
    /**
    * |---------------------------------------------------------------------
    * | Returns a percentage similarity using the bitCount method.
    * | This should be similar to but faster than hamming distance
    * |---------------------------------------------------------------------
    * @return int percentage similarity
    */
    public function getSimilarity($hash1, $hash2)
    {
        $hash1 += 0; $hash2 += 0; //convert to float
        $result = ((64 - $this->bitCount($hash1 ^ $hash2)) * 100) / 64.0;
        return (int)$result;
    }

    /**
     * |---------------------------------------------------------------------
     * | PHP implementation of the AverageHash algorithm for dct based phash
     * | Accepts PNG or JPEG images
     * |---------------------------------------------------------------------
     * @param string full path to the file
     * @return computed hash  
     */
    public function getHash($filepath)
    {
        $scale = 8;//todo, allow scale specification
        try
        {
            $img = imagecreatefrompng($filepath);
        }
        catch (exception $e)
        {
            try
            {
                $img = imagecreatefromjpeg($filepath);
            }
            catch (exception $f)
            {
                return 'Image could not be processed. Only JPG/PNG supported';
            }
        }
        $averageValue = 0;
        for ($y = 0; $y < $scale; $y++)
        {
            for ($x = 0; $x < $scale; $x++)
            {
                // get the rgb value for current pixel
                $rgb = ImageColorAt($img, $x, $y);
                // extract each value for r, g, b
                $red = ($rgb & 0xFF0000) >> 16;
                $green = ($rgb & 0x00FF00) >> 8;
                $blue = ($rgb & 0x0000FF);
                $gray = $red + $blue + $green;
                $gray /= 12;
                $gray = floor($gray);
                $grayscale[$x + ($y * $scale)] = $gray;
                $averageValue += $gray;
            }
        }
        $averageValue /= ($scale * $scale);
        $averageValue = floor($averageValue);
        $hash = 0;

        $phash = array();
        for ($i = 0; $i < ($scale * $scale); $i++)
        {
            $rgb = $grayscale[$i];
            if ($rgb >= $averageValue)
            {
                $this->leftShift($phash, 1, (63 - $i));
            }
        }
        $p1 = $this->bin2dec($phash);
        $pluscarry = $this->string_add($p1, "1");
        return $pluscarry;
    }
    
    /**
    * |----------------------------------------------------------------
    * | Performs a left shift on the supplied binary array
    * |----------------------------------------------------------------
    * @param array binary array to perform shift on
    * @param int integer value to shift
    * @param int amount of places to left shift
    */
    function leftShift(&$bin, $val, $places)
    {
        if ($places < 1) return;
        if (count($bin) < $places)
        {
            $bin = array_pad($bin, $places + 1, 0);
        }
        $bin[count($bin) - $places - 1] = $val;
    }
    
    /**
    * |-----------------------------------------------------------------
    * | Converts supplied bin array to decimal
    * |-----------------------------------------------------------------
    * @param array supplied binary array
    * @return int converted decimal
    */
    function bin2dec($bin)
    {
        $length = count($bin);
        $sum = 0;
        //convert using doubling
        for ($i = 0; $i < $length; $i++)
        {
            //use string_add if doubling bigger than int32
            if ($i >= 16)
            {
                $sum = $this->string_add("$sum", "$sum");
                $cr = $bin[$i];
                if ($cr != 0)
                {
                    $sum = $this->string_add($sum, "$cr");
                }
            }
            else
            {
                $sum += $sum + $bin[$i];
            }
        }
        return $sum;
    }
    
    /**
    * |-----------------------------------------------------------------
    * | Adds any two decimals regardless of their length to bypass int
    * | limitations in PHP
    * |-----------------------------------------------------------------
    * @param int number 1
    * @param int number 2
    * @return string result
    */
    function string_add($a, $b)
    {
        $lena = strlen($a);
        $lenb = strlen($b);
        if ($lena == $lenb)
        {
            $len = $lena - 1; //any
        }
        else
            if ($lena > $lenb)
            {
                $b = str_pad($b, $lena, "0", STR_PAD_LEFT);
                $len = $lena - 1;
            }
            else
                if ($lenb > $lena)
                {
                    $a = str_pad($a, $lenb, "0", STR_PAD_RIGHT);
                    $len = $lenb - 1;
                }
        $result = "";
        for ($i = $len, $carry = 0; $i >= 0 || $carry != 0; $i--)
        {
            $add1 = $i < 0 ? 0 : $a[$i];
            $add2 = $i < 0 ? 0 : $b[$i];
            $add = $add1 + $add2 + $carry;
            if ($add > 9)
            {
                $carry = 1;
                $add -= 10;
            }
            else
            {
                $carry = 0;
            }
            $result .= $add;
        }
        return strrev($result);
    }
}
?>