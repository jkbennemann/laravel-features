<?php

namespace Jkbennemann\Features\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    public $signature = 'feature:install';

    public $description = 'Sets everything up';

    public function handle(): int
    {
        $answers = ['id', 'uuid'];
        do {
            $answer = $this->askWithCompletion(
                'What is the type of your User model? Possible values are: id,uuid',
                $answers, 'id');
        } while(!in_array($answer, $answers, true));

        $this->callSilent('vendor:publish', ['--tag' => 'features-config', '--force' => true]);

        if ($answer === 'uuid') {
            $this->replaceInFile("'user_model_type' => 'id'", "'user_model_type' => 'uuid'", config_path('features.php'));
        }

        $this->callSilent('vendor:publish', ['--tag' => 'features-migrations', '--force' => true]);

        $this->info('Installation completed!');

        return self::SUCCESS;
    }

    protected function replaceInFile($search, $replace, $path)
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }
}
