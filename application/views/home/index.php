<br/>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="social-block spotify">
                <span class="count"><h4><i class="fab fa-spotify"></i> <?= (!empty($playedTracks) ? count($playedTracks) : '') ?> songs played</h4></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="social-block youtube">
                <span class="count"><h4><i class="fab fa-youtube"></i> 0 videos posted</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="social-block facebook">
                <span class="count"><h4><i class="fab fa-facebook"></i> 0 posts placed</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="social-block playstation">
                <span class="count"><h4><i class="fab fa-playstation"></i> 0 achievements</span>
            </div>
        </div>
    </div>

    <br/>
    <div class="row">
        <div class="col-md-6">
            <div class="feed">
                <h3>Spotify Feed | <i class="date"><?= date('Y-m-d') ?></i></h3>
                <br />
                <?php

                if (!empty($todaysTracks)) {
                    foreach ($todaysTracks as $track) {
                        ?>
                        <div class="track">
                            <?php
                                if (!empty($track->image)) {
                                    echo '<img src=' . $track->image . ' class="feed-image" />';
                                }
                            ?>
                            <strong><?= date('H:i', $track->date_uts + 3600) ?></strong> | Marco listened to: <?= $track->artist_name ?> - <?= $track->track_name ?>
                            <hr />
                        </div>
                        <?php

                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>