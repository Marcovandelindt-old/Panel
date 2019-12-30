<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Artist_model extends CI_Model
{
    public $artist_id;
    public $artist_name;
    public $system_name;
    public $created;

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get artist by system name
     *
     * @param string $systemName
     */
    public function getBySystemName($systemName)
    {
        $query = '
            SELECT
                `a`.*
            FROM
                `artists` AS `a`
            WHERE
                `a`.`system_name` = "' . $systemName . '"
            LIMIT 1
        ';

        $this->load->database();
        return $this->db->query($query)->row();
    }

    /**
     * Save an artist
     *
     * @param array $artistdata
     */
    public function save($artistdata)
    {
        $query = '
            INSERT INTO `artists` (
                `artist_name`,
                `system_name`,
                `created`
            ) VALUES (
                "' . $artistdata['artist_name'] . '",
                "' . $artistdata['system_name'] . '",
                NOW()
            );
        ';

        $this->load->database();
        $this->db->query($query);
    }
}