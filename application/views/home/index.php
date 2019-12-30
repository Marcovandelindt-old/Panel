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
            <div class="messages">
                <h4 class="feed-heading">Feed</h4>
                <?php
                    if (!empty($messages)) {
                        foreach ($messages as $message) {
                            if ($message instanceof PlayedTracks_model) {
                                ?>
                                <div class="message track">
                                    <div class="media">
                                        <?php
                                            if (!empty($message->image)) {
                                                echo '<img src="' . $message->image . '" class="mr-3">';
                                            }
                                        ?>
                                        <div class="media-body">
                                            <span class="message-info">
                                                <div class="date-info">
                                                    <?= date('l', $message->date_uts) ?>, <?= date('d', $message->date_uts) ?> <?= date('F', $message->date_uts) ?> <?= date('Y', $message->date_uts) ?> | <strong><?= date('H:i', $message->date_uts + 3600) ?></strong>
                                                </div>
                                                <p style="padding-top: 10px; font-style: italic;">Marco van de Lindt listened to: <strong><?= $message->artist_name ?> - <?= $message->track_name ?></strong></p>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</div>