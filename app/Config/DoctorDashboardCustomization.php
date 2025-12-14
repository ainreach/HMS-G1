<?php

namespace Config;

/**
 * Doctor Dashboard Customization Configuration
 * Define which widgets/sections to show for each specialization
 */
class DoctorDashboardCustomization
{
    /**
     * Get dashboard widgets configuration for a specialization
     * 
     * @param string $specialization
     * @return array
     */
    public static function getWidgets(string $specialization): array
    {
        $config = self::getConfig();
        
        // Get specialization category
        $category = self::getSpecializationCategory($specialization);
        
        // Return widgets for this category, or default
        return $config[$category] ?? $config['default'];
    }
    
    /**
     * Get specialization category
     */
    private static function getSpecializationCategory(string $specialization): string
    {
        // Pediatrics
        if (stripos($specialization, 'pediatric') !== false || 
            stripos($specialization, 'neonatologist') !== false) {
            return 'pediatrics';
        }
        
        // Surgery
        if (stripos($specialization, 'surgeon') !== false || 
            stripos($specialization, 'surgical') !== false) {
            return 'surgery';
        }
        
        // Cardiology
        if (stripos($specialization, 'cardio') !== false) {
            return 'cardiology';
        }
        
        // Emergency/Critical Care
        if (stripos($specialization, 'emergency') !== false || 
            stripos($specialization, 'critical care') !== false ||
            stripos($specialization, 'intensivist') !== false) {
            return 'emergency';
        }
        
        // Radiology
        if (stripos($specialization, 'radiologist') !== false) {
            return 'radiology';
        }
        
        // OB-GYN
        if (stripos($specialization, 'obstetrician') !== false || 
            stripos($specialization, 'gynecologist') !== false ||
            stripos($specialization, 'ob-gyn') !== false) {
            return 'obgyn';
        }
        
        // Psychiatry
        if (stripos($specialization, 'psychiatrist') !== false) {
            return 'psychiatry';
        }
        
        // Default
        return 'default';
    }
    
    /**
     * Get all widget configurations
     */
    private static function getConfig(): array
    {
        return [
            // DEFAULT - General Practitioner, Family Medicine, Internist
            'default' => [
                'kpis' => [
                    'today_appointments' => true,
                    'pending_lab_results' => true,
                    'assigned_patients' => true,
                    'total_consultations' => false,
                ],
                'sections' => [
                    'quick_actions' => true,
                    'today_appointments' => true,
                    'assigned_patients' => true,
                    'weekly_schedule' => true,
                    'recent_medical_records' => true,
                    'pending_lab_tests' => true,
                ],
                'quick_actions' => [
                    'new_medical_record' => true,
                    'request_lab_test' => true,
                    'view_patients' => true,
                    'admit_patient' => true,
                    'prescriptions' => true,
                    'lab_results' => true,
                ],
            ],
            
            // PEDIATRICS - Pediatrician, Neonatologist, etc.
            'pediatrics' => [
                'kpis' => [
                    'today_appointments' => true,
                    'pending_lab_results' => true,
                    'assigned_patients' => true,
                    'pediatric_patients' => true, // Custom KPI
                    'total_consultations' => false,
                ],
                'sections' => [
                    'quick_actions' => true,
                    'today_appointments' => true,
                    'assigned_patients' => true,
                    'pediatric_patients' => true, // Custom section
                    'weekly_schedule' => true,
                    'recent_medical_records' => true,
                    'pending_lab_tests' => true,
                    'vaccination_schedule' => true, // Custom for pediatrics
                ],
                'quick_actions' => [
                    'new_medical_record' => true,
                    'request_lab_test' => true,
                    'view_patients' => true,
                    'admit_patient' => true,
                    'prescriptions' => true,
                    'lab_results' => true,
                    'vaccination_record' => true, // Custom action
                ],
            ],
            
            // SURGERY - All surgeons
            'surgery' => [
                'kpis' => [
                    'today_appointments' => false,
                    'pending_lab_results' => true,
                    'assigned_patients' => true,
                    'scheduled_surgeries' => true, // Custom KPI
                    'total_consultations' => false,
                ],
                'sections' => [
                    'quick_actions' => true,
                    'today_appointments' => false,
                    'assigned_patients' => true,
                    'surgery_schedule' => true, // Custom section
                    'weekly_schedule' => true,
                    'recent_medical_records' => true,
                    'pending_lab_tests' => true,
                    'pre_op_patients' => true, // Custom section
                ],
                'quick_actions' => [
                    'new_medical_record' => true,
                    'request_lab_test' => true,
                    'view_patients' => true,
                    'admit_patient' => true,
                    'prescriptions' => true,
                    'lab_results' => true,
                    'schedule_surgery' => true, // Custom action
                ],
            ],
            
            // CARDIOLOGY - Cardiologist, Cardiac Surgeon
            'cardiology' => [
                'kpis' => [
                    'today_appointments' => true,
                    'pending_lab_results' => true,
                    'assigned_patients' => true,
                    'ecg_pending' => true, // Custom KPI
                    'total_consultations' => false,
                ],
                'sections' => [
                    'quick_actions' => true,
                    'today_appointments' => true,
                    'assigned_patients' => true,
                    'cardiac_patients' => true, // Custom section
                    'weekly_schedule' => true,
                    'recent_medical_records' => true,
                    'pending_lab_tests' => true,
                    'ecg_results' => true, // Custom section
                ],
                'quick_actions' => [
                    'new_medical_record' => true,
                    'request_lab_test' => true,
                    'view_patients' => true,
                    'admit_patient' => true,
                    'prescriptions' => true,
                    'lab_results' => true,
                    'request_ecg' => true, // Custom action
                ],
            ],
            
            // EMERGENCY - Emergency Medicine, Critical Care
            'emergency' => [
                'kpis' => [
                    'today_appointments' => false,
                    'pending_lab_results' => true,
                    'assigned_patients' => true,
                    'emergency_cases' => true, // Custom KPI
                    'total_consultations' => false,
                ],
                'sections' => [
                    'quick_actions' => true,
                    'today_appointments' => false,
                    'assigned_patients' => true,
                    'emergency_cases' => true, // Custom section
                    'weekly_schedule' => false, // Not relevant for ER
                    'recent_medical_records' => true,
                    'pending_lab_tests' => true,
                    'active_emergencies' => true, // Custom section
                ],
                'quick_actions' => [
                    'new_medical_record' => true,
                    'request_lab_test' => true,
                    'view_patients' => true,
                    'admit_patient' => true,
                    'prescriptions' => true,
                    'lab_results' => true,
                    'emergency_admit' => true, // Custom action
                ],
            ],
            
            // RADIOLOGY - Radiologist
            'radiology' => [
                'kpis' => [
                    'today_appointments' => false,
                    'pending_lab_results' => false,
                    'assigned_patients' => false,
                    'imaging_requests' => true, // Custom KPI
                    'completed_scans' => true, // Custom KPI
                ],
                'sections' => [
                    'quick_actions' => true,
                    'today_appointments' => false,
                    'assigned_patients' => false,
                    'imaging_requests' => true, // Custom section
                    'weekly_schedule' => true,
                    'recent_medical_records' => false,
                    'pending_lab_tests' => false,
                    'completed_reports' => true, // Custom section
                ],
                'quick_actions' => [
                    'new_medical_record' => false,
                    'request_lab_test' => false,
                    'view_patients' => true,
                    'admit_patient' => false,
                    'prescriptions' => false,
                    'lab_results' => false,
                    'view_imaging_requests' => true, // Custom action
                    'upload_report' => true, // Custom action
                ],
            ],
            
            // OB-GYN - Obstetrician-Gynecologist
            'obgyn' => [
                'kpis' => [
                    'today_appointments' => true,
                    'pending_lab_results' => true,
                    'assigned_patients' => true,
                    'prenatal_patients' => true, // Custom KPI
                    'total_consultations' => false,
                ],
                'sections' => [
                    'quick_actions' => true,
                    'today_appointments' => true,
                    'assigned_patients' => true,
                    'prenatal_patients' => true, // Custom section
                    'weekly_schedule' => true,
                    'recent_medical_records' => true,
                    'pending_lab_tests' => true,
                    'delivery_schedule' => true, // Custom section
                ],
                'quick_actions' => [
                    'new_medical_record' => true,
                    'request_lab_test' => true,
                    'view_patients' => true,
                    'admit_patient' => true,
                    'prescriptions' => true,
                    'lab_results' => true,
                    'prenatal_checkup' => true, // Custom action
                ],
            ],
            
            // PSYCHIATRY - Psychiatrist
            'psychiatry' => [
                'kpis' => [
                    'today_appointments' => true,
                    'pending_lab_results' => false,
                    'assigned_patients' => true,
                    'therapy_sessions' => true, // Custom KPI
                    'total_consultations' => false,
                ],
                'sections' => [
                    'quick_actions' => true,
                    'today_appointments' => true,
                    'assigned_patients' => true,
                    'therapy_schedule' => true, // Custom section
                    'weekly_schedule' => true,
                    'recent_medical_records' => true,
                    'pending_lab_tests' => false,
                    'patient_sessions' => true, // Custom section
                ],
                'quick_actions' => [
                    'new_medical_record' => true,
                    'request_lab_test' => false,
                    'view_patients' => true,
                    'admit_patient' => true,
                    'prescriptions' => true,
                    'lab_results' => false,
                    'schedule_therapy' => true, // Custom action
                ],
            ],
        ];
    }
    
    /**
     * Get custom KPI data for a specialization
     */
    public static function getCustomKPIs(string $specialization, $doctorId): array
    {
        $category = self::getSpecializationCategory($specialization);
        $customKPIs = [];
        
        switch ($category) {
            case 'pediatrics':
                // Count pediatric patients (age < 18)
                $patientModel = model('App\Models\PatientModel');
                $pediatricCount = $patientModel
                    ->where('attending_physician_id', $doctorId)
                    ->where('is_active', 1)
                    ->where('(discharge_date IS NULL OR discharge_date = "")', null, false)
                    ->findAll();
                
                $pediatricCount = count(array_filter($pediatricCount, function($p) {
                    if (empty($p['date_of_birth'])) return false;
                    $age = (int)date('Y') - (int)date('Y', strtotime($p['date_of_birth']));
                    return $age < 18;
                }));
                
                $customKPIs['pediatric_patients'] = $pediatricCount;
                break;
                
            case 'surgery':
                // Count scheduled surgeries (you'd need a surgeries table)
                $customKPIs['scheduled_surgeries'] = 0; // Placeholder
                break;
                
            case 'cardiology':
                // Count pending ECG requests
                $labTestModel = model('App\Models\LabTestModel');
                $ecgCount = $labTestModel
                    ->where('doctor_id', $doctorId)
                    ->like('test_name', 'ECG', 'both')
                    ->where('status !=', 'completed')
                    ->countAllResults();
                
                $customKPIs['ecg_pending'] = $ecgCount;
                break;
                
            case 'emergency':
                // Count active emergency cases
                $patientModel = model('App\Models\PatientModel');
                $emergencyCount = $patientModel
                    ->where('attending_physician_id', $doctorId)
                    ->where('is_active', 1)
                    ->where('admission_type', 'admission')
                    ->where('(discharge_date IS NULL OR discharge_date = "")', null, false)
                    ->countAllResults();
                
                $customKPIs['emergency_cases'] = $emergencyCount;
                break;
                
            case 'radiology':
                // Count imaging requests (you'd need an imaging_requests table)
                $customKPIs['imaging_requests'] = 0; // Placeholder
                $customKPIs['completed_scans'] = 0; // Placeholder
                break;
                
            case 'obgyn':
                // Count prenatal patients
                $patientModel = model('App\Models\PatientModel');
                $prenatalCount = $patientModel
                    ->where('attending_physician_id', $doctorId)
                    ->where('is_active', 1)
                    ->where('gender', 'Female')
                    ->where('(discharge_date IS NULL OR discharge_date = "")', null, false)
                    ->countAllResults();
                
                $customKPIs['prenatal_patients'] = $prenatalCount;
                break;
                
            case 'psychiatry':
                // Count therapy sessions (you'd need a therapy_sessions table)
                $customKPIs['therapy_sessions'] = 0; // Placeholder
                break;
        }
        
        return $customKPIs;
    }
}

