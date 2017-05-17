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

    #sample implementation
    $phasher = new Phash;
    $phash2 = $phasher->getHash('phash2.jpg', false);
    //this will echo hash in hex, then binary
    echo $phasher->hashAsString($phash2, false).PHP_EOL;
    echo $phasher->hashAsString($phash2).PHP_EOL;

    $phash3 = $phasher->getHash('phash3.jpg', false);
    //this will echo hash in hex, then binary
    echo $phasher->hashAsString($phash3, false).PHP_EOL;
    echo $phasher->hashAsString($phash3).PHP_EOL;

    //using BIT COUNT METHOD FOR SIMILARITY
    echo $phasher->getSimilarity($phash2, $phash3, 'BITS');
    echo PHP_EOL;

    //using HAMMING METHOD (DEFAULT) FOR SIMILARITY
    echo $phasher->getSimilarity($phash2, $phash3);
    echo PHP_EOL;

## Notes

 - The bitcount algorithm for matching similarity may be siginificantly faster than hamming distance.
 - Calculating hamming distance between two hex based hashes may be faster but less accurate than the binary version