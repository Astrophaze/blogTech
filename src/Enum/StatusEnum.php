<?php
namespace App\Enum;

enum StatusEnum: string
{
    case Draft = 'draft';
    case Published = 'published';
}