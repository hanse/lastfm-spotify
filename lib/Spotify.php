<?php

/**
 * Spotify API wrapper
 *
 * @package lastfm-spotify
 * @author Hans-Kristian Koren
 * @todo Add ability to search for albums and artists
 */
class Spotify {
    
    /**
     * Search the spotify database for $query
     *
     * @param string $query 
     * @return array  Information about the search result
     * @author Hans-Kristian Koren
     */
    public function search($query) {
        $clean = $this->cleanQuery($query);
        $result = simplexml_load_file('http://ws.spotify.com/search/1/track?q=' . urlencode($clean));
        
        if (count($result->track) > 0) {
            return array(
                'artist' => (string) $result->track[0]->artist->name,
                'track'  => (string) $result->track[0]->name,
                'link'   => (string) $result->track[0]['href'],
                'query' => $query,
                'clean'  => $clean,
            );
        }
        
        return NULL;
    }
    
    /**
     * Clean up the search query to get better matches
     *
     * @param string $query 
     * @return string  The cleaned query
     * @author Hans-Kristian Koren
     */
    protected function cleanQuery($query) {
        $query = str_replace('-', ' ', $query);
        $query = preg_replace('/\(.*?\)/', '', $query);
        $query = preg_replace('/feat[^\s]+/', '', $query);
        return $query;
    }
}