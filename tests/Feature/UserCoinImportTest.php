<?php

namespace Tests\Feature;

use App\Models\Admin;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\StartImportingFile;
use App\Models\ImportedFile;
use App\Models\ImportedRow;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;


class UserCoinImportTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        Storage::makeDirectory('imports');
        Artisan::call('cache:clear');
        DB::table('imported_files')->truncate();
        DB::table('imported_rows')->truncate();
        $adminSeeder = new \AdminsTableSeeder();
        $adminSeeder->run();
    }

    public function tearDown()
    {
        Storage::deleteDirectory('imports');
        parent::tearDown();
    }

    private function setupUsers()
    {
        DB::table('user_security_settings')->truncate();
        $userSeeder = new \UsersTableSeeder();
        $userSeeder->run();
    }
    public function testUserUploadFileWithWrongColumnFormat()
    {
        $admin = Admin::first();

        $mock_file = __DIR__ . '/test-files/import_user_coin_test_fail_header.xlsx';
        $uploaded_mock_file = new UploadedFile($mock_file, 'import_user_coin.xlsx', null, null, null, true);
        $response = $this->actingAs($admin, 'api')->json('POST', 'api/import/upload_user_coin', [
            'file' => $uploaded_mock_file,
        ]);
        $response->assertStatus(422);
    }
    public function testUserUploadExcelsFile()
    {
        $admin = Admin::first();
        Excel::fake();
        $mock_file = __DIR__ . '/test-files/import_user_coin_no_rows.xlsx';
        $uploaded_mock_file = new UploadedFile($mock_file, 'import_user_coin_no_rows.xlsx', null, null, null, true);
        $response = $this->actingAs($admin, 'api')->json('POST', 'api/import/upload_user_coin', [
            'file' => $uploaded_mock_file,
        ]);

        $response->assertStatus(200);

        $directory = Storage::disk('local')->files('imports');

        $this->assertEquals(count($directory), 1);

        $stored_mock_file = $directory[0];

        $this->assertDatabaseHas('imported_files', [
            'path' => $stored_mock_file,
            'filename' => 'import_user_coin_no_rows.xlsx',
            'admin_id' => $admin->id,
            'type' => 'user_coin',
        ]);
        //Excel::assertImported($mock_file, 'local');
    }
    public function testUserReploadExcelsFile()
    {
        $admin = Admin::first();
        Excel::fake();
        $mock_file = __DIR__ . '/test-files/reimport_user_coin.xlsx';
        $uploaded_mock_file = new UploadedFile($mock_file, 'reimport_user_coin.xlsx', null, null, null, true);
        $response = $this->actingAs($admin, 'api')->json('POST', 'api/import/upload_user_coin', [
            'file' => $uploaded_mock_file,
        ]);
        $response->assertStatus(200);

        $directory = Storage::disk('local')->files('imports');

        $this->assertEquals(count($directory), 1);

        $stored_mock_file = $directory[0];

        $this->assertDatabaseHas('imported_files', [
            'path' => $stored_mock_file,
            'filename' => 'reimport_user_coin.xlsx',
            'admin_id' => $admin->id,
            'type' => 'user_coin_reupload'
        ]);
        //Excel::assertImported($mock_file, 'local');
    }
    public function testDispatchImportUserCoinFromFile()
    {
        $admin = Admin::first();
        $mock_file = __DIR__ . '/test-files/import_user_coin_success.xlsx';
        $mock_storage_file = Storage::disk('local')->putFile('imports', new File($mock_file));
        dispatch(new StartImportingFile($mock_storage_file, 'import_user_coin_success.xlsx', 'user_coin', $admin->id));

        // test first row data
        $first_row = ImportedRow::where('row_index', 2)->first();

        $this->assertEquals($first_row->fields(), [
            'id' => 1234,
            'email' => 'okazoe-yuichiro@cryptopie.co.jp',
            'currency' => 'BTC',
            'amount' => 0.12345678
        ]);

        $last_row =  ImportedRow::where('row_index', 14)->first();
        $this->assertEquals($last_row->fields(), [
            'id' => 1246,
            'email' => 'okazoe-yuichiro@cryptopie.co.jp',
            'currency' => 'BTC',
            'amount' => 0.12345678
        ]);
    }
    public function testFileValidation()
    {
        $admin = Admin::first();
        $mock_file = __DIR__ . '/test-files/import_user_coin_validation.xlsx';
        $mock_storage_file = Storage::disk('local')->putFile('imports', new File($mock_file));
        dispatch(new StartImportingFile($mock_storage_file, 'import_user_coin_validation.xlsx', 'user_coin', $admin->id));
        // test first row data
        $correct_row = ImportedRow::where('row_index', 2)->first();

        $this->assertEquals($correct_row->fields(), [
            'id' => 1234,
            'email' => 'okazoe-yuichiro@cryptopie.co.jp',
            'currency' => 'BTC',
            'amount' => 0.12345678
        ]);
    }
    public function testImportedRowValidationRules()
    {
        $this->setupUsers();
        $user = User::first();
        $user->securitySetting->email_verified = true;
        $user->securitySetting->otp_verified = true;
        $user->securitySetting->save();
        $row_is_validated = ImportedRow::create([
            'row_index' => 1,
            'raw_data' => ['1234', $user->email, 'BTC', '0.123456789'],
            'imported_file_id' => 1,
            'type' => 'user_coin',
        ]);

        $this->assertEquals($row_is_validated->id, 1234);
        $this->assertEquals($row_is_validated->email, $user->email);
        $this->assertEquals($row_is_validated->currency, 'BTC');
        $this->assertEquals($row_is_validated->amount, 0.123456789);

        $row_has_uppercase_currency = ImportedRow::create([
            'row_index' => 1,
            'raw_data' => ['1234', $user->email, 'BTC2', '0.123456789'],
            'imported_file_id' => 1,
            'type' => 'user_coin',
        ]);
        $this->assertEquals($row_has_uppercase_currency->errors, [
            "currency" => [
                "validation.currency_exists"
            ]
        ]);
        $row_has_lowercase_currency = ImportedRow::create([
            'row_index' => 2,
            'raw_data' => ['1235', $user->email, 'btc3', '0.123456789'],
            'imported_file_id' => 1,
            'type' => 'user_coin',
        ]);
        $this->assertEquals($row_has_lowercase_currency->errors, [
            "currency" => [
                "validation.currency_exists"
            ]
        ]);
        $row_has_null_id = ImportedRow::create([
            'row_index' => 3,
            'raw_data' => [null, $user->email, 'btc', '0.123456789'],
            'imported_file_id' => 1,
            'type' => 'user_coin',
        ]);
        $this->assertEquals($row_has_null_id->errors, [
            "id" => [
                'validation.required',
            ]
        ]);

        $row_has_null_amount = ImportedRow::create([
            'row_index' => 4,
            'raw_data' => ['1237', $user->email, 'btc', null],
            'imported_file_id' => 1,
            'type' => 'user_coin',
        ]);
        $this->assertEquals($row_has_null_amount->errors, [
            "amount" => [
                'validation.required',
            ],
        ]);

        $row_has_not_numeric_amount = ImportedRow::create([
            'row_index' => 5,
            'raw_data' => ['1237', $user->email, 'btc', 'aa'],
            'imported_file_id' => 1,
            'type' => 'user_coin',
        ]);
        $this->assertEquals($row_has_not_numeric_amount->errors, [
            "amount" => [
                'validation.numeric',
            ],
        ]);
        $row_has_null_email = ImportedRow::create([
            'row_index' => 5,
            'raw_data' => ['1237', null, 'btc', '0.12345678'],
            'imported_file_id' => 1,
            'type' => 'user_coin',
        ]);
        $this->assertEquals($row_has_null_email->errors, [
            "email" => [
                'validation.required',
            ],
        ]);
        $this->assertEquals($row_has_null_email->email, null);
        $row_has_not_exists_email = ImportedRow::create([
            'row_index' => 5,
            'raw_data' => ['1237', str_random(10) . '@abc.com', 'btc', '0.12345678'],
            'imported_file_id' => 1,
            'type' => 'user_coin',
        ]);
        $this->assertEquals($row_has_not_exists_email->errors, [
            "email" => [
                'user_not_exists',
            ],
        ]);
    }
    public function testDispatchImportUserCoinSplitByBatch()
    {
        $admin = Admin::first();
        $mock_file = __DIR__ . '/test-files/import_user_coin_big_chunk.xlsx';
        $mock_storage_file = Storage::disk('local')->putFile('imports', new File($mock_file));

        dispatch(new StartImportingFile($mock_storage_file, 'import_user_coin_big_chunk.xlsx', 'user_coin', $admin->id));

        $first_row = ImportedRow::where('row_index', 2)->first();

        $this->assertEquals($first_row->fields(), [
            'id' => 1234,
            'email' => 'okazoe-yuichiro@cryptopie.co.jp',
            'currency' => 'BTC',
            'amount' => 0.12345678
        ]);
        $last_row = ImportedRow::where('row_index', 1112)->first();
        $this->assertEquals($last_row->fields(), [
            'id' => null,
            'email' => 'okazoe-yuichiro@cryptopie.co.jp',
            'currency' => null,
            'amount' => null
        ]);
    }

    public function testUserGetImportedFiles()
    {
        Excel::fake();

        $admin = Admin::first();

        dispatch_now(new StartImportingFile(__DIR__ . '/test-files/import_user_coin_validation.xlsx', 'import_user_coin_validation.xlsx', 'user_coin', $admin->id));
        $response_1 = $this->actingAs($admin, 'api')->json('GET', 'api/import/imported_file');

        $response_1->assertJsonFragment([
            "filename" => 'import_user_coin_validation.xlsx',
        ]);

        dispatch_now(new StartImportingFile(__DIR__ . '/test-files/import_user_coin_success.xlsx', 'import_user_coin_success.xlsx', 'user_coin', $admin->id));

        $response_2 = $this->actingAs($admin, 'api')->json('GET', 'api/import/imported_file');
        $response_2->assertJson([
            "data" => [
                "total" => 2,
            ]

        ]);
    }
    public function testUserGetImportedFileRecord()
    {

        $admin = Admin::first();
        $mock_file = __DIR__ . '/test-files/import_user_coin_success.xlsx';
        $mock_storage_file = Storage::disk('local')->putFile('imports', new File($mock_file));
        dispatch(new StartImportingFile($mock_storage_file, 'import_user_coin_success.xlsx', 'user_coin', $admin->id));

        $imported = ImportedFile::first();

        $assert_404 = $this->actingAs($admin, 'api')->json('GET', 'api/import/imported_file/40');
        $assert_404->assertStatus(404);
        $response = $this->actingAs($admin, 'api')->json('GET', 'api/import/imported_file/' . $imported->id);
        $response->assertStatus(200)->assertJsonFragment([
            "filename" => 'import_user_coin_success.xlsx',
        ]);
        $rows_request = $this->actingAs($admin, 'api')->json('GET', 'api/import/imported_file/' . $imported->id . '/rows');
        $rows_request->assertStatus(200)->assertJson([
            "data" => [
                "total" => 13
            ]
        ]);
    }
}
