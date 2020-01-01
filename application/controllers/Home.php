<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        # Load helpers
        $this->load->helper(
            [
                'url',
            ]
        );

        # Load libraries
        $this->load->library(
            [
                'session',
            ]
        );
    }

    /**
     * Index action
     */
    public function index()
    {
        $this->load->model('PlayedTracks_model');
        $this->load->model('Track_model');
        $this->load->model('Artist_model');

        $playedTracks = $this->PlayedTracks_model->getPlayedTracks();
        $todaysTracks = $this->PlayedTracks_model->getTodaysTracks();

        $messages = [];
        foreach ($todaysTracks as $track) {
            $messages[] = $track;
        }

        # Get most played track
        $mostPlayed = $this->PlayedTracks_model->getMostPlayedToday();

        if (!empty($mostPlayed)) {
            $topTrack             = $this->Track_model->getById($mostPlayed->track_id);
            $topTrack->occurrence = $mostPlayed->occurrence;
        }

        $topTrackArtist = (!empty($topTrack) ? $this->Artist_model->getById($topTrack->artist_id) : '');

        # Set data
        $data = [
            'title'          => 'Home',
            'todaysTracks'   => $this->PlayedTracks_model->getTodaysTracks(),
            'playedTracks'   => $playedTracks,
            'messages'       => $messages,
            'topTrack'       => (!empty($topTrack) ? $topTrack : ''),
            'topTrackArtist' => $topTrackArtist,
        ];

        $this->load->view('layouts/header', $data);
        $this->load->view('home/index', $data);
        $this->load->view('layouts/footer', $data);
    }
}