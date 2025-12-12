<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetAdminPassword extends Command
{
    protected $signature = 'admin:reset-password {email=admin@resevit.com}';
    protected $description = 'Reset admin password';

    public function handle()
    {
        $email = $this->argument('email');
        
        $admin = Admin::where('email', $email)->first();
        
        if (!$admin) {
            $this->error("Admin with email {$email} not found!");
            $this->info("Creating admin account...");
            
            $admin = Admin::create([
                'name' => 'Super Admin',
                'email' => $email,
                'password' => Hash::make('password'),
            ]);
            
            if (method_exists($admin, 'assignRole')) {
                $admin->assignRole('securegate_admin');
            }
            
            $this->info("Admin created successfully!");
        } else {
            $admin->password = Hash::make('password');
            $admin->save();
            $this->info("Password reset successfully!");
        }
        
        $this->info("Email: {$email}");
        $this->info("Password: password");
        $this->warn("You can now login at /securegate");
        
        return 0;
    }
}
