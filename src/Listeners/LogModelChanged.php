<?php

namespace Experteam\ApiLaravelCrud\Listeners;

use Experteam\ApiLaravelCrud\Events\ModelChanged;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LogModelChanged
{
    /**
     * Handle the event.
     *
     * @param ModelChanged $event
     * @return void
     */
    public function handle(ModelChanged $event): void
    {
        $api = config('experteam-crud.prefix');
        $dbConnection = false;
        try {
            DB::connection('mongodb_audits')->getDatabaseName();
            $dbConnection = true;
        } catch (\Exception) {
        }
        if (empty($api) || !$dbConnection) {
            $className = class_basename($event->model);

            \ESLog::notice("Model [$className] changed!", [
                'user' => $event->user,
                'model' => $className,
                'old' => $event->old,
                'new' => $event->new,
            ]);
            return;
        }

        $action = !empty($event->old) && !empty($event->new) ? 'UPDATE' : (empty($event->old) ? 'INSERT' : 'DELETE');

        DB::connection('mongodb_audits')->collection('transaction_logs')->insert([
            'username' => $event->user['username'],
            'api' => Str::headline($api),
            'table' => Str::headline(class_basename($event->model)),
            'action' => $action,
            'ID' => (string)$event->model->getKey(),
            'last_value' => $event->old,
            'new_value' => $event->new,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
