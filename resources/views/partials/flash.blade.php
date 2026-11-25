@php
    $done = [
        'added' => ['projects', 'Project added.'], 'saved' => ['projects', 'Changes saved.'],
        'role-added' => ['experience', 'Role added.'], 'role-saved' => ['experience', 'Changes saved.'],
        'edu-added' => ['education', 'Qualification added.'], 'edu-saved' => ['education', 'Changes saved.'],
        'kit-added' => ['toolkit', 'Skill group added.'], 'kit-saved' => ['toolkit', 'Changes saved.'],
        'profile-saved' => ['profile', 'Profile saved.'],
        'strava-saved' => ['strava', 'Strava settings saved.'], 'strava-synced' => ['strava', 'Synced with Strava.'],
        'score-added' => ['leaderboard', 'Entry added.'], 'score-saved' => ['leaderboard', 'Changes saved.'],
    ][request('done')] ?? null;
    [$in, $message] = match (true) {
        (bool) session('message') => [null, session('message')],
        (bool) $done => $done,
        is_string(request('deleted')) => [request('in', 'projects'), '“'.request('deleted').'” deleted.'],
        default => [null, null],
    };
@endphp
@if ($message && (! isset($scope) || $in === null || $in === $scope))
    <p class="form__ok" role="status">{{ $message }}</p>
@endif
