<?php

include('phash.php');

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
