<?php

namespace App\Observers;

use App\Models\Project;

class ProjectObserver
{
    public function deleting(Project $project)
    {
        $project->attachments->each->delete();
    }
}
