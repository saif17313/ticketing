<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixNullJourneyDates extends Command
{
    protected $signature = 'fix:journey-dates';
    protected $description = 'Fix bus schedules with NULL journey_date';

    public function handle()
    {
        $this->info('Checking for schedules with NULL journey_date...');
        
        // Count schedules with NULL journey_date
        $count = DB::table('bus_schedules')->whereNull('journey_date')->count();
        
        $this->info("Found {$count} schedules with NULL journey_date");
        
        if ($count > 0) {
            $this->warn('Deleting schedules with NULL journey_date...');
            DB::table('bus_schedules')->whereNull('journey_date')->delete();
            $this->info('✅ Deleted schedules with NULL journey_date');
            
            $this->info('You should now run: php artisan db:seed --class=ScheduleSeeder');
        } else {
            $this->info('✅ No issues found. All schedules have journey_date.');
        }
        
        return 0;
    }
}
