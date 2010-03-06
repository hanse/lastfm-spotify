<?php

/**
 * Last.fm API wrapper
 *
 * @package default
 * @author Hans-Kristian Koren
 */
class LastFM {
    
    const BASE_URL = 'http://ws.audioscrobbler.com/2.0/';
    
    /**
     * The API key for the Last.fm service
     *
     * @var string
     */
    protected $apiKey = NULL;
    
    /**
     * Instance of the spotify class
     *
     * @var Spotify
     */
    protected $spotify;
    
    /**
     * Constructor
     *
     * @param string $apiKey 
     * @author Hans-Kristian Koren
     */
    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }
    
    /**
     * Set the spotify instance
     *
     * @param Spotify $spotify 
     * @return void
     * @author Hans-Kristian Koren
     */
    public function setSpotify(Spotify $spotify) {
        $this->spotify = $spotify;
    }
    
    /**
     * Get the spotify instance
     *
     * @return Spotify
     * @author Hans-Kristian Koren
     */
    public function getSpotify() {
        if (!$this->spotify) {
            $this->spotify = new Spotify();
        }
        return $this->spotify;
    }
    
    /**
     * Retrieve the recent tracks played by $username
     *
     * @param string $username 
     * @return void
     * @author Hans-Kristian Koren
     */
    public function userGetRecentTracks($username) {
        $url = $this->constructQuery('user.getRecentTracks', array('user' => $username));
        $tracks = json_decode(file_get_contents($url));
        
        foreach ($tracks->recenttracks->track as $track) {
            if ($spotifyResult = $this->getSpotify()->search($track->artist->{'#text'} . ' ' . $track->name)) {
                $result[] = $spotifyResult;
            } else {
                $result[] = array('artist' => $track->artist->{'#text'}, 'track' => $track->name, 'link' => 'None');
            }
        }
        return $result;
    }
    
    /**
     * Construct a last.fm query string
     *
     * @param string $method  The last.fm API method
     * @param array $arguments  Query string parameters
     * @return void
     * @author Hans-Kristian Koren
     */
    protected function constructQuery($method, array $arguments) {
        $defaults = array(
            'format' => 'json',
            'api_key' => $this->apiKey
        );
        
        $arguments = array_merge($defaults, $arguments);
        
        $url = self::BASE_URL . '?method=' . $method . '&' . http_build_query($arguments);
        return $url;
    }
}