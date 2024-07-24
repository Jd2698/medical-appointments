<?php

namespace App\Enums;

enum RolesEnum: string
{
    case ADMIN = 'admin';
    case CLIENT = 'client';
    case DOCTOR = 'doctor';
}
