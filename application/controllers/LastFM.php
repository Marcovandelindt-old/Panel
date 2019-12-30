<?php

defined('BASEPATH') or exit('No direct script access allowed');

class LastFM extends CI_Controller
{
    const LASTFM_ENDPOINT = 'http://ws.audioscrobbler.com/2.0/';

    # Actions
    const ACTION_RECENT_TRACKS = 'user.getrecenttracks';

    /**
     * @inheritdoc
     */
    public function construct()
    {
        parent::__construct();

        # Load models

    }

    /**
     * Index action
     */
    public function index()
    {
        echo 'x';
        die;
    }

    /**
     * Get LastFM history
     */
    public function update()
    {
        # Load model
        $this->load->model('playedtracks_model');
        $this->load->model('artist_model');
        $this->load->database();

        $endpoint = $this->buildEndpoint(self::ACTION_RECENT_TRACKS);
        $xml      = @file_get_contents($endpoint);

        if (!empty($xml)) {
            $element = new SimpleXMLElement($xml);
            if (!empty($element)) {
                foreach ($element->recenttracks->track as $track) {

                    if (!$track->attributes()['nowplaying'] == true && empty($this->playedtracks_model->getTrackByDateUts(strtotime($track->date)))) {

                        # Get the artist
                        $systemName = strtolower(str_replace(' ', '_', $track->artist));

                        # Save new artist
                        if (empty($this->artist_model->getBySystemName($systemName))) {

                            $artistdata = [
                                'artist_name' => $track->artist,
                                'system_name' => strtolower(str_replace(' ', '_', $track->artist)),
                            ];

                            $this->artist_model->save($artistdata);
                        }

                        $artist = $this->artist_model->getBySystemName($systemName);

                        $trackdata = [
                            'artist_name' => $track->artist,
                            'artist_id'   => (!empty($artist) ? $artist->artist_id : null),
                            'track_name'  => $track->name,
                            'album_name'  => $track->album,
                            'image'       => (!empty($track->image) ? $track->image[3] : ''),
                            'date_uts'    => strtotime($track->date),
                            'created'     => date('Y-m-d', strtotime($track->date)),
                        ];

                        $this->playedtracks_model->save($trackdata);
                    }
                }
            }
        }
    }

    /**
     * Build the endpoint
     *
     * @param string $action
     *
     * @return mixed string | boolean
     */
    public function buildEndpoint($action)
    {
        if (!empty($action)) {
            $endpoint = self::LASTFM_ENDPOINT . '/?method=' . $action . '&user=' . $this->config->item('lastfm_registered_to') . '&api_key=' . $this->config->item('lastfm_api_key');
            return $endpoint;
        }

        return false;
    }
}