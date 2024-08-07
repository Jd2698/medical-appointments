<?php

namespace App\Enums;

enum RolesEnum: string
{
    case ADMIN = 'admin';
    case DOCTOR = 'doctor';
    case PATIENT = 'patient';
}
