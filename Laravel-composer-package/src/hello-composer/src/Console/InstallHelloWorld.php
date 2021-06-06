<?php

namespace maximuse\HelloWorld\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallHelloWorld extends Command
{
    protected $signature = 'helloworld:install';

    protected $description = 'Install HelloWorld';

    public function handle()
    {
        // Info ketika telah memulai instalasi
        $this->info('Installing HelloWorld...');

        $this->info('Publishing configuration...');

        // Cek jika file config sudah ada
        if (!$this->configExists('helloworld.php')) {
            $this->publishConfiguration();
            $this->info('Published configuration');
        } else {
            // Konfirmasi apakah ingin mengoverwrite yang telah ada
            if ($this->shouldOverwriteConfig()) {
                $this->info('Overwriting configuration file...');
                $this->publishConfiguration($force = true);
            } else {
                $this->info('Existing configuration was not overwritten');
            }
        }

        // Info ketika telah selesai
        $this->info('Successfully Installed HelloWorld');
    }

    private function configExists($fileName)
    {
        return File::exists(config_path($fileName));
    }

    private function shouldOverwriteConfig()
    {
        return $this->confirm(
            'Config file already exists. Do you want to overwrite it?',
            false
        );
    }

    private function publishConfiguration($forcePublish = false)
    {
        $params = [
            '--provider' => "maximuse\HelloWorld\CalculatorServiceProvider",
            '--tag' => "config"
        ];

        if ($forcePublish === true) {
            $params['--force'] = '';
        }

        $this->call('vendor:publish', $params);
    }
}
