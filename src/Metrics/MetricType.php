<?php

namespace Aternos\Mclogs\Metrics;

enum MetricType: string
{
    case COUNTER = 'counter';
    case GAUGE = 'gauge';
}
