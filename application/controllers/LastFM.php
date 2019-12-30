<?php

defined('BASEPATH') or exit('No direct script access allowed');

class LastFM extends CI_Controller
{
    const LASTFM_ENDPOINT = 'http://ws.audioscrobbler.com/2.0/';

    # Actions
    const ACTION_RECENT_TRACKS = 'user.getrecenttracks';
    const ACTION_TRACK_INFO    = 'track.getInfo';

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
        $this->load->model('PlayedTracks_model');
        $this->load->model('Artist_model');
        $this->load->model('Track_model');
        $this->load->database();

        $recentTrackEndpoint = $this->buildEndpoint(self::ACTION_RECENT_TRACKS);
        $recentTracksXML     = @file_get_contents($recentTrackEndpoint);

        if (!empty($recentTracksXML)) {
            $element = new SimpleXMLElement($recentTracksXML);
            if (!empty($element)) {
                foreach ($element->recenttracks->track as $track) {

                    if (!$track->attributes()['nowplaying'] == true && empty($this->PlayedTracks_model->getTrackByDateUts(strtotime($track->date)))) {

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
                                            'artist_id'   => (!empty($artist) ? $artist->artist_id : null),
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
                                        'artist_id'   => (!empty($artist) ? $artist->artist_id : null),
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

                        $trackdata = [
                            'track_id'    => (!empty($savedTrack) ? $savedTrack->track_id : null),
                            'artist_name' => $track->artist,
                            'artist_id'   => (!empty($artist) ? $artist->artist_id : null),
                            'track_name'  => $track->name,
                            'album_name'  => $track->album,
                            'image'       => (!empty($track->image) ? $track->image[3] : ''),
                            'date_uts'    => strtotime($track->date),
                            'created'     => date('Y-m-d', strtotime($track->date)),
                        ];

                        $this->PlayedTracks_model->save($trackdata);

                        // Update playcount in tracks
                        $this->Track_model->updatePlayCount($savedTrack->system_name);
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
public
function buildEndpoint($action)
{
    if (!empty($action)) {
        $endpoint = self::LASTFM_ENDPOINT . '/?method=' . $action . '&user=' . $this->config->item('lastfm_registered_to') . '&api_key=' . $this->config->item('lastfm_api_key');
        return $endpoint;
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
public
function createSystemName($string)
{
    if (!empty($string)) {
        $formatted = preg_replace('/[^ \w]/', '', $string);
        $formatted = str_replace('  ', ' ', $formatted);
        $formatted = str_replace(' ', '_', $formatted);
        $formatted = strtolower($formatted);

        return $formatted;
    }
}
}