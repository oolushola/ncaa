<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\aoc;
use App\aircrafts;
use App\focc;
use App\foreignamo;
use App\localamo;
use App\aop;
use App\atl;
use App\pncf;
use App\paas;
use App\atol;
use App\ato;
use App\CertificationTracker;
use App\ForeignAirlineDacl;
use App\Fcop;

class dashboardController extends Controller
{
    function determinant($data, $labelChecker) {
        $active = 0;
        $expiringSoon = 0;
        $expired = 0;
        foreach($data as $object) {
            $now = time();
            $daysDiff = strtotime($object->$labelChecker) - $now;
            $numberofdays = round($daysDiff / (60 * 60 * 24));
            if($numberofdays > 90) 
            {
                $active++;
            }
            elseif(($numberofdays >= 0) && ($numberofdays <=90)) 
            {
                $expiringSoon++;
            }
            else
            {
                $expired++;
            }
        }
        return [$active, $expiringSoon, $expired];
    }

    function expiredRecords($data, $headingOne, $headingTwo, $headingThree, $dateChecker, $tableLabel) {
        $response ='
        <table class="table table-striped">
            <thead class="table-success">
            <tr>
                <th colspan="3" class="table-info" style="color:red">ALL EXPIRED '.$tableLabel.' RECORD</th>
            </tr>
            <tr>
                <th>'.ucwords(str_replace('_', ' ', $headingOne)).'</th>
                <th>'.ucwords(str_replace('_', ' ', $headingTwo)).'</th>
                <th>'.ucwords(str_replace('_', ' ', $headingThree)).'</th>
            </tr>
            </thead>
            <tbody>';
            foreach($data as $object) {
                $counter = 0;
                $now = time();
                $daysDiff = strtotime($object->$dateChecker) - $now;
                $numberofdays = round($daysDiff / (60 * 60 * 24));
                if($numberofdays <= 0) {

                    $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                    $response.='
                    <tr class="'.$css_style.'">
                        <td>'.$object->$headingOne.' <br>('.$numberofdays * -1 .' Days)</td>
                        <td>'.date('d-m-Y', strtotime($object->$headingTwo)).'</td>
                        <td>'.$object->$headingThree.'</td>
                    </tr>';
                }
            }
            $response.='
            </tbody>
        </table>';
        return $response;
    }

    public function aocResult(Request $request) 
    {
        $aocResult = aoc::SELECT('id', 'aoc_holder', 'aoc_certificate_no', 'validity')->GET();
        $status = $this->determinant($aocResult, 'validity');
        $expiredAocs = $this->expiredRecords($aocResult, 'aoc_holder', 'validity', 'aoc_certificate_no', 'validity', 'AOC');
        return array(
            'determinant' => $status,
            'expiredData' => $expiredAocs
        );
    }

    public function acstatusResult(Request $request) {
        $acStatusResult = aircrafts::SELECT('id', 'aircraft_type', 'aircraft_serial_number', 'c_of_a_status')->GET();
        $status = $this->determinant($acStatusResult, 'c_of_a_status');
        $expiredacs = $this->expiredRecords(
            $acStatusResult, 
            'aircraft_type', 
            'c_of_a_status', 
            'aircraft_serial_number', 
            'c_of_a_status', 
            'AC STATUS'
        );
        return array(
            'determinant' => $status,
            'expiredData' => $expiredacs
        );
    }

    public function foccAndMccResult(Request $request) 
    {
        $foccResult = focc::SELECT('id', 'focc_no', 'aircraft_reg_no', 'valid_till')->GET();
        $status = $this->determinant($foccResult, 'valid_till');
        $expiredFoccs = $this->expiredRecords(
            $foccResult, 
            'focc_no', 
            'valid_till', 
            'aircraft_reg_no', 
            'valid_till', 
            'FOCC & MCC'
        );
        return array(
            'determinant' => $status,
            'expiredData' => $expiredFoccs
        );
    }

    public function foreignAmoResult(Request $request)
    {
        $foreignAmoResult = foreignamo::SELECT('id', 'moe_reference', 'amo_number', 'expiry')->GET();
        $status = $this->determinant($foreignAmoResult, 'expiry');
        $expiredForeignAmos = $this->expiredRecords(
            $foreignAmoResult, 
            'moe_reference', 
            'expiry', 
            'amo_number', 
            'expiry', 
            'FOREIGN AMO'
        );
        return array(
            'determinant' => $status,
            'expiredData' => $expiredForeignAmos
        );
    }

    public function localAmoResult(Request $request)
    {
        $localAmoResult = localamo::SELECT('id', 'aoc_holder_id', 'amo_approval_number', 'expiry')->GET();
        $status = $this->determinant($localAmoResult, 'expiry');
        $expiredLocalAmos = $this->expiredRecords(
            $localAmoResult, 
            'aoc_holder_id', 
            'expiry', 
            'amo_approval_number', 
            'expiry', 
            'LOCAL AMO'
        );
        return array(
            'determinant' => $status,
            'expiredData' => $expiredLocalAmos
        );
    }

    public function certificationTrackerResult(Request $request)
    {
        $certificationTrackerResult = CertificationTracker::SELECT('id', 'certification_no', 'applicant_name', 'date_assigned')->GET();
        $status = $this->determinant($certificationTrackerResult, 'date_assigned');
        $expiredCertificationTrackers = $this->expiredRecords(
            $certificationTrackerResult, 
            'certification_no', 
            'date_assigned', 
            'applicant_name', 
            'date_assigned', 
            'CERTIFICATION TRACKER'
        );
        return array(
            'determinant' => $status,
            'expiredData' => $expiredCertificationTrackers
        );
    }

    public function foreignAirlineDaclResult(Request $request) 
    {
        $foreignAirlineDaclResult = ForeignAirlineDacl::SELECT('id', 'dacl_no', 'airline_name', 'aoc_expiry_date')->GET();
        $status = $this->determinant($foreignAirlineDaclResult, 'aoc_expiry_date');
        $expiredForeignAirlineDacl = $this->expiredRecords(
            $foreignAirlineDaclResult, 
            'dacl_no', 
            'aoc_expiry_date', 
            'airline_name', 
            'aoc_expiry_date', 
            'FOREIGN AIRLINE DACL'
        );
        return array(
            'determinant' => $status,
            'expiredData' => $expiredForeignAirlineDacl
        );
    }

    public function fcopResult(Request $request)
    {
        $fcopResult = Fcop::SELECT('id', 'licence_no', 'foreign_airline', 'date_fcop_issued')->GET();
        $status = $this->determinant($fcopResult, 'date_fcop_issued');
        $expiredFcop = $this->expiredRecords(
            $fcopResult, 
            'licence_no', 
            'date_fcop_issued', 
            'foreign_airline', 
            'date_fcop_issued', 
            'FCOP'
        );
        return array(
            'determinant' => $status,
            'expiredData' => $expiredFcop
        );
    }

    public function aopResult(Request $request)
    {
        $aopResult = aop::SELECT('id', 'operator', 'licence_no', 'date_of_expiry')->GET();
        $status = $this->determinant($aopResult, 'date_of_expiry');
        $expiredAops = $this->expiredRecords(
            $aopResult, 
            'operator', 
            'date_of_expiry', 
            'licence_no', 
            'date_of_expiry', 
            'AOP'
        );
        return array(
            'determinant' => $status,
            'expiredData' => $expiredAops
        );
    }

    public function atlResult(Request $request)
    {
        $atlResult = atl::SELECT('id', 'operator', 'licence_no', 'date_of_expiry')->GET();
        $status = $this->determinant($atlResult, 'date_of_expiry');
        $expiredAtls = $this->expiredRecords(
            $atlResult, 
            'operator', 
            'date_of_expiry', 
            'licence_no', 
            'date_of_expiry', 
            'ATL'
        );
        return array(
            'determinant' => $status,
            'expiredData' => $expiredAtls
        );
    }

    public function pncfResult(Request $request)
    {
        $pncfResult = pncf::SELECT('id', 'operator', 'licence_no', 'date_of_expiry')->GET();
        $status = $this->determinant($pncfResult, 'date_of_expiry');
        $expiredPncfs = $this->expiredRecords(
            $pncfResult, 
            'operator', 
            'date_of_expiry', 
            'licence_no', 
            'date_of_expiry', 
            'PNCF'
        );
        return array(
            'determinant' => $status,
            'expiredData' => $expiredPncfs
        );
    }

    public function atolResult(Request $request)
    {
        $atolResult = atol::SELECT('id', 'operator_type', 'licence_no', 'date_of_expiry')->GET();
        $status = $this->determinant($atolResult, 'date_of_expiry');
        $expiredAtols = $this->expiredRecords(
            $atolResult, 
            'operator_type', 
            'date_of_expiry', 
            'licence_no', 
            'date_of_expiry', 
            'ATOL'
        );
        return array(
            'determinant' => $status,
            'expiredData' => $expiredAtols
        );
    }

    public function paasResult(Request $request)
    {
        $paasResult = paas::SELECT('id', 'operator', 'licence_no', 'date_of_expiry')->GET();
        $status = $this->determinant($paasResult, 'date_of_expiry');
        $expiredPaas = $this->expiredRecords(
            $paasResult, 
            'operator', 
            'date_of_expiry', 
            'licence_no', 
            'date_of_expiry', 
            'PAAS'
        );
        return array(
            'determinant' => $status,
            'expiredData' => $expiredPaas
        );
    }

    public function atoResult(Request $request)
    {
        $atoResult = ato::SELECT('id', 'approval_no', 'date_of_first_issue', 'date_of_expiry')->GET();
        $status = $this->determinant($atoResult, 'date_of_expiry');
        $expiredAtos = $this->expiredRecords(
            $atoResult, 
            'approval_no', 
            'date_of_expiry', 
            'date_of_first_issue', 
            'date_of_expiry', 
            'ATO'
        );
        return array(
            'determinant' => $status,
            'expiredData' => $expiredAtos
        );
    }


    
    
    
    
    
    
    
    
    
    
    
    
    
    
}
