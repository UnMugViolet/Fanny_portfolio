<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Orchid\Attachment\Models\Attachment;

class AttachmentClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attachment:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all the unattached files from the storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $unrelatedAttachments = Attachment::doesntHave('relationships')
            ->whereDate('created_at', '<', now()->subDay(2))
            ->get();

        $this->info('Starting cleanup of unattached files. Found ' . $unrelatedAttachments->count() . ' files to delete.');
        Log::info('Starting cleanup of unattached files. Found ' . $unrelatedAttachments->count() . ' files to delete.');
        foreach ($unrelatedAttachments as $attachment) {
            $this->line('Deleting image at: ' . $attachment->path . $attachment->original_name . '.' . $attachment->extension);
        }

        $unrelatedAttachments->each->delete();

        return Command::SUCCESS;
    }
}
