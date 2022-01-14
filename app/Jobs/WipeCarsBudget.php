<?php

namespace App\Jobs;

use App\Models\EstimatedBudget;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WipeCarsBudget implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected array $wipedData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->wipedData = [
            'original_budget' => 0,
            'budget_left' => 0,
            'last_payment_amount' => null,
        ];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        EstimatedBudget::where('original_budget', '<>', 0)
            ->orWhere('last_payment_amount', '!=', null)
            ->orWhere('budget_left', '<>', 0)
            ->update($this->wipedData);
    }
}
