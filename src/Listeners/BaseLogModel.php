<?php

namespace Experteam\ApiLaravelCrud\Listeners;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BaseLogModel
{
    protected ?string $action;

    public function register($event): void
    {
        $api = config('experteam-crud.prefix');

        if ($this->validateAction($api)) {
            $this->saveToLogs($event);
            return;
        }

        $this->saveToAudits($event, $api);
    }

    protected function validateAction($api): bool
    {
        $dbConnection = false;
        try {
            DB::connection('mongodb_audits')->getDatabaseName();
            $dbConnection = true;
        } catch (\Exception) {
        }

        return $dbConnection || empty($api);
    }

    protected function saveToLogs($event): void
    {
        $className = class_basename($event->model);

        \ESLog::notice("Model [$className] changed!", [
            'user' => $event->user,
            'model' => $className,
            'old' => $event->old,
            'new' => $event->new,
        ]);
    }

    protected function saveToAudits($event, $api): void
    {
        DB::connection('mongodb_audits')->collection('transaction_logs')->insert([
            'username' => $event->user['username'],
            'api' => Str::headline($api),
            'table' => Str::headline(class_basename($event->model)),
            'action' => $this->action,
            'ID' => (string)$event->model->getKey(),
            'last_value' => $event->old,
            'new_value' => $event->new,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
