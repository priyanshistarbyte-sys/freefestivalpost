<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DailyCronJob extends Command
{
    protected $signature = 'cron:daily-job';
    protected $description = 'Daily cron job converted from CodeIgniter';

    public function handle()
    {
        // ===== Existing logic =====
        $this->deleteDaysAfterRemoveUserPost();
        sleep(1);

        $this->userCheckPaidOrStatusChange();
        sleep(1);

        $this->paidUserRemoveFromWhatsAppLog();
        sleep(1);

        $this->cronReportRemove();
        sleep(1);

        // ===== Added CodeIgniter cron logic =====
        $this->morningEightClock();
        sleep(1);

        $this->beforeExpire();
        sleep(1);

        $this->todayExpire();
        sleep(1);

        $this->afterExpire();
        sleep(1);

        $this->afterTrialExpire();
        sleep(1);

        $this->countDailyUserPost();

        $this->info('Daily cron executed successfully');
    }

    /* =====================================================
     | EXISTING FUNCTIONS (UNCHANGED)
     ===================================================== */

    private function deleteDaysAfterRemoveUserPost()
    {
        $daysAgo = Carbon::now()->subDays(15)->toDateString();

        $posts = DB::table('makepost')
            ->where('created_at', '<=', $daysAgo)
            ->get(['id', 'post']);

        $deleteCount = 0;

        foreach ($posts as $post) {
            $filePath = public_path('media/upload/' . $post->post);
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            DB::table('makepost')->where('id', $post->id)->delete();
            $deleteCount++;
        }

        DB::table('setting')
            ->where('option_name', 'totalpost')
            ->increment('value', $deleteCount);

        DB::table('crone_report')->insert([
            'funcation' => 'deleteDaysAfterRemoveUserPost',
            'title' => 'before 15 days post remove',
            'type' => 'delete-post',
            'count' => $deleteCount,
            'created_at' => now(),
        ]);
    }

    private function userCheckPaidOrStatusChange()
    {
        $today = Carbon::today();
        $count = 0;

        $users = DB::table('admin')
            ->where('ispaid', 1)
            ->get(['id', 'expdate']);

        foreach ($users as $user) {
            if ($user->expdate && Carbon::parse($user->expdate)->lt($today)) {
                DB::table('admin')
                    ->where('id', $user->id)
                    ->update([
                        'ispaid' => 0,
                        'planStatus' => 0,
                        'status' => 0,
                    ]);
                $count++;
            }
        }

        DB::table('crone_report')->insert([
            'funcation' => 'userCheckPaidOrStatusChange',
            'title' => 'expire user update',
            'type' => 'update-paid-user',
            'count' => $count,
            'created_at' => now(),
        ]);
    }

    private function paidUserRemoveFromWhatsAppLog()
    {
        $daysAgo = Carbon::now()->subDays(90);

        $deletedOld = DB::table('whatsapp_logs')
            ->whereDate('created_at', '<=', $daysAgo)
            ->delete();

        DB::table('crone_report')->insert([
            'funcation' => 'paidUserRemoveFromWhatsAppLog',
            'title' => '90 days whatsapp log remove',
            'type' => 'whatsapp_log_remove',
            'count' => $deletedOld,
            'created_at' => now(),
        ]);

        $deletedPaid = DB::table('whatsapp_logs as l')
            ->join('admin as a', 'l.mobile', '=', 'a.mobile')
            ->where('a.ispaid', 1)
            ->where('a.planStatus', 2)
            ->where('a.role', 'User')
            ->delete();

        DB::table('crone_report')->insert([
            'funcation' => 'paidUserRemoveFromWhatsAppLog',
            'title' => 'paid user whatsapp log remove',
            'type' => 'whatsapp_paid_remove',
            'count' => $deletedPaid,
            'created_at' => now(),
        ]);
    }

    private function cronReportRemove()
    {
        $daysAgo = Carbon::now()->subDays(30);

        $deleted = DB::table('crone_report')
            ->whereDate('created_at', '<=', $daysAgo)
            ->delete();

        DB::table('crone_report')->insert([
            'funcation' => 'cronReportRemove',
            'title' => 'cron report remove before 30 days',
            'type' => 'cron_report_cleanup',
            'count' => $deleted,
            'created_at' => now(),
        ]);
    }

    /* =====================================================
     | ADDED CODEIGNITER FUNCTIONS
     ===================================================== */

    // morningeighitclock
    private function morningEightClock()
    {
        DB::table('admin')
            ->whereDate('expdate', Carbon::today())
            ->update(['note' => 'Plan expiring today']);
    }

    // beforeExpire (3 days before)
    private function beforeExpire()
    {
        DB::table('admin')
            ->whereDate('expdate', Carbon::today()->addDays(3))
            ->update(['note' => 'Plan expiring in 3 days']);
    }

    // todayExpire
    private function todayExpire()
    {
        DB::table('admin')
            ->whereDate('expdate', Carbon::today())
            ->update([
                'ispaid' => 0,
                'planStatus' => 0,
                'status' => 0,
            ]);
    }

    // afterExpire
    private function afterExpire()
    {
        DB::table('admin')
            ->whereDate('expdate', '<', Carbon::today())
            ->update([
                'ispaid' => 0,
                'planStatus' => 0,
            ]);
    }

    // afterTrialExpire
    private function afterTrialExpire()
    {
        DB::table('admin')
            ->where('planStatus', 1)
            ->whereDate('expdate', '<', Carbon::today())
            ->update([
                'ispaid' => 0,
                'planStatus' => 0,
            ]);
    }

    // countDailyUserPost
    private function countDailyUserPost()
    {
        $today = Carbon::today();

        $users = DB::table('admin')->where('role', 'User')->get();

        foreach ($users as $user) {
            $count = DB::table('makepost')
                ->where('user_id', $user->id)
                ->whereDate('created_at', $today)
                ->count();

            DB::table('daily_post_count')->updateOrInsert(
                [
                    'user_id' => $user->id,
                    'date' => $today,
                ],
                [
                    'count' => $count,
                ]
            );
        }
    }
}
