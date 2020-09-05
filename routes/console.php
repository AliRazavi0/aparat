<?php

Artisan::command('aparat:clear', function () {
    clear_storage('videos');
    $this->info('CLear uploaded video files');

    clear_storage('category');
    $this->info('CLear uploaded category files');

    clear_storage('channel');
    $this->info('CLear uploaded channel files');

})->describe('Clear all temporary files,...');
