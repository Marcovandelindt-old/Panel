<?php

defined('BASEPATH') or exit('No direct script access allowed');

class UpdateLastFM extends CI_Controller
{
    # Endpoints
    const LASTFM_ENDPOINT = 'http://ws.audioscrobbler.com/2.0/';

    # Actions
    const ACTION_RECENT_TRACKS = 'user.getrecenttracks';
    const ACTION_TRACK_INFO    = 'track.getInfo';

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        parent::__construct();

        # Load the models
        $this->load->model('PlayedTracks_model');
        $this->load->model('Artist_model');
        $this->load->model('Track_model');

        # Load the database
        $this->load->database();
    }

    /**
     * Index action
     */
    public function index()
    {
        # Set time limit to unlimited
        set_time_limit(0);

        # Get the recent tracks
        $recentTracksEndpoint = $this->buildEndpoint(self::LASTFM_ENDPOINT, self::ACTION_RECENT_TRACKS, $this->config->item('lastfm_registered_to'), $this->config->item('lastfm_api_key'), 22);
        $recentTracksXML      = @file_get_contents($recentTracksEndpoint);

        if (!empty($recentTracksXML)) {
            $element = new SimpleXMLElement($recentTracksXML);

            if (!empty($element)) {
                foreach ($element->recenttracks->track as $track) {
                    $trackSystemName = $this->createSystemName($track->artist . $track->name);
                    if ($track->attributes()['nowplaying'] != true && empty($this->PlayedTracks_model->getTrackByDateUtsAndSystemName((string)$track->date->attributes()['uts'], $trackSystemName))) {

                        # Get the artist
                        $systemName = $this->createSystemName($track->artist);

                        # Save new artist
                        if (empty($this->Artist_model->getBySystemName($systemName))) {
                            $artistdata = [
                                'artist_name' => $track->artist,
                                'system_name' => $systemName,
                            ];

                            $this->Artist_model->save($artistdata);
                        }

                        $artist = $this->Artist_model->getBySystemName($systemName);

                        # Save track
                        $trackInfoEndpoint = self::LASTFM_ENDPOINT . '/?method=' . self::ACTION_TRACK_INFO . '&api_key=' . $this->config->item('lastfm_api_key') . '&artist=' . strtolower(
                                str_replace(' ', '+', $track->artist)
                            ) . '&track=' . strtolower(
                                str_replace(' ', '+', $track->name)
                            );

                        $trackInfoXML = @file_get_contents($trackInfoEndpoint);

                        if (!empty($trackInfoXML)) {
                            $info = new SimpleXMLElement($trackInfoXML);
                            if (!empty($info) && $info->attributes()['status'] != 'failed') {
                                foreach ($info->track as $newTrack) {

                                    $systemName = $this->createSystemName($track->artist . ' ' . $track->name);

                                    # Get tags
                                    $tags = [];
                                    if (!empty($newTrack->toptags)) {
                                        foreach ($newTrack->toptags->tag as $tag) {
                                            $tags[] = $tag->name;
                                        }
                                    }

                                    if (empty($this->Track_model->getBySystemName($systemName))) {
                                        $newtrackdata = [
                                            'track_name'  => $newTrack->name,
                                            'system_name' => $systemName,
                                            'image'       => (!empty($track->image) ? $track->image[3] : ''),
                                            'artist_id'   => (!empty($artist) ? $artist->artist_id : null),
                                            'album_name'  => $track->album,
                                            'tags'        => (!empty($tags) ? implode(', ', $tags) : null),
                                        ];

                                        $this->Track_model->save($newtrackdata);
                                    }
                                }
                            } elseif ($info->attributes()['status'] == 'failed') {
                                $systemName = $this->createSystemName($track->artist . ' ' . $track->name);
                                if (empty($this->Track_model->getBySystemName($systemName))) {
                                    $newtrackdata = [
                                        'track_name'  => $track->name,
                                        'system_name' => $systemName,
                                        'image'       => (!empty($track->image) ? $track->image[3] : ''),
                                        'artist_id'   => (!empty($artist) ? $artist->artist_id : null),
                                        'album_name'  => $track->album,
                                        'tags'        => null,
                                    ];

                                    $this->Track_model->save($newtrackdata);
                                }
                            } else {
                                // Something went wrong
                            }
                        }

                        $newSystemName = $this->createSystemName($track->artist . ' ' . $track->name);
                        $savedTrack    = $this->Track_model->getBySystemName($newSystemName);

                        $uts = (string)$track->date->attributes()['uts'];

                        $trackdata = [
                            'track_id'    => (!empty($savedTrack) ? $savedTrack->track_id : null),
                            'artist_name' => $track->artist,
                            'artist_id'   => (!empty($artist) ? $artist->artist_id : null),
                            'track_name'  => $track->name,
                            'system_name' => $trackSystemName,
                            'album_name'  => $track->album,
                            'image'       => (!empty($track->image) ? $track->image[3] : ''),
                            'date_uts'    => $uts,
                            'created'     => date('Y-m-d', $uts),
                        ];

                        $this->PlayedTracks_model->save($trackdata);
                        echo 'Saved!<br />';

                        // Update playcount in tracks
                        $this->Track_model->updatePlayCount($savedTrack->system_name);
                    }
                }
                die;

            }
        } else {
            // No data
            echo 'x';
            die;
        }
    }

    /**
     * Build the endpoint for fetching data from the LastFM API
     *
     * @param string $action
     * @param string $endpoint
     * @param string $registeredTo
     * @param string $apiKey
     * @param int    $limit
     *
     * @return mixed string | bool
     */
    function buildEndpoint($endpoint, $action, $registeredTo, $apiKey, $limit = 200)
    {
        if (!empty($action)) {
            $url = $endpoint . '/?method=' . $action . '&user=' . $registeredTo . '&api_key=' . $apiKey . '&limit=' . $limit;
            return $url;
        }

        return false;
    }

    /**
     * Create system name from string
     *
     * @param string $string
     *
     * @return string
     */
    public function createSystemName($string)
    {
        if (!empty($string)) {
            $formatted = preg_replace('/[^ \w]/', '', $string);
            $formatted = str_replace('  ', ' ', $formatted);
            $formatted = str_replace(' ', '_', $formatted);
            $formatted = str_replace(['ä', 'Ä', 'ö', 'Ö', 'ü', 'Ü', 'ï', 'Ï', 'ë', 'Ë'], ['a', 'A', 'o', 'O', 'u', 'U', 'i', 'I', 'e', 'E'], $formatted);
            $formatted = preg_replace('/[^A-Za-z0-9\-]/', '', $formatted);
            $formatted = strtolower($formatted);

            return $formatted;
        }
    }
}

