<?php

namespace Aternos\Mclogs\Filter;

enum FilterType: string
{
    case TRIM = 'trim';
    case LIMIT_BYTES = 'limit-bytes';
    case LIMIT_LINES = 'limit-lines';
    case REGEX = 'regex';
}
