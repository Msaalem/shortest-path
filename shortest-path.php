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

        // Set scores for start cell
        $gScore[$startX][$startY] = 0;
        $fScore[$startX][$startY] = heuristic($startX, $startY, $endX, $endY);

        while (!empty($openSet)) {
            // Find cell with lowest fScore in openSet
            $current = null;
            $lowestFScore = PHP_INT_MAX;
            foreach ($openSet as $cell) {
                if ($fScore[$cell[0]][$cell[1]] < $lowestFScore) {
                    $current = $cell;
                    $lowestFScore = $fScore[$cell[0]][$cell[1]];
                }
            }

            // If we've reached the end, reconstruct the path and return it
            if ($current[0] == $endX && $current[1] == $endY) {
                return reconstructPath($cameFrom, $current);
            }

            // Move current cell from openSet to closedSet
            $openSet = array_values(array_filter($openSet, function($cell) use ($current) {
                return $cell[0] != $current[0] || $cell[1] != $current[1];
            }));
            $closedSet[] = $current;

            // Check each neighbor of the current cell
            foreach (getNeighbors($maze, $current[0], $current[1]) as $neighbor) {
                // Ignore neighbors in closedSet
                if (in_array($neighbor, $closedSet)) {
                    continue;
                }

                // Calculate tentative gScore for this neighbor
                $tentativeGScore = $gScore[$current[0]][$current[1]] + $maze[$neighbor[0]][$neighbor[1]];

                // If this neighbor isn't in openSet, add it
                if (!in_array($neighbor, $openSet)) {
                    $openSet[] = $neighbor;
                } elseif ($tentativeGScore >= $gScore[$neighbor[0]][$neighbor[1]]) {
                    // This path isn't better than the existing path to the neighbor
                    continue;
                }

                // This path is the best we've seen so far
                $cameFrom[$neighbor[0]][$neighbor[1]] = $current;
                $gScore[$neighbor[0]][$neighbor[1]] = $tentativeGScore;
                $fScore[$neighbor[0]][$neighbor[1]] = $tentativeGScore + heuristic($neighbor[0], $neighbor[1], $endX, $endY);
                // Check if the neighbor is the end point
                if ($neighbor[0] == $endX && $neighbor[1] == $endY) {
                    // Reconstruct the path and return it
                    return reconstructPath($cameFrom, $neighbor);
                }
            }
        }

// No path was found
        return null;
    }

// Function to get all neighbors of a cell
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

// Function to reconstruct the path from start to end
    function reconstructPath($cameFrom, $current) {
        $path = array();
        while ($current != null) {
            $path[] = $current;
            $current = isset($cameFrom[$current[0]][$current[1]]) ? $cameFrom[$current[0]][$current[1]] : null;
        }
        return array_reverse($path);
    }

// Function to calculate the heuristic value (estimated distance) between two cells
    function heuristic($x1, $y1, $x2, $y2) {
        return abs($x1 - $x2) + abs($y1 - $y2);
    }

// Function to find the shortest path and update the maze array
    function findShortestPathAndUpdateMaze(&$maze, $startX, $startY, $endX, $endY) {
        $path = findShortestPath($maze, $startX, $startY, $endX, $endY);
        if ($path == null) {
// No path was found, return the original maze
            echo "No path was found";
            exit(1);
        }


// Update the maze with the shortest path
        foreach ($path as $cell) {
            $maze[$cell[0]][$cell[1]] = "X";
        }
        return $maze;
    }

// function to check if all elements of the matrix are between 0 and 9

function validateMatrixSize($input) {
    $size = explode(' ', $input);
    if (count($size) != 2) {
        return false;
    }
    if (!ctype_digit($size[0]) || !ctype_digit($size[1])) {
        return false;
    }
    return true;
}

// validate matrix
function validateMatrix($maze) {
    foreach ($maze as $row) {
        foreach ($row as $elem) {
            if ($elem < 0 || $elem > 9) {
                return false;
            }
        }
    }
    return true;
}

// get matrix size from user input
do {
    echo "Enter matrix size (m x n): ";
    $input = readline();
} while (!validateMatrixSize($input));
list($m, $n) = explode(' ', $input);
$maze = array();

// get matrix elements from user input
echo "Enter matrix elements:\n";
for ($i = 0; $i < $m; $i++) {
    echo "Row $i: ";
    $row = explode(' ', readline());
    if (count($row) != $n) {
        echo "Error: Row must contain $n elements.\n";
        exit(1);
    }
    $maze[] = $row;
}

// validate matrix
if (!validateMatrix($maze)) {
    echo "Error: Matrix must contain only elements between 0 and 9.\n";
    exit(1);
}

// function to check if coordinates are valid for a given matrix size
function validateCoordinates($m, $n, $x, $y) {
    if ($x < 0 || $x >= $m || $y < 0 || $y >= $n) {
        return false;
    }
    return true;
}

// get start and end coordinates from user input
echo "Enter starting coordinates (x,y): ";
list($startX, $startY) = explode(',', readline());
if (!validateCoordinates($m, $n, $startX, $startY)) {
    echo "Error: Starting coordinates are invalid.\n";
    exit(1);
}

echo "Enter ending coordinates (x,y): ";
list($endX, $endY) = explode(',', readline());
if (!validateCoordinates($m, $n, $endX, $endY)) {
    echo "Error: Ending coordinates are invalid.\n";
    exit(1);
}

$result = findShortestPathAndUpdateMaze($maze, $startX, $startY, $endX, $endY);

// function to print the matrix
function printMatrix($result) {
    foreach ($result as $row) {
        foreach ($row as $element) {
            echo $element . " ";
        }
        echo "\n";
    }
}

printMatrix($result);
?>