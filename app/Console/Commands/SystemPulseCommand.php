<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SystemPulseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:pulse';
    protected $description = 'Log a system uptime pulse with health metrics';

    public function handle()
    {
        $cpu = $this->getCpuUsage();
        $memory = $this->getMemoryUsage();
        $disk = $this->getDiskUsage();

        \App\Models\UptimePulse::create([
            'status' => 'up',
            'cpu_usage' => $cpu,
            'memory_usage' => $memory,
            'disk_usage' => $disk,
            'payload' => [
                'timestamp' => now()->toDateTimeString(),
                'os' => PHP_OS,
            ],
        ]);

        $this->info('System pulse logged successfully.');
    }

    private function getCpuUsage(): float
    {
        if (stristr(PHP_OS, 'win')) {
            return 0.0; // Windows not supported for now
        }

        if (PHP_OS === 'Darwin') {
            // macOS
            $load = sys_getloadavg();
            return (float) ($load[0] * 10); // Simplified CPU impact
        }

        // Linux
        if (file_exists('/proc/loadavg')) {
            $load = sys_getloadavg();
            return (float) ($load[0]);
        }

        return 0.0;
    }

    private function getMemoryUsage(): float
    {
        if (PHP_OS === 'Darwin') {
            // macOS - bit more complex, using simple memory_get_usage for PHP as proxy for now
            return (float) (memory_get_usage(true) / 1024 / 1024);
        }

        if (file_exists('/proc/meminfo')) {
            $free = shell_exec('free');
            $free = (string) trim($free);
            $free_arr = explode("\n", $free);
            $mem = explode(" ", $free_arr[1]);
            $mem = array_filter($mem);
            $mem = array_values($mem);
            return (float) (($mem[2] / $mem[1]) * 100);
        }

        return 0.0;
    }

    private function getDiskUsage(): float
    {
        $path = base_path();
        return (float) (100 - (disk_free_space($path) / disk_total_space($path) * 100));
    }
}
