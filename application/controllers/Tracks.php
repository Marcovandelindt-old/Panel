<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Tracks extends CI_Controller
{
    /**
     * @inheritdoc
     */
    public function __construct()
    {
        parent::__construct();

        # Load helpers
        $this->load->helper(
            [
                'url',
            ]
        );

        # Load models
        $this->load->model('Track_model');
        $this->load->model('PlayedTracks_model');
        $this->load->model('Artist_model');
    }

    /**
     * Index action
     */
    public function index()
    {
        $tracks = $this->PlayedTracks_model->getPlayedTracks();
        $data   = [
            'tracks' => $tracks,
            'title'  => 'History',
        ];

        $this->load->view('layouts/header', $data);
        $this->load->view('tracks/index', $data);
        $this->load->view('layouts/footer', $data);
    }

    /**
     * Get track based on id
     *
     * @param int $trackId
     */
    public function getTrack($trackId)
    {
        if (is_numeric($trackId)) {
            $track = $this->Track_model->getById($trackId);

            if (!empty($track)) {
                $artist = $this->Artist_model->getById($track->artist_id);

                $data = [
                    'track'  => $track,
                    'artist' => $artist,
                    'title'  => $track->track_name . ' - ' . $artist->artist_name,
                ];

                $this->load->view('layouts/header', $data);
                $this->load->view('tracks/track', $data);
                $this->load->view('layouts/footer', $data);
            }
        }
    }
}