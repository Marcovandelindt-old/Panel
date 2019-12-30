<div class="row">
    <div class="container-fluid">
        <div class="col-md-6">
            <div class="messages">
                <h4 class="feed-heading">Feed</h4>
                <?php

                if (!empty($tracks)) {
                    foreach ($tracks as $track) {
                        if ($track instanceof PlayedTracks_model) {
                            ?>
                            <a href="/tracks/<?= $track->track_id ?>" class="message-link">
                                <div class="message track">
                                    <div class="media">
                                        <?php

                                        if (!empty($track->image)) {
                                            echo '<img src="' . $track->image . '" class="mr-3">';
                                        }
                                        ?>
                                        <div class="media-body">
                                            <span class="message-info">
                                                <div class="date-info">
                                                    <?= date('l', $track->date_uts) ?>, <?= date('d', $track->date_uts) ?> <?= date('F', $track->date_uts) ?> <?= date('Y', $track->date_uts) ?> | <strong><?= date(
                                                            'H:i',
                                                            $track->date_uts + 3600
                                                        ) ?></strong>
                                                </div>
                                                <p style="padding-top: 10px; font-style: italic;">Marco van de Lindt listened to: <strong><?= $track->artist_name ?> - <?= $track->track_name ?></strong></p>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <?php

                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>