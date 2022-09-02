<?php

use U2y\FattureInCloud\Http\Controllers\FattureInCloudController;

app('router')->prefix('fattureincloud')->middleware(['web', config('fattureincloud.auth_middleware')])->group(function () {
    app('router')->get('', [FattureInCloudController::class, 'index'])->name('fattureincloud.auth');
    app('router')->get('auth-callback', [FattureInCloudController::class, 'callback'])->name('fattureincloud.auth_callback')->withoutMiddleware(config('fattureincloud.auth_middleware'));
});
