<?php

namespace App\Support;

class Spaniel
{
    public const ROWS = [
        '...........................HKHKHK........',
        '...........................KHHBBHK.......',
        '..........................KBBBHHBBK......',
        '.........................KBBBBBBBBBK.....',
        '.........................KBBBBBBTTBK.....',
        '.....K...................KBBHMBBEWTBKKKK.',
        '....KHK.................KBKHMBHTTTTLLLWNK',
        '...KHBMK.................KHMBHMTTTTTLTNNK',
        '....KHBMK....KKKKKKKKKKKKKMBHMBTTSTTTTKK.',
        '.....KMBMK.KKBBBBMBBBBMBBKBHMBHKSSSSSK...',
        '......KMBKKBBBBMBBBBMBBBBKHMBHMKBKKKK....',
        '.......KKKMBBBBMBBBBMBBBBKMBHMBKBK.......',
        '........KBBBBMBBBBMBBBBMBKBHMBHKBMK......',
        '........KBBBBMBBBBMBBBBMBKHMBHMBKMK......',
        '........KBBMBBBBMBBBBMBBBKMBHMBHKBK......',
        '........KBBMBBBBMBBBBMBBBBKHMBHSKBK......',
        '.........KBBBBMBBBBMBBBBMKMSMSMKBK.......',
        '.........KBBBBMBMMMMMMBBMBKKBBBBK........',
        '.........KBBBBBBKMKMKMKKBBBMBBBK.........',
        '........KMTTSKBBKK.K.K..KBBMTTSK.........',
        '........KMTTSKSSK.......KSSMTTSK.........',
        '........KMTTSKSSK.......KSSMTTSK.........',
        '.........KTTSKSSK.......KSSKTTSK.........',
        '.........KTTSKSSK.......KSSKTTSK.........',
        '.........KTTSKSSK.......KSSKTTSK.........',
        '.........KLLLLSSSK......KSSSLLLLK........',
        '..........KKKKKKK........KKKKKKK.........',
        '.........................................',
    ];

    public const FILL = [
        'K' => '#111011',
        'B' => '#2a2220',
        'M' => '#43352f',
        'H' => '#67544a',
        'T' => '#b46a32',
        'L' => '#d99350',
        'S' => '#87481f',
        'N' => '#0b0a0a',
        'E' => '#0b0a0a',
        'W' => '#f3ece6',
    ];

    public static function runs(int $fromX = 0, int $fromY = 0, ?int $toX = null, ?int $toY = null): array
    {
        $runs = [];
        foreach (self::ROWS as $y => $row) {
            if ($y < $fromY || ($toY !== null && $y > $toY)) continue;
            $end = min(strlen($row) - 1, $toX ?? PHP_INT_MAX);
            for ($x = $fromX; $x <= $end; $x++) {
                $c = $row[$x];
                if (! isset(self::FILL[$c])) continue;
                $w = 1;
                while ($c !== 'E' && $x + $w <= $end && $row[$x + $w] === $c && self::isTail($x + $w, $y) === self::isTail($x, $y)) $w++;
                $runs[] = ['x' => $x, 'y' => $y, 'w' => $w, 'fill' => self::FILL[$c], 'eye' => $c === 'E', 'tail' => self::isTail($x, $y)];
                $x += $w - 1;
            }
        }

        return $runs;
    }

    private static function isTail(int $x, int $y): bool
    {
        return $x <= 8 && $y <= 10;
    }
}
