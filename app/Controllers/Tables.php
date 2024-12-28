<?php namespace App\Controllers;

use App\Models\ModelUsers;
use App\Models\ModelCategory;
use App\Models\ModelTables;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

class Tables extends BaseController
{
	protected $modelUser;
    protected $modelCategory;
    protected $modelTables;

	public function __construct()
    {
        $this->modelUser     = new ModelUsers();
        $this->modelCategory = new ModelCategory();
		$this->modelTables   = new ModelTables();
        helper(['form', 'url']);
    }

	protected function generateQRCode($tableId, $filename)
	{
		// Specify the path where the QR code should be saved
		$qrCodePath = 'assets/img/qr/table/' . $filename;

		// Generate and save the QR code to the specified path
		$result = Builder::create()
			->writer(new PngWriter())
			->data((string) $tableId)
			->encoding(new Encoding('UTF-8'))
			->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
			->size(50)
			->margin(10)
			->roundBlockSizeMode(new RoundBlockSizeModeMargin())
			->build();

		$result->saveToFile($qrCodePath);

		return $qrCodePath;
	}

    public function index()
    {
		$session = \Config\Services::session();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to('login');
        } else {
			if ($session->get('role') === 'Admin') {
				$data['title'] = "Table";
				$data['categories'] = $this->modelCategory->getAllCategories();

				$tables = $this->modelTables->findAll();
				$data['tables'] = $tables;
				$data['num_tables'] = $this->modelTables->countAll();

				return view('templates/admin/header', $data)
                    . view('templates/admin/sidebar', $data)
                    . view('templates/admin/topbar', $data)
                    . view('admin/table', $data)
                    . view('templates/admin/footer');
			}
		}
    }

    public function add()
	{
		$session = \Config\Services::session();

		if (!$session->get('isLoggedIn')) {
			return redirect()->to('login');
		} else {
			if ($session->get('role') !== 'Admin') {
				return redirect()->to('/');
			}
		}

		$data['title'] = "Add Tables";

		if ($this->request->getPost()) {
			$validationRules = [
				'num_tables' => 'required|integer|greater_than[0]'
			];

			if (!$this->validate($validationRules)) {
				$data['validation'] = $this->validator;
				return redirect()->back()->withInput()->with('error', 'Validation failed.');
			}

			$num_tables = (int) $this->request->getPost('num_tables');

			// Fetch the current highest table number
			$lastTable = $this->modelTables->orderBy('table_number', 'DESC')->first();
			$startNumber = $lastTable ? (int)$lastTable['table_number'] + 1 : 1;

			for ($i = 0; $i < $num_tables; $i++) {
				$table_number = $startNumber + $i; // Generate sequential table numbers

				$tableData = [
					'table_number' => (string) $table_number,
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s')
				];

				// Insert data into the tables table using the model
				$inserted = $this->modelTables->insertTable($tableData);

				if (!$inserted) {
					return redirect()->back()->withInput()->with('error', 'Failed to insert table number.');
				}

				// Generate QR code and update QR code path in the database
				$tableId = $this->modelTables->insertID(); // Get the last inserted table ID
				$filename = 'table_' . time() . '.png'; // Example filename generation

				$qrCodePath = $this->generateQRCode($tableId, $filename);

				// Update the QR code path in the database
				$updateData = [
					'qr_code' => $qrCodePath
				];

				$this->modelTables->update($tableId, $updateData);
			}

			return redirect()->to('table')->with('success', 'Tables added successfully.');
		}

		return redirect()->to('table');
	}

	public function delete($table_number)
    {
        $session = \Config\Services::session();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to('login');
        } else {
            if ($session->get('role') === 'Admin') {
                $table = $this->modelTables->find($table_number);
                if (!$table) {
                    $session->setFlashdata('error', 'Menu item not found.');
                    return redirect()->back();
                }

                $this->modelTable->deleteTable((string) $table_number);
				
				$imagePath = 'assets/img/qr/table/' . $table['qr_code'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }

                $session->setFlashdata('success', 'Menu item deleted successfully.');
                return redirect()->back();
            } else {
                return redirect()->to('/');
            }
        }
    }
}
