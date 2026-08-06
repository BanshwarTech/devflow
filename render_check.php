<?php

require __DIR__.'/vendor/autoload.php';

$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$bc = app('blade.compiler');

$views = [
    'dashboard'      => 'resources/views/livewire/dashboard.blade.php',
    'projects.index' => 'resources/views/livewire/projects/index.blade.php',
    'projects.create'=> 'resources/views/livewire/projects/create.blade.php',
    'projects.edit'  => 'resources/views/livewire/projects/edit.blade.php',
    'projects.show'  => 'resources/views/livewire/projects/show.blade.php',
];

foreach ($views as $name => $path) {
    $source = file_get_contents(__DIR__.'/'.$path);
    try {
        $compiled = $bc->compileString($source);
        $tmp = tempnam(sys_get_temp_dir(), 'blade_').'.php';
        file_put_contents($tmp, $compiled);
        $out = [];
        $code = 0;
        exec('php -l '.escapeshellarg($tmp).' 2>&1', $out, $code);
        unlink($tmp);
        $lint = trim(implode("\n", $out));
        echo "[{$name}] compile: OK | lint: {$lint}\n";
    } catch (\Throwable $e) {
        echo "[{$name}] COMPILE/FAIL: ".$e->getMessage()."\n";
    }
}

echo "\n=== Components instantiate & render (with computed injection) ===\n";

use Illuminate\Support\Facades\Auth;
$u = \App\Models\User::where('email', 'admin@devflow.test')->first();
Auth::login($u);

$components = [
    ['dashboard', []],
    ['projects.index', []],
    ['projects.create', []],
];

foreach ($components as [$name, $params]) {
    try {
        $proxy = \Livewire\Livewire::mount($name, $params);
        $component = $proxy->instance();
        // Force a real render to ensure computed props are injected
        $rendered = $component->render();
        echo "[{$name}] instantiated & rendered OK\n";
    } catch (\Throwable $e) {
        echo "[{$name}] FAIL: ".$e->getMessage()."\n";
        echo "  at ".$e->getFile().':'.$e->getLine()."\n";
    }
}

// Projects\Show needs a project
try {
    $project = \App\Models\Project::first();
    if (! $project) { echo "[projects.show] SKIP (no projects)\n"; }
    else {
        $proxy = \Livewire\Livewire::mount('projects.show', ['project' => $project]);
        $component = $proxy->instance();
        $component->render();
        echo "[projects.show] instantiated & rendered OK\n";
    }
} catch (\Throwable $e) {
    echo "[projects.show] FAIL: ".$e->getMessage()."\n";
    echo "  at ".$e->getFile().':'.$e->getLine()."\n";
}

// Projects\Edit
try {
    $project = \App\Models\Project::first();
    if (! $project) { echo "[projects.edit] SKIP (no projects)\n"; }
    else {
        $proxy = \Livewire\Livewire::mount('projects.edit', ['project' => $project]);
        $component = $proxy->instance();
        $component->render();
        echo "[projects.edit] instantiated & rendered OK\n";
    }
} catch (\Throwable $e) {
    echo "[projects.edit] FAIL: ".$e->getMessage()."\n";
    echo "  at ".$e->getFile().':'.$e->getLine()."\n";
}

echo "\n=== Real HTTP request: GET /dashboard ===\n";
$u = \App\Models\User::where('email', 'admin@devflow.test')->first();
$u->email_verified_at = now();
$u->save();

$request = \Illuminate\Http\Request::create('/dashboard', 'GET');
$request->setLaravelSession(app('session.store'));
$request->setUserResolver(fn () => $u);

$router = $app->make(\Illuminate\Routing\Router::class);
try {
    $response = $router->dispatch($request);
    echo "HTTP status: ".$response->getStatusCode()." | body bytes: ".strlen($response->getContent())."\n";
    $body = $response->getContent();
} catch (\Throwable $e) {
    echo "DISPATCH EXCEPTION: ".get_class($e)." :: ".$e->getMessage()."\n";
    $body = null;
}

if (!empty($body)) {
    // Search the debug error page for the actual exception message.
    if (preg_match('/<title>([^<]*)<\/title>/', $body, $m)) {
        echo "title: ".$m[1]."\n";
    }
    // Symfony HtmlErrorRenderer puts the message in a few likely spots.
    if (preg_match('/(Undefined variable[^<]{0,80}|syntax error[^<]{0,80}|Too few arguments[^<]{0,80}|ArgumentCountError[^<]{0,80}|Class [^<]{0,60} not found[^<]{0,40})/i', $body, $m)) {
        echo "ERROR MSG: ".$m[1]."\n";
    } else {
        echo "no recognizable error msg in body; body head:\n";
        echo substr($body, 0, 600)."\n";
    }
}
