<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

// Bootstrap Laravel agar Eloquent ORM dan Service Provider aktif jika belum di-bootstrap
if (class_exists(\Illuminate\Container\Container::class) && \Illuminate\Container\Container::getInstance() instanceof \Illuminate\Foundation\Application) {
    $laravel = \Illuminate\Container\Container::getInstance();
} else {
    // Panggil autoloader Composer Laravel
    require __DIR__ . '/../vendor/autoload.php';

    /** @var \Illuminate\Foundation\Application $laravel */
    $laravel = require __DIR__.'/../bootstrap/app.php';
    $kernel = $laravel->make(\Illuminate\Contracts\Http\Kernel::class);
    $kernel->bootstrap();
}

$app = AppFactory::create();

// Set base path
$app->setBasePath('/slim-api');

// Endpoint: Mengambil daftar project secara ringkas
$app->get('/projects', function (Request $request, Response $response) {
    try {
        $projects = \App\Models\Project::with('owner')->get();
        
        $data = $projects->map(function ($project) {
            return [
                'id' => $project->id,
                'name' => $project->name,
                'client_name' => $project->client_name,
                'status' => $project->status,
                'priority' => $project->priority,
                'progress' => $project->progress . '%',
                'due_date' => $project->due_date?->format('Y-m-d'),
                'owner' => $project->owner?->name,
            ];
        });

        $payload = json_encode([
            'status' => 'success',
            'framework' => 'Slim Framework 4 (Integrated with Laravel Eloquent)',
            'count' => $data->count(),
            'data' => $data
        ], JSON_PRETTY_PRINT);
        
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    } catch (\Exception $e) {
        $response->getBody()->write(json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ], JSON_PRETTY_PRINT));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(500);
    }
});

// Endpoint: Mengambil detail progress project, breakdown task, dan member
$app->get('/projects/{id}', function (Request $request, Response $response, array $args) {
    try {
        $id = $args['id'];
        $project = \App\Models\Project::with(['owner', 'members', 'milestones'])->find($id);

        if (!$project) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => 'Project not found'
            ], JSON_PRETTY_PRINT));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $payload = json_encode([
            'status' => 'success',
            'framework' => 'Slim Framework 4 (Integrated with Laravel Eloquent)',
            'data' => [
                'id' => $project->id,
                'name' => $project->name,
                'client_name' => $project->client_name,
                'status' => $project->status,
                'priority' => $project->priority,
                'progress' => $project->progress . '%',
                'due_date' => $project->due_date?->format('Y-m-d'),
                'owner' => [
                    'id' => $project->owner?->id,
                    'name' => $project->owner?->name,
                    'email' => $project->owner?->email,
                ],
                'task_breakdown' => $project->getTaskBreakdown(),
                'members' => $project->members->map(function ($member) {
                    return [
                        'id' => $member->id,
                        'name' => $member->name,
                    ];
                }),
                'milestones' => $project->milestones->map(function ($milestone) {
                    return [
                        'id' => $milestone->id,
                        'title' => $milestone->title,
                        'due_date' => $milestone->due_date?->format('Y-m-d'),
                        'completed_at' => $milestone->completed_at?->format('Y-m-d H:i:s'),
                    ];
                })
            ]
        ], JSON_PRETTY_PRINT);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    } catch (\Exception $e) {
        $response->getBody()->write(json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ], JSON_PRETTY_PRINT));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(500);
    }
});

// Jalankan aplikasi Slim
$app->run();
