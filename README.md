Phash
=====

Perceptual hash implementation for PHP

This is my PHP Implementation of the AverageHash algorithm to create Perceptual Hashes

A perceptual hash is different from a typical hash as it allows you to compute a signature based
on the visual features of an image rather than the actual data they contain as with a cryptographic
hash. This allows you to use the Perceptual Hash for simple image matching which could prove useful
in finding duplicate pictures or picture tagging.

To get a better understanding of this project see: 
http://www.hackerfactor.com/blog/index.php?/archives/432-Looks-Like-It.html

***

### Sample Usage ###
You can use pHash methods as a class in your project and extend as you wish.

A basic implementation is as follows:

    $hasher = new Phash();
    //initialize input files of both pictures 
    $input_file = '/path/to/mypicture.jpg';
    $second_file = '/path/to/mysecondpicture.jpg';
    //get hash of both files
    $hash1 = $hasher->getHash($input_file);
    $hash2 = $hasher->getHash($second_file);
    //compute similarity
    $similarity = $hasher->getSimilarity($hash1, $hash2);
    echo $similarity;

## Notes
We use the bitcount algorithm for matching similarity (calculating the distance between the hashes). This should be siginificantly faster than using hamming distance.
