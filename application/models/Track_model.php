<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Track_model extends CI_Model
{
    /**
     * Get a track by id
     *
     * @param int $id
     */
    public function getById($id)
    {
        $query = '
            SELECT
                `t`.*
            FROM
                `tracks` AS `t`
            WHERE
                `t`.`track_id` = ' . $id . '
            LIMIT 1
        ';

        $this->load->database();
        return $this->db->query($query)
            ->row();
    }

    /**
     * Get track by system name
     *
     * @param string $systemName
     */
    public function getBySystemName($systemName)
    {
        $query = '
            SELECT
                `t`.*
            FROM
                `tracks` AS `t`
            WHERE
                `t`.`system_name` = "' . $systemName . '"
            LIMIT 1
        ';

        $this->load->database();
        return $this->db->query($query)
            ->row();
    }

    /**
     * Save a track
     *
     * @param array $trackdata
     */
    public function save($trackdata)
    {
        $query = '
            INSERT INTO `tracks` (
                `track_id`,
                `track_name`,
                `system_name`,
                `image`,
                `artist_id`,
                `created`,
                `tags`
            ) VALUES (
                NULL,
                "' . $trackdata['track_name'] . '",
                "' . $trackdata['system_name'] . '",
                "' . $trackdata['image'] . '",
                "' . $trackdata['artist_id'] . '",
                NOW(),
                "' . $trackdata['tags'] . '"               
            );
        ';

        $this->load->database();
        $this->db->query($query);
    }

    /**
     * Update playcount
     *
     * @param string $systemName
     */
    public function updatePlayCount($systemName)
    {
        $query = ' 
            UPDATE 
                `tracks`
            SET
                `play_count` = `play_count` + 1
            WHERE
                `system_name` = "' . $systemName . '"
            LIMIT 1
        ';

        $this->load->database();
        $this->db->query($query);
    }

    /**
     * Get all tracks
     */
    public function getAllTracks()
    {
        $query = '
            SELECT
                `t`.*
            FROM 
                `tracks` AS `t`
        ';

        $this->load->database();
        return $this->db->query($query)
            ->result();
    }

}