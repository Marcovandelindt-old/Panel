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
       require_once '/../cronjobs/updateLastFM.php';
    }

    /**
     * Build the endpoint
     *
     * @param string $action
     *
     * @return mixed string | boolean
     */
    public
    function buildEndpoint(
        $action
    ) {
        if (!empty($action)) {
            $endpoint = self::LASTFM_ENDPOINT . '/?method=' . $action . '&user=' . $this->config->item('lastfm_registered_to') . '&api_key=' . $this->config->item('lastfm_api_key') . '&limit=200';
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
    function createSystemName(
        $string
    ) {
        if (!empty($string)) {
            $formatted = preg_replace('/[^ \w]/', '', $string);
            $formatted = str_replace('  ', ' ', $formatted);
            $formatted = str_replace(' ', '_', $formatted);
            $formatted = strtolower($formatted);

            return $formatted;
        }
    }
}