<?php

use Rowles\Models\Video;

Breadcrumbs::for('home', function ($trail) {
    $motd = '';
    if (request()->routeIs('video.index')) {
        $motd = ' - Find something to watch :D';
    }
    $trail->push('Home' . $motd, route('video.index'));
});
Breadcrumbs::for('watch', function ($trail, Video $video) {
    $trail->parent('home');
    $trail->push($video->title, route('video.watch', ['id' => $video->id]));
});
Breadcrumbs::for('account', function ($trail) {
    $trail->parent('home');
    $trail->push('Manage Account', route('account.index'));
});


Breadcrumbs::for('admin.video', function ($trail) {
    $trail->parent('home');
    $trail->push('Manage Videos', route('admin.video'));
});
Breadcrumbs::for('admin.subscription', function ($trail) {
    $trail->parent('home');
    $trail->push('Manage Subscriptions', route('admin.subscription'));
});
