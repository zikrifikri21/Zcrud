<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;

class CRUDCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Z-CRUD:model {name} {--controller} {--model} {--migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model, migration and controller';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        $pluralName = Str::plural($name);
        $controllerName = "{$name}Controller";
        $modelName = "{$name}";
        $migrationName = "create_{$pluralName}_table";

        if($this->option('controller')){
            $controllerTemplate = "<?php
            namespace App\Http\Controllers;
            use Illuminate\Http\Request;
            use App\\{$modelName};
            class {$controllerName} extends Controller
            {
                public function index()
                {
                    return view('{$pluralName}.index');
                }
                public function create()
                {
                    return view('{$pluralName}.create');
                }
                public function store(Request \$request)
                {
                    \$validatedData = \$request->validate([
                        'name' => 'required|max:255',
                    ]);
                    \${$modelName} = new {$modelName}();
                    \${$modelName}->name = \$validatedData['name'];
                    \${$modelName}->save();
                    return redirect('/{$pluralName}');
                }
            }";
            File::put(app_path("/Http/Controllers/{$controllerName}.php"), $controllerTemplate);
            $this->info("{$controllerName} created successfully.");
        }
        if($this->option('model')){
            Artisan::call("make:model", ["name" => "{$modelName}"]);
            $this->info("{$modelName} created successfully.");
        }
        if($this->option('migration')){
            Artisan::call("make:migration", ["name" => "{$migrationName}"]);
            $this->info("{$migrationName} migration created successfully.");
        }
    }
}