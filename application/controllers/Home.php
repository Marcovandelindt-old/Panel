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

        # Set data
        $data = [
            'title'        => 'Home',
            'playedTracks' => $this->PlayedTracks_model->getPlayedTracks(50),
            'todaysTracks' => $this->PlayedTracks_model->getTodaysTracks(),
        ];

        $this->load->view('layouts/header', $data);
        $this->load->view('home/index', $data);
        $this->load->view('layouts/footer', $data);
    }
}