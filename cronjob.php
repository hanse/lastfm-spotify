#!/usr/bin/php
<?php

require 'lib/Spotify.php';
require 'lib/LastFM.php';

$lastFm = new LastFM('***');

$tracks = $lastFm->userGetRecentTracks('Hanse');

file_put_contents('tracks.cache', serialize($tracks));