<?php

namespace Tests\Feature;

use App\Http\Services\MasterdataService;
use App\Models\Admin;
use App\Models\Transaction;
use App\Models\User;
use App\Utils;
use App\Utils\BigNumber;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ConfirmWithdrawalTest extends TestCase
{
    const BALANCE = 250000;
    protected $user;
    protected $admin;
    protected $currency = 'btc';
    protected $toAddress = '1F1tAaz5x1HUXrCNLbtMDqcw6o5GNn4xqW';
    protected $fromAddress = '1F1tAaz5x1HUXrCNLbtMDqcw6o5GNn4xqX';
    protected $amount = '-1';

    public function setUp()
    {
        parent::setUp();
        Artisan::call('cache:clear');
        $this->updateMasterData();
        $this->clearData();

        $usersSeeder = new \UsersTableSeeder();
        $usersSeeder->run();

        $adminSeeder = new \AdminsTableSeeder();
        $adminSeeder->run();

        $this->admin = Admin::first();
        $this->user = User::first();

        $this->setUpBalance();
    }

    protected function clearData()
    {
        DB::table('user_security_settings')->truncate();
        DB::table('transactions')->truncate();
    }

    protected function setUpBalance()
    {
        DB::table($this->currency.'_accounts')
            ->where('id', $this->user->id)
            ->update(['balance' => self::BALANCE, 'available_balance' => self::BALANCE]);
    }

    public function updateMasterData()
    {
        $BATCH_SIZE = 500;
        try {
            DB::beginTransaction();
            $language = null;
            $filename = empty($language) ? 'latest.json' : 'latest_'.$language.'.json';
            $path = storage_path('masterdata/'.$filename);
            $jsonData = json_decode(file_get_contents($path), true);
            foreach ($jsonData as $table => $values) {
                if (Schema::hasTable($table)) {
                    // printf("Update table: %s\n", $table);
                    DB::table($table)->delete();

                    $chunks = array_chunk($values, $BATCH_SIZE);
                    for ($chunkIndex = 0; $chunkIndex < count($chunks); $chunkIndex++) {
                        $chunk = $chunks[$chunkIndex];
                        DB::table($table)->insert($chunk);
                    }
                }
            }

            DB::commit();
            Cache::flush();
        } catch (Exception $e) {
            DB::rollback();
            printf($e);
        }
    }

    public function getWithdrawLimit()
    {
        // Check limitation and fee
        $currency = $this->currency;
        $withdrawLimits = MasterdataService::getOneTable('withdrawal_limits');
        $withdrawLimit = collect($withdrawLimits)->first(function ($value) use ($currency) {
            return $value->security_level === 1
                && $value->currency == $currency;
        });
        return $withdrawLimit;
    }

    private function createWithdrawTransaction($withdrawLimit, $status)
    {
        $transaction = new Transaction();
        $transaction->fill([
            'transaction_id' => str_limit(strtoupper(bin2hex(random_bytes(20))), 34, ''),
            'user_id' => $this->user->id,
            'currency' => $this->currency,
            'amount' => $this->amount,
            'fee' => $withdrawLimit->fee,
            'from_address' => $this->fromAddress,
            'to_address' => $this->toAddress,
            'status' => $status,
            'transaction_date' => Carbon::now(),
            'created_at' => Utils::currentMilliseconds(),
            'updated_at' => Utils::currentMilliseconds(),
        ]);

        $transaction->is_external = true;
        $transaction->save();

        return $transaction;
    }

    private function updateUserAvaiableBalanceRaw()
    {
        DB::table($this->currency . '_accounts')
            ->where('id', $this->user->id)
            ->update([
                'available_balance' => DB::raw('available_balance + ' . $this->amount),
            ]);
    }

    /**
     * @dataProvider dataTestConfirmWithdraw
     */
    public function testConfirmWithdraw($action, $expected)
    {
        $withdrawLimit = $this->getWithdrawLimit($this->currency);
        $transaction = $this->createWithdrawTransaction($withdrawLimit, Transaction::PROCESSING_STATUS);
        $this->updateUserAvaiableBalanceRaw();

        $params = [
            "action" => $action,
            "transaction_id" => $transaction->transaction_id
        ];

        $this->actingAs($this->admin, 'api')->json('PUT', '/api/transactions/approve', $params);

        $transactionStatus = Transaction::where('transaction_id', $transaction->transaction_id)
            ->where('amount', '<', 0)
            ->value('status');

        $this->assertEquals($expected, $transactionStatus);

        $userBalance = DB::table($this->currency . '_accounts')
                ->where('id', $this->user->id)
                ->first();

        if ($expected === Transaction::REJECTED_STATUS) {
            $this->assertEquals(BigNumber::new(self::BALANCE)->toString(), $userBalance->balance);
            $this->assertEquals(BigNumber::new(self::BALANCE)->toString(), $userBalance->available_balance);
            return;
        }

        $amount = BigNumber::new(-1)->mul($this->amount)->toString();
        $this->assertEquals(self::BALANCE, $userBalance->balance);
        $this->assertEquals(BigNumber::new(self::BALANCE)->sub($amount), $userBalance->available_balance);
    }

    public function dataTestConfirmWithdraw()
    {
        return [
            "verified" => [
                "action" => "verified",
                "expected" => "approved"
            ],
            "rejected" => [
                "action" => "rejected",
                "expected" => "rejected"
            ]
        ];
    }

    public function testConfirmWithdrawSuccess()
    {
        $withdrawLimit = $this->getWithdrawLimit($this->currency);
        $transaction = $this->createWithdrawTransaction($withdrawLimit, Transaction::APPROVED_STATUS);
        $this->updateUserAvaiableBalanceRaw();

        $params = [
            "transaction_id" => $transaction->transaction_id,
            "tx_hash" => '',
            "remarks" => '',
            "send_confirmer1" => '',
            "send_confirmer2" => '',
        ];

        $response = $this->actingAs($this->admin, 'api')->json('POST', '/api/transactions/registration-remittance', $params);

        $transactionStatus = Transaction::where('transaction_id', $transaction->transaction_id)
            ->where('amount', '<', 0)
            ->value('status');

        $this->assertEquals('success', $transactionStatus);

        $userBalance = DB::table($this->currency . '_accounts')
                ->where('id', $this->user->id)
                ->first();

        $amount = BigNumber::new(-1)->mul($this->amount)->toString();
        $this->assertEquals(BigNumber::new(self::BALANCE)->sub($amount), $userBalance->balance);
        $this->assertEquals(BigNumber::new(self::BALANCE)->sub($amount), $userBalance->available_balance);
    }
}
