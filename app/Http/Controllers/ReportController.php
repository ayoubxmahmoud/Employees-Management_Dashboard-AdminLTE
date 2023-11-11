<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Auth;
use Excel;
use PDF;

class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        date_default_timezone_set('asia/ho_chi_minh');
        $format = 'Y/m/d';
        $now = date($format);
        $to = date($format, strtotime("+30 days"));
        $constraints = [    
            'from' => $now,
            'to' => $to
        ];

        $employees = $this->getHiredEmployee($constraints);
        return view('system-mgmt/report/index', ['employees' => $employees, 'searchingVals' => $constraints]);
    }

    public function exportExcel(Request $request) {
        return $this->prepareExportingData($request);
    }
    

    public function exportPDF(Request $request) {
         $constraints = [
            'from' => $request['from'],
            'to' => $request['to']
        ];
        $employees = $this->getExportingData($constraints);
        $pdf = PDF::loadView('system-mgmt/report/pdf', ['employees' => $employees, 'searchingVals' => $constraints]);
        return $pdf->download('report_from_' . $request['from'] . '_to_' . $request['to'] . '.pdf');
    }
    
    private function prepareExportingData($request) {
        $author = Auth::user()->username;
        $employee = $this->getExportingData(['from' => $request['from'], 'to' => $request['to']]);

        $from = str_replace(['/', '\\'], '_', $request['from']);
        $to = str_replace(['/', '\\'], '_', $request['to']);
        $filename = 'report_from_' . $from . '_to_' . $to . '.xlsx';
        
        return Excel::download(function ($excel) use ($employee, $request, $author) {
            $excel->setTitle('List of hired employee from ' . $request['from'] . ' to ' . $request['to']);
            $excel->setCreator($author)->setCompany('HoaDang');
            $excel->setDescription('The list of hired employee');
            $excel->sheet('Hired_Employee', function ($sheet) use ($employee) {
                $sheet->fromArray($employee);
            });
        }, $filename);
    }

    public function search(Request $request) {
        $constraints = [
            'from' => $request['from'],
            'to' => $request['to']
        ];

        $employees = $this->getHiredEmployee($constraints);
        return view('system-mgmt/report/index', ['employees' => $employees, 'searchingVals' => $constraints]);
    }

    private function getHiredEmployee($constraints) {
        $employees = Employee::where('date_hired', '>=', $constraints['from'])
                        ->where('date_hired', '<=', $constraints['to'])
                        ->get();
        return $employees;
    }

    private function getExportingData($constraints) {
        return DB::table('employee')
        ->leftJoin('city', 'employee.city_id', '=', 'city.id')
        ->leftJoin('department', 'employee.department_id', '=', 'department.id')
        ->leftJoin('state', 'employee.state_id', '=', 'state.id')
        ->leftJoin('country', 'employee.country_id', '=', 'country.id')
        ->leftJoin('division', 'employee.division_id', '=', 'division.id')
        ->select('employee.firstname', 'employee.middlename', 'employee.lastname', 
        'employee.age','employee.birthdate', 'employee.address', 'employee.zip', 'employee.date_hired',
        'department.name as department_name', 'division.name as division_name')
        ->where('date_hired', '>=', $constraints['from'])
        ->where('date_hired', '<=', $constraints['to'])
        ->get()
        ->map(function ($item, $key) {
        return (array) $item;
        })
        ->all();
    }
}
