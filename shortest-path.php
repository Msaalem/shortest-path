<?php

function findShortestPath($maze, $startX, $startY, $endX, $endY) {
    $openSet = array(array($startX, $startY));
    $closedSet = array();
    $cameFrom = array();
    $gScore = array();
    $fScore = array();

    // Initialize scores for all cells
    for ($x = 0; $x < count($maze); $x++) {
        $gScore[$x] = array();
        $fScore[$x] = array();
        for ($y = 0; $y < count($maze[$x]); $y++) {
            $gScore[$x][$y] = PHP_INT_MAX;
            $fScore[$x][$y] = PHP_INT_MAX;
        }
    }

    $gScore[$startX][$startY] = 0;
    $fScore[$startX][$startY] = heuristic($startX, $startY, $endX, $endY);

    while (!empty($openSet)) {
        $current = null;
        $lowestFScore = PHP_INT_MAX;
        foreach ($openSet as $cell) {
            if ($fScore[$cell[0]][$cell[1]] < $lowestFScore) {
                $current = $cell;
                $lowestFScore = $fScore[$cell[0]][$cell[1]];
            }
        }

        if ($current[0] == $endX && $current[1] == $endY) {
            return reconstructPath($cameFrom, $current);
        }

        $openSet = array_values(array_filter($openSet, function($cell) use ($current) {
            return $cell[0] != $current[0] || $cell[1] != $current[1];
        }));
        $closedSet[] = $current;

        foreach (getNeighbors($maze, $current[0], $current[1]) as $neighbor) {
            if (in_array($neighbor, $closedSet)) {
                continue;
            }

            $tentativeGScore = $gScore[$current[0]][$current[1]] + $maze[$neighbor[0]][$neighbor[1]];

            if (!in_array($neighbor, $openSet)) {
                $openSet[] = $neighbor;
            } elseif ($tentativeGScore >= $gScore[$neighbor[0]][$neighbor[1]]) {
                continue;
            }

            $cameFrom[$neighbor[0]][$neighbor[1]] = $current;
            $gScore[$neighbor[0]][$neighbor[1]] = $tentativeGScore;
            $fScore[$neighbor[0]][$neighbor[1]] = $tentativeGScore + heuristic($neighbor[0], $neighbor[1], $endX, $endY);
        }
    }

    return null;
}

function getNeighbors($maze, $x, $y) {
    $neighbors = array();
    if ($x > 0 && $maze[$x-1][$y] != 0) {
        $neighbors[] = array($x-1, $y);
    }
    if ($x < count($maze)-1 && $maze[$x+1][$y] != 0) {
        $neighbors[] = array($x+1, $y);
    }
    if ($y > 0 && $maze[$x][$y-1] != 0) {
        $neighbors[] = array($x, $y-1);
    }
    if ($y < count($maze[$x])-1 && $maze[$x][$y+1] != 0) {
        $neighbors[] = array($x, $y+1);
    }
    return $neighbors;
}

function reconstructPath($cameFrom, $current) {
    $path = array();
    while ($current != null) {
        $path[] = $current;
        $current = isset($cameFrom[$current[0]][$current[1]]) ? $cameFrom[$current[0]][$current[1]] : null;
    }
    return array_reverse($path);
}

function heuristic($x1, $y1, $x2, $y2) {
    return abs($x1 - $x2) + abs($y1 - $y2);
}

// Function to print matrix with path marked as X
function printMatrix($maze, $path) {
    $visualMaze = array_map(function($row) {
        return array_map('strval', $row);
    }, $maze);

    foreach ($path as $point) {
        $visualMaze[$point[0]][$point[1]] = 'X';
    }

    echo "\nMaze visualization with path marked as X:\n";
    echo str_repeat('-', count($visualMaze[0]) * 4 + 1) . "\n";

    foreach ($visualMaze as $row) {
        echo "| ";
        foreach ($row as $cell) {
            echo $cell . " | ";
        }
        echo "\n";
        echo str_repeat('-', count($row) * 4 + 1) . "\n";
    }
}

// Read maze size
$size = trim(fgets(STDIN));
if (!preg_match('/^\d+\s+\d+$/', $size)) {
    fwrite(STDERR, "Invalid maze size format\n");
    exit(1);
}
list($m, $n) = explode(' ', $size);

// Validate that dimensions are greater than zero
if ($m <= 0 || $n <= 0) {
    fwrite(STDERR, "Maze dimensions must be greater than zero\n");
    exit(1);
}

// Read maze structure
$maze = array();
for ($i = 0; $i < $m; $i++) {
    $row = trim(fgets(STDIN));
    $values = explode(' ', $row);

    if (count($values) != $n) {
        fwrite(STDERR, "Invalid number of elements in row $i\n");
        exit(1);
    }

    foreach ($values as $value) {
        if (!preg_match('/^[0-9]$/', $value)) {
            fwrite(STDERR, "Invalid maze element: $value\n");
            exit(1);
        }
    }

    $maze[] = array_map('intval', $values);
}

// Read start coordinates
$start = trim(fgets(STDIN));
if (!preg_match('/^\d+\s+\d+$/', $start)) {
    fwrite(STDERR, "Invalid start coordinates format\n");
    exit(1);
}
list($startX, $startY) = explode(' ', $start);

// Read end coordinates
$end = trim(fgets(STDIN));
if (!preg_match('/^\d+\s+\d+$/', $end)) {
    fwrite(STDERR, "Invalid end coordinates format\n");
    exit(1);
}
list($endX, $endY) = explode(' ', $end);

// Validate coordinates
if ($startX >= $m || $startY >= $n || $endX >= $m || $endY >= $n) {
    fwrite(STDERR, "Coordinates out of bounds\n");
    exit(1);
}

if ($maze[$startX][$startY] == 0 || $maze[$endX][$endY] == 0) {
    fwrite(STDERR, "Start or end position is a wall\n");
    exit(1);
}

// Find path
$path = findShortestPath($maze, $startX, $startY, $endX, $endY);

if ($path === null) {
    fwrite(STDERR, "No path found\n");
    exit(1);
}

// Output in the test format (with blank line before)
echo "координаты кратчайших путей:  \n";
foreach ($path as $point) {
    echo $point[0] . " " . $point[1] . "\n";
}
echo ".\n";

// Output visualization with X's marking the path
printMatrix($maze, $path);
