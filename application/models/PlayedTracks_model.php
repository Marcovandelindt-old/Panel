<?php

defined('BASEPATH') or exit('No direct script access allowed');

class PlayedTracks_model extends CI_Model
{
    public $track_id;
    public $artist_id   = null;
    public $artist_name;
    public $track_name;
    public $album_id    = null;
    public $album_name  = null;
    public $image       = null;
    public $date_uts;
    public $created;
    public $now_playing = 0;

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get track by date uts
     *
     * @param string $date_uts
     *
     * @return mixed $result | false
     */
    public function getTrackByDateUts($date_uts)
    {
        $query = '
            SELECT
                `pt`.*
            FROM
                `played_tracks` AS `pt`
            WHERE
                `pt`.`date_uts` = ' . $date_uts . '
        ';

        $this->load->database();
        $result = $this->db->query($query)
            ->result();

        if (!empty($result)) {
            return $result;
        }

        return false;
    }

    /**
     * Save a track
     *
     * @param array $trackdata
     *
     */
    public function save($trackdata)
    {
        if (!empty($trackdata)) {
            $query = '
                INSERT INTO `played_tracks` (
                    `id`,
                    `track_id`,
                    `artist_id`,
                    `artist_name`,
                    `track_name`,
                    `album_name`,
                    `image`,
                    `date_uts`,
                    `created`
                ) VALUES (
                    null,
                    "' . $trackdata['track_id'] . '",
                    "' . $trackdata['artist_id'] . '",
                    "' . $trackdata['artist_name'] . '",
                    "' . $trackdata['track_name'] . '",
                    "' . $trackdata['album_name'] . '",
                    "' . $trackdata['image'] . '",
                    "' . $trackdata['date_uts'] . '",
                    "' . $trackdata['created'] . '"
                );
            ';

            $this->db->query($query);
        }
    }

    /**
     * Get tracks
     *
     * @param int $limit
     *
     * @return \PlayedTracks_model
     */
    public function getPlayedTracks($limit)
    {
        $query = '
            SELECT
                `pt`.*
            FROM
                `played_tracks` AS `pt`
            ORDER BY 
                `date_uts` DESC
            LIMIT ' . $limit . ';
        ';

        $this->load->database();
        return $this->db->query($query)
            ->result();
    }

    /**
     * Get tracks for current date
     */
    public function getTodaysTracks()
    {
        $current_date = date('Y-m-d');

        $query = '
            SELECT
                `pt`.*
            FROM
                `played_tracks` AS `pt`
            WHERE
                DATE(`pt`.`created`) = ' . $current_date . '
        ';

        $this->load->database();
        return $this->db->query($query)
            ->result();
    }
}