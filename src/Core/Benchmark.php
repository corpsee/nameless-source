<?php

/**
 * This file is part of the Nameless framework.
 * For the full copyright and license information, please view the LICENSE
 *
 * @package    Nameless
 * @author     Corpsee <poisoncorpsee@gmail.com>
 * @copyright  2012 - 2014. Corpsee <poisoncorpsee@gmail.com>
 * @link       https://github.com/corpsee/Nameless
 */

namespace Nameless\Core;

/**
 * Benchmark class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class Benchmark
{
    const APPLICATION_MARKER = '_application_';

    /**
     * @var array
     */
    protected $markers = array();

    //TODO: add marker groups
    /**
     * @param string $marker
     */
    public function startMarker($marker)
    {
        $this->markers[$marker]['start']['time']   = microtime(true);
        $this->markers[$marker]['start']['memory'] = memory_get_usage();
    }

    /**
     * @param string $marker
     */
    public function stopMarker($marker)
    {
        $this->markers[$marker]['stop']['time']   = microtime(true);
        $this->markers[$marker]['stop']['memory'] = memory_get_usage();
    }

    /**
     * @param string $marker
     */
    public function deleteMarker($marker)
    {
        unset($this->markers[$marker]);
    }

    /**
     * @param string $marker
     *
     * @return array
     */
    public function getTotal($marker)
    {
        if (isset($this->markers[$marker]['total'])) {
            return $this->markers[$marker]['total'];
        }

        if ($marker === self::APPLICATION_MARKER) {
            $this->markers[self::APPLICATION_MARKER]['total']['time'] = microtime(true) - START_TIME;
            $this->markers[self::APPLICATION_MARKER]['total']['memory'] = memory_get_usage() - START_MEMORY;

            return $this->markers[self::APPLICATION_MARKER]['total'];
        }

        if (!isset($this->markers[$marker]['stop'])) {
            $marker_array = $this->markers[$marker];

            $marker_array['total']['time'] = microtime(true) - $marker_array['start']['time'];
            $marker_array['total']['memory'] = memory_get_usage() - $marker_array['memory']['time'];

            return $marker_array['total'];
        } else {
            $this->markers[$marker]['total']['time'] = $this->markers[$marker]['stop']['time'] - $this->markers[$marker]['start']['time'];
            $this->markers[$marker]['total']['memory'] = $this->markers[$marker]['stop']['memory'] - $this->markers[$marker]['memory']['time'];

            return $this->markers[$marker]['total'];
        }
    }

    /**
     * @param array $markers
     *
     * @return array
     */
    public function getMax(array $markers)
    {
        $max = [
            'time'   => 0,
            'memory' => 0,
        ];

        foreach ($markers as $marker) {
            $total = $this->getTotal($marker);

            if ($total['time'] > $max['time']) {
                $max['time'] = $total['time'];
            }

            if ($total['memory'] > $max['memory']) {
                $max['memory'] = $total['memory'];
            }
        }

        return $max;
    }

    /**
     * @param array $markers
     *
     * @return array
     */
    public function getMin(array $markers)
    {
        $min = [
            'time'   => 0,
            'memory' => 0,
        ];

        foreach ($markers as $marker) {
            $total = $this->getTotal($marker);

            if ($min['time'] === 0 || $total['time'] < $min['time']) {
                $min['time'] = $total['time'];
            }

            if ($min['memory'] === 0 || $total['memory'] < $min['memory']) {
                $min['memory'] = $total['memory'];
            }
        }

        return $min;
    }

    /**
     * @param array $markers
     *
     * @return array
     */
    public function getTotals(array $markers)
    {
        $totals = [
            'time'   => 0,
            'memory' => 0,
        ];

        foreach ($markers as $marker) {
            $total = $this->getTotal($marker);

            $totals['time']   += $total['time'];
            $totals['memory'] += $total['memory'];
        }

        return $totals;
    }

    /**
     * @param array $markers
     *
     * @return array
     */
    public function getAverage(array $markers)
    {
        $count = count($markers);
        $total = $this->getTotals($markers);

        $average = [
            'time' => $total['time'] / $count,
            'memory' => $total['memory'] / $count,
        ];

        return $average;
    }

    /**
     * @param array $markers
     *
     * @return array
     */
    public function getStatistic(array $markers)
    {
        return [
            'min'     => $this->getMax($markers),
            'max'     => $this->getMin($markers),
            'total'   => $this->getTotals($markers),
            'average' => $this->getAverage($markers),
        ];
    }

    /**
     * @return array
     */
    public function getAppStatistic()
    {
        return $this->getTotals(array(self::APPLICATION_MARKER));
    }
}