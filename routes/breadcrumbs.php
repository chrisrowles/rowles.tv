<?php

use Rowles\Models\Video;

Breadcrumbs::for('home', function ($trail) {
    $motd = '';
    if (request()->routeIs('video.index')) {
        $motd = ' / find something to watch :)';
    }

    $trail->push('Home' . $motd, route('video.index'));
});

// Home > Watch
Breadcrumbs::for('watch', function ($trail, Video $video) {
    $trail->parent('home');
    $trail->push($video->title, route('video.watch', ['id' => $video->id]));
});

// Dashboard
Breadcrumbs::for('dashboard', function ($trail) {
    $trail->parent('home');
    $trail->push('Dashboard', route('dashboard'));
});
