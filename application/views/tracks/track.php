<br/>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="track-data">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="track-image">
                                <img src="<?= $track->image ?>" class="track-image">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="track-info">
                                <h4 class="track-name"><?= $track->track_name ?></h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="track-item">
                                            <strong>Artist:</strong><br/>
                                            <?= $artist->artist_name ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="track-item">
                                            <strong>Plays:</strong><br/>
                                            <?= $track->play_count ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>