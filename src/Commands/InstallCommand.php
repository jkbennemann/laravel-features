<?php

namespace Jkbennemann\Features\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    public $signature = 'feature:install {--uuid} {--id}';

    public $description = 'Sets everything up';

    private const OPTIONS = ['id', 'uuid'];

    public function handle(): int
    {
        $chosenOption = $this->getChosenOption();

        if ($chosenOption === false) {
            $this->error('You cannot choose "uuid" and "id" at the same time.');

            return self::INVALID;
        }

        if ($chosenOption === null) {
            do {
                $chosenOption = Str::lower($this->askWithCompletion(
                    'What is the type of your User model? Possible values are: id,uuid',
                    self::OPTIONS,
                    'id'
                ));
            } while (!in_array($chosenOption, self::OPTIONS, true));
        }

        $this->callSilent('vendor:publish', ['--tag' => 'features-config', '--force' => true]);
        $this->callSilent('vendor:publish', ['--tag' => 'features-migrations', '--force' => true]);

        if ($chosenOption === self::OPTIONS[1]) {
            $this->replaceInFile("'user_model_type' => 'id'", "'user_model_type' => 'uuid'", config_path('features.php'));
        }

        $this->info('Installation for ' . $chosenOption . ' completed!');

        return self::SUCCESS;
    }

    protected function replaceInFile($search, $replace, $path): void
    {
        file_put_contents(
            $path,
            str_replace($search, $replace, file_get_contents($path))
        );
    }

    private function getChosenOption(): null|string|bool
    {
        $hasOptionUuid = $this->option('uuid');
        $hasOptionId = $this->option('id');

        return match (true) {
            $hasOptionUuid && $hasOptionId => false,
            $hasOptionUuid => 'uuid',
            $hasOptionId => 'id',
            default => null,
        };
    }
}
