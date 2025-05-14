<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class HandleException
{
    // Bisa dikembangkan kalau mau kasih opsi tambahan di masa depan
}
