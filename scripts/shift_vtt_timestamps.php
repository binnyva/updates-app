<?php
/**
 * Shift all timestamps in a VTT file by a given offset (in seconds).
 * Use a negative offset to subtract time.
 *
 * Usage: php scripts/shift_vtt_timestamps.php <file.vtt> <offset_seconds>
 * Example (subtract 1 hour): php scripts/shift_vtt_timestamps.php file.vtt -3600
 */

if ($argc < 3) {
    echo "Usage: php {$argv[0]} <file.vtt> <offset_seconds>\n";
    echo "Example: php {$argv[0]} storage/app/private/subtitles/file.vtt -3600\n";
    exit(1);
}

$file = $argv[1];
$offsetSeconds = (float) $argv[2];

if (!file_exists($file)) {
    echo "Error: File not found: $file\n";
    exit(1);
}

function parseTimestamp(string $ts): float
{
    [$h, $m, $s] = explode(':', $ts);
    return ($h * 3600) + ($m * 60) + (float) $s;
}

function formatTimestamp(float $seconds): string
{
    if ($seconds < 0) $seconds = 0;
    $h = (int) ($seconds / 3600);
    $m = @(int) (($seconds % 3600) / 60);
    $s = fmod($seconds, 60);
    return sprintf('%02d:%02d:%06.3f', $h, $m, $s);
}

$content = file_get_contents($file);

$shifted = preg_replace_callback(
    '/(\d{2}:\d{2}:\d{2}\.\d{3})\s+-->\s+(\d{2}:\d{2}:\d{2}\.\d{3})/',
    function (array $matches) use ($offsetSeconds): string {
        $start = formatTimestamp(parseTimestamp($matches[1]) + $offsetSeconds);
        $end   = formatTimestamp(parseTimestamp($matches[2]) + $offsetSeconds);
        return "$start --> $end";
    },
    $content
);

file_put_contents($file, $shifted);
echo "Done. Timestamps shifted by {$offsetSeconds}s in: $file\n";
