<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\StartImportingFile;
use App\Jobs\StartExportingFile;
use App\Models\ImportedFile;
use App\Models\ImportedRow;
use Illuminate\Support\Facades\Artisan;


class UserCoinExportTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        Storage::makeDirectory('imports');
        Storage::disk('public')->makeDirectory('exports');
        Artisan::call('cache:clear');
        DB::table('imported_files')->truncate();
        DB::table('imported_rows')->truncate();
        $adminSeeder = new \AdminsTableSeeder();
        $adminSeeder->run();
    }

    public function tearDown()
    {
        Storage::deleteDirectory('imports');
        Storage::disk('public')->deleteDirectory('exports');
        parent::tearDown();
    }
    private function setUsers()
    {
        DB::table('user_security_settings')->truncate();
        $userSeeder = new \UsersTableSeeder();
        $userSeeder->run();
    }
    private function setBatchFile()
    {
        $admin = Admin::first();
        $mock_file = __DIR__ . '/test-files/import_user_coin_big_chunk.xlsx';
        $mock_storage_file = Storage::disk('local')->putFile('imports', new File($mock_file));

        dispatch(new StartImportingFile($mock_storage_file, str_random(10) . '.xlsx', 'user_coin', $admin->id));
    }

    public function testExportedFileJob()
    {
        $this->setUsers();
        $user = User::first();
        $user->email = 'okazoe-yuichiro@cryptopie.co.jp';
        $user->save();
        $this->setBatchFile();
        $importedFile = ImportedFile::first();
        dispatch(new StartExportingFile($importedFile));

        $this->assertEquals(count(Storage::disk('public')->files('exports')), 1);
    }
}
