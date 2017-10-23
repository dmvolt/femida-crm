<?php

namespace App\Console\Commands;

use App\Notifications\BrowserNotification;
use App\TaskNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Log;

class CreateTaskNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:CreateTaskNotification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Task Notification';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach (TaskNotification::whereCreated('no')->where('datetime', '>=', Carbon::now())->with('task.user')->get() as $notification)
        {
            $title = "<a href='".route('tasks.view', ['taskId' => $notification->task->id])."'>Напоминание к задаче №".$notification->task->id."</a>";
            $data = [
                'title' => $title,
                'message' => $notification->text,
                'type' => 'warning'
            ];

            $notification->task->user->notify(new BrowserNotification($data));
            $notification->setCreated();
        }
    }
}
