Phash
=====

Perpetual hash implementation for PHP

This is my PHP Implementation of the simple AverageHash implementation based on Perpetual Hash

A perpetual hash is different from a typical hash as it allows you to compute a signature based
on the visual features of an image rather than the actual data they contain as with a cryptographic
hash. This allows you to use the Perpetual Hash for simple image matching which could prove useful
in finding duplicate pictures or picture tagging.

To get a better understanding of this project see: 
http://www.hackerfactor.com/blog/index.php?/archives/432-Looks-Like-It.html

The methods are quite simple to understand and can be easily implemented on Laravel.

***

###Sample Usage###
You can use pHash methods in your class to modify to your specs or as a library
 
To use as a library in Laravel, simply copy to your laravel library path and 'dump autoload'

After this, you can instantiate and use as follows:

    $hasher = new Phash();
    //initialize input files of both pictures 
    $input_file = '/path/to/mypicture.jpg';
    $second_file = '/path/to/mysecondpicture.jpg';
    //get hash of both files
    $hash1 = $hasher->getHash($input_file);
    $hash2 = $hasher->getHash($second_file);
    //computer similarity
    $similarity = $hasher->getSimilarity($hash1, $hash2); 