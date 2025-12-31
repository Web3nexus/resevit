<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Domain;

class UpdateTenantDomains extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:update-domains {--old-domain=resevit.test : The domain to replace}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all tenant domains in the database to match the current APP_URL';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $oldDomain = $this->option('old-domain');
        $newDomain = parse_url(config('app.url'), PHP_URL_HOST);

        if (!$newDomain) {
            $this->error('APP_URL is not set correctly in .env');
            return 1;
        }

        $this->info("Migrating tenant domains from {$oldDomain} to {$newDomain}...");

        $domains = DB::connection('landlord')
            ->table('domains')
            ->where('domain', 'like', "%{$oldDomain}")
            ->get();

        if ($domains->isEmpty()) {
            $this->warn("No domains found matching *{$oldDomain}");
            return 0;
        }

        $count = 0;
        foreach ($domains as $domainRecord) {
            $newDomainName = str_replace($oldDomain, $newDomain, $domainRecord->domain);

            DB::connection('landlord')
                ->table('domains')
                ->where('id', $domainRecord->id)
                ->update([
                    'domain' => $newDomainName,
                    'updated_at' => now(),
                ]);

            $this->line("Updated: {$domainRecord->domain} -> {$newDomainName}");
            $count++;
        }

        $this->info("Successfully updated {$count} domains.");

        $this->warn("Clearing cache...");
        \Artisan::call('config:clear');
        \Artisan::call('cache:clear');

        return 0;
    }
}
