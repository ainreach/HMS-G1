<?php

namespace Config;

/**
 * Doctor Specializations Configuration
 * Complete list of all doctor types/specializations in a hospital
 */
class DoctorSpecializations
{
    /**
     * Get all doctor specializations organized by category
     * 
     * @return array
     */
    public static function getAll(): array
    {
        return [
            // Pediatrics
            'Pediatrician (Pedia)' => 'Pediatrician (Pedia)',
            'Neonatologist' => 'Neonatologist',
            'Pediatric Cardiologist' => 'Pediatric Cardiologist',
            'Pediatric Neurologist' => 'Pediatric Neurologist',
            'Pediatric Surgeon' => 'Pediatric Surgeon',
            
            // General & Primary Care
            'General Practitioner (GP)' => 'General Practitioner (GP)',
            'Family Medicine Doctor' => 'Family Medicine Doctor',
            
            // Internal Medicine & Subspecialties
            'Internist (Internal Medicine)' => 'Internist (Internal Medicine)',
            'Cardiologist' => 'Cardiologist',
            'Endocrinologist' => 'Endocrinologist',
            'Gastroenterologist' => 'Gastroenterologist',
            'Nephrologist' => 'Nephrologist',
            'Pulmonologist' => 'Pulmonologist',
            'Rheumatologist' => 'Rheumatologist',
            'Hematologist' => 'Hematologist',
            'Oncologist' => 'Oncologist',
            'Infectious Disease Specialist' => 'Infectious Disease Specialist',
            
            // Brain & Mental Health
            'Neurologist' => 'Neurologist',
            'Psychiatrist' => 'Psychiatrist',
            
            // Surgical Specialties
            'General Surgeon' => 'General Surgeon',
            'Orthopedic Surgeon' => 'Orthopedic Surgeon',
            'Neurosurgeon' => 'Neurosurgeon',
            'Cardiothoracic Surgeon' => 'Cardiothoracic Surgeon',
            'Plastic & Reconstructive Surgeon' => 'Plastic & Reconstructive Surgeon',
            'Vascular Surgeon' => 'Vascular Surgeon',
            
            // Women's Health
            'Obstetrician-Gynecologist (OB-GYN)' => 'Obstetrician-Gynecologist (OB-GYN)',
            'Maternal-Fetal Medicine Specialist' => 'Maternal-Fetal Medicine Specialist',
            
            // Special Senses
            'Ophthalmologist' => 'Ophthalmologist',
            'Otolaryngologist (ENT)' => 'Otolaryngologist (ENT)',
            
            // Skin & Rehabilitation
            'Dermatologist' => 'Dermatologist',
            'Rehabilitation Medicine (Physiatrist)' => 'Rehabilitation Medicine (Physiatrist)',
            'Sports Medicine Doctor' => 'Sports Medicine Doctor',
            
            // Diagnostics & Support
            'Radiologist' => 'Radiologist',
            'Pathologist' => 'Pathologist',
            'Anesthesiologist' => 'Anesthesiologist',
            'Emergency Medicine Doctor' => 'Emergency Medicine Doctor',
            'Critical Care / Intensivist' => 'Critical Care / Intensivist',
            
            // Other Specialists
            'Urologist' => 'Urologist',
            'Allergist & Immunologist' => 'Allergist & Immunologist',
            'Geriatrician' => 'Geriatrician',
            'Pain Management Specialist' => 'Pain Management Specialist',
        ];
    }
    
    /**
     * Get specializations grouped by category
     * 
     * @return array
     */
    public static function getGrouped(): array
    {
        return [
            'Pediatrics' => [
                'Pediatrician (Pedia)',
                'Neonatologist',
                'Pediatric Cardiologist',
                'Pediatric Neurologist',
                'Pediatric Surgeon',
            ],
            'General & Primary Care' => [
                'General Practitioner (GP)',
                'Family Medicine Doctor',
            ],
            'Internal Medicine & Subspecialties' => [
                'Internist (Internal Medicine)',
                'Cardiologist',
                'Endocrinologist',
                'Gastroenterologist',
                'Nephrologist',
                'Pulmonologist',
                'Rheumatologist',
                'Hematologist',
                'Oncologist',
                'Infectious Disease Specialist',
            ],
            'Brain & Mental Health' => [
                'Neurologist',
                'Psychiatrist',
            ],
            'Surgical Specialties' => [
                'General Surgeon',
                'Orthopedic Surgeon',
                'Neurosurgeon',
                'Cardiothoracic Surgeon',
                'Plastic & Reconstructive Surgeon',
                'Vascular Surgeon',
            ],
            'Women\'s Health' => [
                'Obstetrician-Gynecologist (OB-GYN)',
                'Maternal-Fetal Medicine Specialist',
            ],
            'Special Senses' => [
                'Ophthalmologist',
                'Otolaryngologist (ENT)',
            ],
            'Skin & Rehabilitation' => [
                'Dermatologist',
                'Rehabilitation Medicine (Physiatrist)',
                'Sports Medicine Doctor',
            ],
            'Diagnostics & Support' => [
                'Radiologist',
                'Pathologist',
                'Anesthesiologist',
                'Emergency Medicine Doctor',
                'Critical Care / Intensivist',
            ],
            'Other Specialists' => [
                'Urologist',
                'Allergist & Immunologist',
                'Geriatrician',
                'Pain Management Specialist',
            ],
        ];
    }
    
    /**
     * Check if a specialization is valid
     * 
     * @param string $specialization
     * @return bool
     */
    public static function isValid(string $specialization): bool
    {
        return in_array($specialization, self::getAll());
    }
}

