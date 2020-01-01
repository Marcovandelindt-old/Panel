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
     * @param string $dateUts
     * @param string $systemName
     *
     * @return mixed $result | false
     */
    public function getTrackByDateUtsAndSystemName($dateUts, $systemName)
    {
        $query = '
            SELECT
                `pt`.*
            FROM
                `played_tracks` AS `pt`
            WHERE
                `pt`.`date_uts` = "' . $dateUts . '"
            AND
                `pt`.`system_name` = "' . $systemName . '"
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
                    `system_name`,
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
                    "' . $trackdata['system_name'] . '",
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
    public function getPlayedTracks($limit = null)
    {
        $query = '
            SELECT
                `pt`.*
            FROM
                `played_tracks` AS `pt`
            ORDER BY 
                `date_uts` DESC
            ' . (!empty($limit) ? ' LIMIT ' . $limit : '') . '
        ';

        $this->load->database();
        return $this->db->query($query)
            ->result("PlayedTracks_model");
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
               `pt`.`created` = "' . $current_date . '"
            ORDER BY 
                `date_uts` DESC
        ';

        $this->load->database();
        return $this->db->query($query)
            ->result("PlayedTracks_model");
    }

    /**
     * Get most played track of today
     */
    public function getMostPlayedToday()
    {
        $query = '
            SELECT
                `track_id`,
                COUNT(`track_id`) AS `occurrence`
            FROM
                `played_tracks`
            WHERE
                `created` = "' . date('Y-m-d') . '"
            GROUP BY
                `track_id`
            ORDER BY 
                `occurrence` DESC 
            LIMIT 1
        ';

        $this->load->database();
        return $this->db->query($query)
            ->row();
    }
}