# кратчайший путь в лабиринте

Это PHP-реализация алгоритма A*, популярного алгоритма поиска пути, используемого в искусственном интеллекте и робототехнике. Алгоритм находит кратчайший путь между начальной и конечной точкой в лабиринте или сетке, принимая во внимание препятствия и их вес.

## Как использовать

Функция `findShortestPath` принимает следующие параметры:

- `$maze` : двумерный массив, представляющий лабиринт, где 0 обозначает стены, а целые положительные числа (1-9) обозначают стоимость перемещения через клетку.

- `$startX` и `$startY` : начальные координаты в лабиринте.

- `$endX` и `$endY` : координаты окончания лабиринта.

Функция возвращает массив координат, представляющий кратчайший путь от начальной точки до конечной.

Если путь не найден, функция возвращает `No path was found`.

## Функции


## `findShortestPath`:

### Параметры

- `$maze` : двумерный массив, представляющий лабиринт, где 0 - стены, а целые положительные числа (1-9) представляют стоимость перемещения через клетку.

- `$startX` и `$startY` : начальные координаты в лабиринте.

- `$endX` и `$endY` : координаты окончания лабиринта.

### Возвращает

- Массив координат, представляющий кратчайший путь от начальной точки до конечной точки.

- `No path was found`, если путь не найден.

 ## `getNeighbors`

Эта функция возвращает массив координат, представляющих соседей данной клетки.

### Параметры

- `$maze`: двумерный массив, представляющий лабиринт, где 0 - стены, а целые положительные числа - стоимость перемещения через клетку.
- `$x` и `$y`: координаты клетки.

### Возвращает

- Массив координат, представляющих соседей клетки.

## `reconstructPath`

Эта функция восстанавливает кратчайший путь из массива `cameFrom`.

### Параметры

- `$cameFrom` : массив, который отображает каждую клетку на ее родительскую клетку в кратчайшем пути.
- `$current` : текущая ячейка.

### Возвращает

- Массив координат, представляющий кратчайший путь от начальной точки до конечной.

## `эвристика`

Эта функция вычисляет эвристическое значение (расчетное расстояние) между двумя клетками.


### Параметры

- `$x1` и `$y1`: координаты первой клетки.

### Возвращает

- Расчетное расстояние между двумя ячейками.

## `findShortestPathAndUpdateMaze`

### Параметры

- `$maze` : двумерный массив, представляющий лабиринт, где 0 - стены, а целые положительные числа (1-9) представляют стоимость перемещения через клетку.

- `$startX` и `$startY` : начальные координаты в лабиринте.

- `$endX` и `$endY` : координаты окончания лабиринта.

### Возвращает

- лабиринт, в котором кратчайший путь обозначен буквой `Х`

    функция вызывает `findShortestPath` для получения кратчайшего пути между начальной и конечной позициями лабиринта. Если путь не найден, функция выводит сообщение       `No path was found` и выходит.

    Если путь найден, функция выполняет итерацию по каждой клетке пути и обновляет лабиринт, помечая каждую клетку знаком `X`. Это делается путем прохождения каждой       клетки пути и доступа к ее координатам в лабиринте с помощью $cell[0] и $cell[1]. Затем лабиринт обновляется путем установки значения этой клетки на `X`.



